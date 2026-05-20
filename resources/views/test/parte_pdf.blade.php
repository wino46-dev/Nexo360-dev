@foreach($checkIns as $key => $checkIn)    
      <object data="{{ url('parte-viajero/parte_viajero_' . $checkIn['checkin_id'] . '.pdf') }}" type="application/pdf" width="100%" height="100%">
        <param name="src" value="{{ url('parte-viajero/parte_viajero_' . $checkIn['checkin_id'] . '.pdf') }}">
        <param name="toolbar" value="0">    
        <param name="type" value="application/pdf">    
        <p>El navegador no puede mostrar este PDF. Puedes <a href="{{ url('parte-viajero/parte_viajero_' . $checkIn['checkin_id'] . '.pdf') }}">descargarlo aquí</a>.</p>
      </object>
@endforeach 