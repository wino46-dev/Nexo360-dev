<?php

namespace App\Jobs;

use App\Mail\AuditLogsExportReadyMail;
use App\Models\AuditLog;
use App\Models\ControlError;
use App\Models\ControlSesion;
use App\Models\EventoHomeTotem;
use App\Models\GrabacionTarjetum;
use App\Models\PagoTotem;
use App\Models\RespuestaPago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileObject;

class ExportAuditLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $dataset;
    public string $dateStart;
    public string $dateEnd;
    public string $email;
    public ?int $requesterId;

    public function __construct(string $dataset, string $dateStart, string $dateEnd, string $email, ?int $requesterId = null)
    {
        $this->dataset = $dataset;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->email = $email;
        $this->requesterId = $requesterId;
    }

    public function handle(): void
    {
        [$query, $filenamePrefix] = $this->buildQueryAndName();

        // Create temporary CSV in storage/tmp
        $disk = Storage::disk('local');
        $dir = 'tmp/exports';
        if (!$disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }
        $filename = $filenamePrefix . '_' . now()->format('Ymd_His') . '.csv';
        $path = $dir . '/' . $filename;

        $fullPath = storage_path('app/' . $path);
        $file = new SplFileObject($fullPath, 'w');

        $chunkSize = 1000;
        $writtenHeader = false;
        $query->orderBy('id')->chunk($chunkSize, function ($rows) use (&$writtenHeader, $file) {
            foreach ($rows as $row) {
                $array = $row->toArray();
                if (!$writtenHeader) {
                    $file->fputcsv(array_keys($array));
                    $writtenHeader = true;
                }
                // stringify nested arrays/objects
                array_walk($array, function (&$value) {
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                    }
                });
                $file->fputcsv($array);
            }
        });

        // Email the file
        Mail::to($this->email)->send(new AuditLogsExportReadyMail(
            $this->dataset,
            $this->dateStart,
            $this->dateEnd,
            $filename,
            $fullPath,
            $this->requesterId
        ));

        // Optionally delete after send (keep a copy for a short time)
        // $disk->delete($path);
    }

    private function buildQueryAndName(): array
    {
        $start = $this->dateStart;
        $end = $this->dateEnd;

        switch ($this->dataset) {
            case 'audit-logs':
                $q = AuditLog::whereBetween('created_at', [$start, $end]);
                $name = 'audit_logs';
                break;
            case 'control-errors':
                $q = ControlError::whereBetween('created_at', [$start, $end]);
                $name = 'control_errors';
                break;
            case 'control-sesion':
                $q = ControlSesion::whereBetween('created_at', [$start, $end]);
                $name = 'control_sesion';
                break;
            case 'evento-home-totem':
                $q = EventoHomeTotem::whereBetween('created_at', [$start, $end]);
                $name = 'evento_home_totem';
                break;
            case 'grabacion-tarjeta':
                $q = GrabacionTarjetum::whereBetween('created_at', [$start, $end]);
                $name = 'grabacion_tarjeta';
                break;
            case 'respuesta-pago':
                $q = RespuestaPago::whereBetween('created_at', [$start, $end]);
                $name = 'respuesta_pago';
                break;
            case 'pago-totem':
                $q = PagoTotem::whereBetween('created_at', [$start, $end]);
                $name = 'pago_totem';
                break;
            default:
                throw new \InvalidArgumentException('Dataset no soportado');
        }

        return [$q, $name];
    }
}
