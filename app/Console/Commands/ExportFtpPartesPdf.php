<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\CheckIn;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Throwable;
use App\Models\FtpUploadLog;

class ExportFtpPartesPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-ftp-partes-pdf {establecimiento_id} {fecha}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Ejecutando tarea ExportFtpPartesPdf");
        $establecimiento_id = $this->argument('establecimiento_id');
        $fecha = $this->argument('fecha');

        $establecimiento = \App\Models\Establecimiento::find($establecimiento_id);

        $checkins = CheckIn::select(
            'check_in.*',
            'reservations.name as reservation_name', // Ejemplo: agregar código de la reserva
            'establecimientos.nombre as establecimiento_name' // Ejemplo: agregar estado de la reserva
        )
            ->join('reservations', 'check_in.reservation_id', '=', 'reservations.id')
            ->join('folios', 'reservations.folio_id', '=', 'folios.id')
            ->join('establecimientos', 'folios.establecimiento_id', '=', 'establecimientos.id')
            ->where('check_in.checkin_partner_state', 'onboard')
            ->where('folios.establecimiento_id', $establecimiento_id)
            ->whereDate('check_in.created_at', $fecha)
            ->get();

        if (count($checkins) > 0) {
            Log::info("Checkins encontrados: " . count($checkins));
            if ($establecimiento->partes_tipo_envio == 'ftp') {

                $ftpConfig = json_decode($establecimiento->partes_ftp_data, true);
                if (empty($ftpConfig) || !isset($ftpConfig['driver']) || !isset($ftpConfig['host'])) {
                    Log::error('Configuración FTP inválida para establecimiento ' . $establecimiento_id);
                    $this->error('Configuración FTP inválida.');
                    return Command::FAILURE;
                }

                $diskName = 'ftp_cliente_' . $establecimiento->id;
                Config::set("filesystems.disks.$diskName", $ftpConfig);

                $lockKey = "export_ftp_partes:{$establecimiento_id}:{$fecha}";
                $lock = Cache::lock($lockKey, 600);
                if (!$lock->get()) {
                    $this->warn('Otra exportación está en curso para este establecimiento y fecha.');
                    Log::warning("Lock activo: $lockKey");
                    return Command::SUCCESS;
                }

                try {
                    $fecha_partes = explode('-', $fecha);
                    $carpeta_destino = $fecha_partes[0] . '/' . $fecha_partes[1] . '/' . $fecha_partes[2]  . '/';

                    // Asegurar que el directorio remoto existe (idempotente)
                    try {
                        Storage::disk($diskName)->makeDirectory($carpeta_destino);
                    } catch (Throwable $e) {
                        Log::warning('No se pudo crear/asegurar el directorio remoto: ' . $carpeta_destino . ' - ' . $e->getMessage());
                    }

                    $ok = 0; $skip = 0; $fail = 0;
                    foreach ($checkins as $checkin) {
                        $localPath = public_path('parte-viajero/parte_viajero_' . $checkin->id . '.pdf');
                        if (!file_exists($localPath)) {
                            Log::warning("Archivo local no existe para checkin {$checkin->id}: $localPath");
                            $fail++; continue;
                        }

                        $remotePath = $carpeta_destino . 'parte_viajero_' . $checkin->id . '.pdf';
                        $tmpPath = $remotePath . '.tmp';

                        // Idempotencia: si ya existe y tiene mismo tamaño, saltar
                        try {
                            if (Storage::disk($diskName)->exists($remotePath)) {
                                $remoteSize = Storage::disk($diskName)->size($remotePath);
                                $localSize = filesize($localPath);
                                if ($remoteSize === $localSize) {
                                    try {
                                        $lastMod = null; try { $lastMod = Storage::disk($diskName)->lastModified($remotePath); } catch (Throwable $e2) {}
                                        FtpUploadLog::create([
                                            'establecimiento_id' => $establecimiento_id,
                                            'check_in_id' => $checkin->id,
                                            'disk' => $diskName,
                                            'local_path' => $localPath,
                                            'remote_path' => $remotePath,
                                            'status' => 'skip',
                                            'attempts' => 0,
                                            'size_local' => $localSize,
                                            'size_remote' => $remoteSize,
                                            'remote_last_modified' => $lastMod ? date('Y-m-d H:i:s', $lastMod) : null,
                                            'uploaded_at' => null,
                                            'message' => 'Archivo ya existía con el mismo tamaño. No se re-subió.',
                                            'meta' => null,
                                        ]);
                                    } catch (Throwable $eLog) {}
                                    $skip++; continue;
                                }
                            }
                        } catch (Throwable $e) {
                            // Continuar intentando subir si no se puede obtener tamaño
                        }

                        $attempts = 0; $maxAttempts = 3; $uploaded = false;
                        $logBase = [
                            'establecimiento_id' => $establecimiento_id,
                            'check_in_id' => $checkin->id,
                            'disk' => $diskName,
                            'local_path' => $localPath,
                            'remote_path' => $remotePath,
                        ];

                        while ($attempts < $maxAttempts && !$uploaded) {
                            $attempts++;
                            try {
                                // Subir a .tmp primero
                                Storage::disk($diskName)->put($tmpPath, fopen($localPath, 'r'));
                                // Mover de forma atómica al destino
                                if (Storage::disk($diskName)->exists($remotePath)) {
                                    Storage::disk($diskName)->delete($remotePath);
                                }
                                Storage::disk($diskName)->move($tmpPath, $remotePath);

                                // Verificar tamaño final
                                $remoteSize = null;
                                try { $remoteSize = Storage::disk($diskName)->size($remotePath); } catch (Throwable $e) {}
                                if ($remoteSize !== null && $remoteSize === filesize($localPath)) {
                                    $uploaded = true; $ok++;
                                    try {
                                        $lastMod = null; try { $lastMod = Storage::disk($diskName)->lastModified($remotePath); } catch (Throwable $e2) {}
                                        FtpUploadLog::create($logBase + [
                                            'status' => 'success',
                                            'attempts' => $attempts,
                                            'size_local' => filesize($localPath),
                                            'size_remote' => $remoteSize,
                                            'remote_last_modified' => $lastMod ? date('Y-m-d H:i:s', $lastMod) : null,
                                            'uploaded_at' => now(),
                                            'message' => 'Subida completada y verificada.',
                                            'meta' => null,
                                        ]);
                                    } catch (Throwable $eLog) {}
                                    break;
                                } else {
                                    throw new \RuntimeException('Verificación de tamaño fallida');
                                }
                            } catch (Throwable $e) {
                                Log::warning("Intento {$attempts}/{$maxAttempts} fallido para subir {$localPath} => {$remotePath}: " . $e->getMessage());
                                // Intentar limpiar tmp
                                try { if (Storage::disk($diskName)->exists($tmpPath)) { Storage::disk($diskName)->delete($tmpPath); } } catch (Throwable $te) {}
                                if ($attempts < $maxAttempts) { usleep(500000); } // 0.5s
                            }
                        }

                        if (!$uploaded) { $fail++;
                            try {
                                FtpUploadLog::create($logBase + [
                                    'status' => 'fail',
                                    'attempts' => $attempts,
                                    'size_local' => @filesize($localPath) ?: null,
                                    'size_remote' => isset($remoteSize) ? $remoteSize : null,
                                    'remote_last_modified' => null,
                                    'uploaded_at' => null,
                                    'message' => 'No se pudo subir el archivo tras varios intentos.',
                                    'meta' => ['last_error' => isset($e) ? (string)$e : null],
                                ]);
                            } catch (Throwable $eLog) {}
                        }
                    }

                    $this->line("Backup de partes finalizado. OK={$ok}, SKIP={$skip}, FAIL={$fail}. Fecha: $fecha");
                    Log::info("Export FTP partes finalizado: est={$establecimiento_id}, fecha={$fecha}, ok={$ok}, skip={$skip}, fail={$fail}");
                } finally {
                    optional($lock)->release();
                }
            }
        } else {
            $this->line("No hay checkins para el establecimiento: $establecimiento_id, Fecha: $fecha");
            Log::info("No hay checkins para el establecimiento: $establecimiento_id, Fecha: $fecha");
        }
    }
}
