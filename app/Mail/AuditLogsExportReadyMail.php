<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuditLogsExportReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $dataset;
    public string $dateStart;
    public string $dateEnd;
    public string $filename;
    public ?int $requesterId;

    protected string $fullPath;

    public function __construct(string $dataset, string $dateStart, string $dateEnd, string $filename, string $fullPath, ?int $requesterId = null)
    {
        $this->dataset = $dataset;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->filename = $filename;
        $this->fullPath = $fullPath;
        $this->requesterId = $requesterId;
    }

    public function build()
    {
        $subject = 'Exportación de auditoría lista: ' . $this->labelFor($this->dataset);

        $requester = $this->requesterId ? User::find($this->requesterId) : null;

        return $this->subject($subject)
            ->view('mail.audit_export_ready')
            ->with([
                'datasetLabel' => $this->labelFor($this->dataset),
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
                'requester' => $requester,
                'filename' => $this->filename,
            ])
            ->attach($this->fullPath, [
                'as' => $this->filename,
                'mime' => 'text/csv',
            ]);
    }

    private function labelFor(string $key): string
    {
        return [
            'audit-logs' => 'Audit Logs',
            'control-errors' => 'Control Errors',
            'control-sesion' => 'Control Sesión',
            'evento-home-totem' => 'Eventos Home Totem',
            'grabacion-tarjeta' => 'Grabación Tarjeta',
            'respuesta-pago' => 'Respuesta Pago',
            'pago-totem' => 'Pago Totem',
        ][$key] ?? $key;
    }
}
