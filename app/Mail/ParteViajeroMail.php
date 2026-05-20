<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PagoTotem;
use App\Services\PagoService;
use Illuminate\Mail\Mailables\Address;

use Illuminate\Mail\Mailables\Attachment;

class ParteViajeroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $checkins;
    public $parte_viajero_mail;
    public $recibo_pago_mail;

    public $establecimiento_datos;

    public function __construct($reservation, $establecimiento_datos, $checkins, $parte_viajero_mail, $recibo_pago_mail)
    {
        $this->reservation = $reservation;
        $this->establecimiento_datos = $establecimiento_datos;
        $this->checkins = $checkins;
        $this->parte_viajero_mail = $parte_viajero_mail;
        $this->recibo_pago_mail = $recibo_pago_mail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $nombreReserva = $this->reservation->name ?? null;
        $nombreEstablecimiento = $this->establecimiento_datos->nombre ?? null;
        $prefix = __('parte.mail.subject_prefix');
        return new Envelope(
            from: new Address(config('mail.from.address'), ($nombreEstablecimiento ? ' - ' . $nombreEstablecimiento : '')),
            subject: $prefix . ($nombreReserva ? ' - ' . $nombreReserva : '') . ' - ' . $nombreEstablecimiento,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.parte_viajero',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $adjuntos = [];
        if ($this->parte_viajero_mail) {
            foreach ($this->checkins as $checkin) {
                $adjuntos[] = Attachment::fromPath(public_path() . '/parte-viajero/parte_viajero_' . $checkin->id . '.pdf');
            }
        }
        if ($this->recibo_pago_mail) {
            $folio_id = $this->reservation->folio_id;

            $pagos = PagoTotem::where('folio_id', $folio_id)
                ->where('estado', 'Autorizada')
                ->where('origen', 'totem')
                ->get();
            foreach($pagos as $pago){
                $pago_pdf_name = PagoService::pdfGenerate($pago->id);
                $adjuntos[] = Attachment::fromPath(public_path() . $pago_pdf_name);
            }

        }

        return $adjuntos;
    }
}
