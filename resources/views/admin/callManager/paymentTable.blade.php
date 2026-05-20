<table id="payment_table" class="table table-bordered table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Pago</th>            
            <th style="width:100px">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pagos_sesion as $pago)
            @if($pago->estado == 'Autorizada')
                @php($color_fondo = '#2eb85c')
            @elseif($pago->estado == 'Pendiente')
                @php($color_fondo = '')
            @else
                @php($color_fondo = '#ff9e81')
            @endif
            <tr>
                <td style="color: black;background: {{$color_fondo}}; line-height:14px">                    
                    Importe: <b>{{$pago->importe}}</b><br />
                    Factura: <b>{{$pago->factura}}</b> <br />
                    Fecha: {{$pago->created_at}}<br />
                    Origen: {{ strtoupper($pago->origen) }}<br />
                    @if($pago->origen == 'manual')
                        Notas: {{$pago->notas}}
                    @endif

                </td>
                <td style="color: black;background: {{$color_fondo}}" class="text-center pt-3">
                    <b>{{$pago->estado}}</b>
                    @if($pago->origen == 'manual')
                        <!-- 
                            <button class="btn btn-sm btn-danger px-1 py-0" style="font-size:11px; min-width:76px" id="payment_btn_delete_{{ $pago->id }}" onclick="payment_btn_delete({{ $pago->id }})">Eliminar Pago</button>
                        -->
                    @endif

                </td>
            </tr>
        @endforeach

    </tbody>
</table>