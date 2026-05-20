@php
    $r = $reservation ?? [];
    $reservationLines = $r['reservationLines'] ?? [];
    $services = $r['services'] ?? [];
    $boardServices = $r['boardServices'] ?? [];
    $tot_room = $r['priceOnlyRoom'] ?? null;
    $tot_services = $r['priceOnlyServices'] ?? null;
    $tot_total = $r['priceTotal'] ?? null;
    $tot_tax = $r['priceTax'] ?? null;
    $tot_discount = $r['discount'] ?? null;
    $services_discount = $r['servicesDiscount'] ?? null;
@endphp

<div class="mb-2">
    <div><b>Reserva:</b> {{ $r['name'] ?? '-' }} (ID: {{ $r['id'] ?? '-' }})</div>
    <div class="text-muted" style="font-size:90%">
        Check-in: {{ $r['checkin'] ?? '-' }} | Check-out: {{ $r['checkout'] ?? '-' }} | Noches: {{ $r['nights'] ?? '-' }}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h6 class="mb-2">Desglose habitación</h6>
        <table class="table table-sm table-bordered costs-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th class="text-right">Precio</th>
                    <th class="text-right">Dto.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservationLines as $line)
                    <tr>
                        <td>{{ $line['date'] ?? '' }}</td>
                        <td class="text-right">{{ number_format((float)($line['price'] ?? 0), 2) }} €</td>
                        <td class="text-right">{{ number_format((float)($line['discount'] ?? 0), 2) }} €</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted">Sin líneas de habitación</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>Total habitación</th>
                    <th class="text-right" colspan="2">{{ isset($tot_room) ? number_format((float)$tot_room, 2) . ' €' : '-' }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="mb-2">Servicios</h6>
        <table class="table table-sm table-bordered costs-table">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Impuestos</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $allServices = array_merge($boardServices, $services); @endphp
                @forelse($allServices as $svc)
                    <tr>
                        <td>
                            {{ $svc['name'] ?? 'Servicio' }}
                            @if(!empty($svc['quantity'])) <span class="text-muted">x{{ $svc['quantity'] }}</span> @endif
                        </td>
                        <td class="text-right">{{ number_format((float)($svc['priceSubtotal'] ?? 0), 2) }} €</td>
                        <td class="text-right">{{ number_format((float)($svc['priceTaxes'] ?? 0), 2) }} €</td>
                        <td class="text-right">{{ number_format((float)($svc['priceTotal'] ?? 0), 2) }} €</td>
                    </tr>
                    @if(!empty($svc['serviceLines']))
                        <tr>
                            <td colspan="4">
                                <div class="small text-muted">Líneas:</div>
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th class="text-right">Precio</th>
                                            <th class="text-right">Dto.</th>
                                            <th class="text-right">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(($svc['serviceLines'] ?? []) as $sl)
                                            <tr>
                                                <td>{{ $sl['date'] ?? '' }}</td>
                                                <td class="text-right">{{ number_format((float)($sl['priceUnit'] ?? 0), 2) }} €</td>
                                                <td class="text-right">{{ number_format((float)($sl['discount'] ?? 0), 2) }} €</td>
                                                <td class="text-right">{{ $sl['quantity'] ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="4" class="text-muted">Sin servicios</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>Total servicios</th>
                    <th class="text-right" colspan="3">{{ isset($tot_services) ? number_format((float)$tot_services, 2) . ' €' : '-' }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h6 class="mb-2">Totales</h6>
        <table class="table table-sm table-bordered costs-table mb-0">
            <tbody>
                <tr>
                    <th style="width:200px">Descuento reservas</th>
                    <td class="text-right">{{ isset($tot_discount) ? number_format((float)$tot_discount, 2) . ' €' : '-' }}</td>
                </tr>
                <tr>
                    <th>Descuento servicios</th>
                    <td class="text-right">{{ isset($services_discount) ? number_format((float)$services_discount, 2) . ' €' : '-' }}</td>
                </tr>
                <tr>
                    <th>Impuestos</th>
                    <td class="text-right">{{ isset($tot_tax) ? number_format((float)$tot_tax, 2) . ' €' : '-' }}</td>
                </tr>
                <tr>
                    <th>Total general</th>
                    <td class="text-right"><b>{{ isset($tot_total) ? number_format((float)$tot_total, 2) . ' €' : '-' }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
