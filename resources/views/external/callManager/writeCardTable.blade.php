<table id="write_card_table" class="table table-bordered table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Fecha</th>            
            <th>Detalles</th>         
            <th style="width:80px">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tarjetas_sesion as $tarjeta)       
            <tr>
                <td style="line-height:15px">                    
                    Entrada:<br /> <b>{{$tarjeta->date_in}} {{$tarjeta->time_in->format('H:i') }}</b><br /><br />
                    Salida:<br /> <b>{{$tarjeta->date_out}} {{$tarjeta->time_out->format('H:i') }}</b> <br />
                </td>
                <td>
                    Habitación 1 : {{$tarjeta->room_no}} <br />
                    Habitación 2 : {{$tarjeta->room_no2}} <br />
                    Habitación 3 : {{$tarjeta->room_no3}} <br /> 
                    Uid Card : {{$tarjeta->uid}}<br/> 
                    Creado :<br />  {{$tarjeta->created_at}}
                </td>
                <td style="color: black;" class="text-center pt-3">
                    <b>{{$tarjeta->status}}</b>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>