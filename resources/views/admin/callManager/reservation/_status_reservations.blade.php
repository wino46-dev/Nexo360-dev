@php
    $error_message = $error_message ?? null;
@endphp

{{-- Bloque de error, destinado a ser extraído y mostrado fuera del contenedor principal --}}
<div id="pms_error_block">
@if($error_message)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-warning w-100">
                <div class="card-body py-3">
                    <h5 class="card-title text-warning mb-2">Problema al cargar datos del PMS</h5>
                    <div class="card-text">{!! nl2br(e($error_message)) !!}</div>
                    <div class="mt-2"><small class="text-muted">Si el problema persiste, revisa en la configuración del hotel: URL del PMS, usuario/contraseña o token de la API.</small></div>
                </div>
            </div>
        </div>
    </div>
@endif
</div>

{{-- Bloque de tarjetas de estado a insertar dentro del contenedor de estado --}}
<div id="folio_status_cards">
<div class="row ">
    <div class="col-6">
        <div class="card border-success mb-0" style="cursor: pointer;" onclick="folio_search('checkin')">
            <div class="card-body px-3 py-1">
                <b class="h4 text-success">Check-Ins</b><br />
                <div class="h5 mb-1">
                    {{ $reservations_checkin_status['dummy'] ?? 0 }}
                    por Llegar</div>
                <div> {{ $reservations_checkin_status['onboard'] ?? 0 }} completados</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-danger mb-0" style="cursor: pointer;" onclick="folio_search('checkout')">
            <div class="card-body  px-3 py-1">
                <b class="h4 text-danger">Check-Outs</b><br />
                <div class="h5 mb-1"> {{ $reservations_checkout_status['onboard'] ?? 0 }} pendientes</div>
                <div> {{ $reservations_checkout_status['done'] ?? 0 }} completados</div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function checkout_pending() {

    }
</script>
