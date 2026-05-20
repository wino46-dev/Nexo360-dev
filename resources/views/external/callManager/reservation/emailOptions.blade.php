@php
    use App\Models\PagoTotem;

    $emails = [];

    foreach ($checkIns as $checkIn) {
        $emails[] = $checkIn['email'];
    }

    $emails_address = count($emails) > 0 ? implode(',', $emails) : '';

    $pago = PagoTotem::where('folio_id', $reservation->folio_id ?? null)
        ->where('estado', 'Autorizada')
        ->where('origen', 'totem')
        ->first();

    $check_recibo = $pago ? '' : 'disabled';

    // Usamos el campo que realmente existe (id o reservation_id)
    $reservationId = $reservation->reservation_id ?? $reservation->id ?? null;

    // Mensaje de error si no hay ID válido
    $reservationError = null;
    if (!$reservationId) {
        $reservationError = 'Error: El ID de la reserva no está disponible.';
    }
@endphp

<div class="card mb-0">
    <div class="card-header py-1 px-2">
        Envío Parte de Viajero
    </div>
    <div class="card-body py-1 px-2">
        <div class="row align-items-end">
            <div class="col-md-5">
                <label for="email_emails" class="form-label mb-1">Email(s)</label>
                <input type="text" class="form-control form-control-sm" id="email_emails" value="{{ $emails_address }}" />
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="email_check_parte" checked />
                    <label class="form-check-label" for="email_check_parte">Parte Viajero</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="email_check_recibo_pago" {{ $check_recibo }} />
                    <label class="form-check-label" for="email_check_recibo_pago">Recibo Pago</label>
                </div>
            </div>
            <div class="col-md-3">
                <button
                    class="btn btn-{{ $reservationId ? 'success' : 'secondary' }} btn-sm w-100 d-flex align-items-center justify-content-center"
                    id="btn_email_send"
                    {{ $reservationId ? '' : 'disabled' }}
                    onclick="{{ $reservationId ? "parte_email_send($reservationId)" : '' }}">
                    <span id="email_send_text">Enviar Email</span>
                    <span id="email_send_spinner" class="spinner-border spinner-border-sm ms-2" role="status" style="display: none;"></span>
                </button>

                @if ($reservationError)
                    <span class="text-danger d-block mt-1">{{ $reservationError }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function validateEmails(input) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const emails = input.split(',').map(email => email.trim());
        return emails.every(email => emailRegex.test(email));
    }

    function parte_email_send(reservation_id) {
        const emailVal = $('#email_emails').val().trim();
        const parteViajeroChecked = $('#email_check_parte').is(':checked');
        const reciboPagoChecked = $('#email_check_recibo_pago').is(':checked');

        if (!emailVal) {
            Toast.fire({ icon: "error", title: 'El campo de correo está vacío' });
            return;
        }

        if (!validateEmails(emailVal)) {
            Toast.fire({ icon: "error", title: 'Uno o más correos no son válidos' });
            return;
        }

        if (!reservation_id || isNaN(reservation_id)) {
            Toast.fire({ icon: "error", title: 'El ID de la reserva no es válido' });
            return;
        }

        // Mostrar spinner y desactivar botón
        $('#btn_email_send').prop('disabled', true);
        $('#email_send_text').text('Enviando...');
        $('#email_send_spinner').show();

        const data = {
            reservation_id: reservation_id,
            email: emailVal,
            parte_viajero: parteViajeroChecked ? 'true' : 'false',
            recibo_pago: reciboPagoChecked ? 'true' : 'false',
        };

        $.ajax({
            url: '{{ url('external/call-manager/parte-send-mail') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            dataType: 'json',
            data: data,
        }).done(function(response) {
            Toast.fire({ icon: "success", title: 'Email enviado correctamente' });
        }).fail(function(error) {
            let msg = 'Error al enviar el parte viajero';
            if (error.responseJSON?.errors) {
                msg = error.responseJSON.errors.join(', ');
            } else if (error.responseJSON?.message) {
                msg = error.responseJSON.message;
            }
            Toast.fire({ icon: "error", title: msg });
        }).always(function() {
            $('#btn_email_send').prop('disabled', false);
            $('#email_send_text').text('Enviar Email');
            $('#email_send_spinner').hide();
        });
    }
</script>
