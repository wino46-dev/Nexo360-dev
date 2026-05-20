<div class="row">
    <div class="col-md-3">
        Reserva: {{ $folio['id'] }}</b>
        <br /><b>{{ $folio['name'] }}</b>
    </div>
    <div class="col-md-3">
        Estado: <br />
        @if ($folio['state'] == 'cancel')
            <span class="badge badge-danger">{{ $folio['state'] }}</span>
        @else
            <span class="badge badge-success">{{ $folio['state'] }}</span>
        @endif

    </div>
    <div class="col-md-3">
        Ingreso:
        <br /><b>{{ $folio['firstCheckin2'] }}</b>
    </div>
    <div class="col-md-3">
        Salida:
        <br /><b>{{ $folio['lastCheckout2'] }}</b>
    </div>
</div>
<hr class="my-1" />
<div class="row">

    <div class="col-md-8">
        <b>{{ $folio['partnerName'] }}</b>
        <div class="font-weight-light">
            <i class="fa fa-envelope-o mr-1" aria-hidden="true"></i> <span
                id="folio_detail_email">{{ $folio['partnerEmail'] }}</span>
        </div>
        <div class="font-weight-light">
            <i class="fa fa-phone mr-1" aria-hidden="true"></i> <span
                id="folio_detail_mobile">{{ $folio['partnerPhone'] }}</span>
        </div>
    </div>
    <div class="col-md-2 text-right text-nowrap">
        Total:
        <br /><b>{{ $folio['amountTotal'] }} €</b>
    </div>
    <div class="col-md-2 text-right text-nowrap folio_saldo_pendiente">
        Pend. :
        <br /><b>{{ $folio['pendingAmount'] }} €</b>
    </div>
</div>
<hr class="my-1" />
<div class="row">
    <div class="col-md-12">
        <table id="reservation_detail_table" class="table table-bordered table-striped table-hover"
            style="font-size:90%">
            <thead>
                <tr>
                    <th style="width:100px" class="text-center">Reserva </th>
                    <th class="text-nowrap">Fechas</th>
                    <th class="text-center text-nowrap">Noches</th>
                    <th class="text-center text-nowrap">Adultos</th>
                    <th class="text-center text-nowrap">Niños</th>
                    <th class="text-center text-nowrap">Habitación</th>
                    <th class="text-center text-nowrap">Servicios</th>
                    <th class="text-nowrap">Precio</th>
                    @if (isset($origen) && $origen == 'folio_selected')
                        <th class="text-nowrap"> </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($folio['reservations'] as $reservation)
                    <tr>
                        <td class="text-center">
                            {{ $reservation['name'] }}
                            <div> <span class="badge badge-secondary">{{ $reservation['stateCode'] }}</span></div>
                        </td>
                        <td class="text-center">
                            {{ $reservation['checkin2'] }}<br />
                            {{ $reservation['checkout2'] }}
                        </td>
                        <td class="text-center">{{ $reservation['nights'] }}</td>
                        <td class="text-center">{{ $reservation['adults'] }}</td>
                        <td class="text-center">{{ $reservation['children'] }}</td>
                        <td class="text-center">{{ $reservation['roomName'] }}</td>
                        <td class="text-center">{{ $reservation['numServices'] }}</td>
                        <td class="text-right">{{ $reservation['priceTotal'] }} €</td>
                        @if (isset($origen) && $origen == 'folio_selected')
                            <td class="text-nowrap text-center" style="width:130px">
                                <button class="btn btn-success btn-sm"
                                    data-reservation-id="{{ $reservation['id'] }}"
                                    data-checkin="{{ $reservation['checkin3'] }}"
                                    data-checkout="{{ $reservation['checkout3'] }}"
                                    onclick="reservation_select(this)">
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i> Check In
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Sección fija de costes de la reserva (visible sólo si hay servicios) --}}
        @php
            // Detectar si alguna reserva tiene servicios reales (no sólo el número)
            $reservationsWithServices = collect($folio['reservations'] ?? [])->filter(function($r){
                $n = (int)($r['numServices'] ?? 0);
                $hasArrays = !empty($r['services'] ?? []) || !empty($r['boardServices'] ?? []);
                return $n > 0 || $hasArrays;
            })->values();
            $firstWithServices = $reservationsWithServices->first();
        @endphp
        <div id="fixed_costs_section" class="mt-3" style="display: none;">
            <div class="d-flex align-items-center mb-2">
                <h5 class="mb-0 mr-2">Costes de la reserva</h5>
                <small id="fixed_costs_caption" class="text-muted"></small>
            </div>
            <div id="fixed_costs_container" class="border rounded p-2">
                <div class="py-2 text-center">Cargando...</div>
            </div>
        </div>
        <script>
            (function(){
                try{
                    var firstId = {!! json_encode($firstWithServices['id'] ?? null) !!};
                    var hasAny = {!! json_encode(!empty($firstWithServices)) !!};
                    if(hasAny && firstId){
                        $('#fixed_costs_section').show();
                        load_fixed_costs(firstId);
                    }
                }catch(e){ /* no-op */ }

                window.load_fixed_costs = function(reservation_id){
                    var $c = $('#fixed_costs_container');
                    $c.html('<div class="py-2 text-center">'+ (window.loading1 || 'Cargando...') +'</div>');
                    $.ajax({
                        url: "{{ url((request()->is('external*')?'external':'external').'/reservation-api/reservation-costs') }}/" + reservation_id + '?embed=1',
                        method: 'GET',
                        cache: false
                    }).done(function(html){
                        $c.html(html);
                        $('#fixed_costs_caption').text('Reserva ID: '+reservation_id);
                    }).fail(function(xhr){
                        var msg = 'No se pudo cargar el desglose de costes';
                        try{ var r = JSON.parse(xhr.responseText); if(r.error){ msg = r.error; } }catch(e){}
                        $c.html('<div class="text-danger">'+msg+'</div>');
                    });
                };
            })();
        </script>
    </div>
</div>
