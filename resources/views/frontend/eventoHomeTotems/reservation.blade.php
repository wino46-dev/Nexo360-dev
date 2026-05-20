<!--  pdf parte viajero, call y totem -->
<table class="table table-bordered">  
  <tbody>
    <tr>
      <td ondblclick="reservation_resumen_test()">
        Reserva:<br />
        <b>{{ $reservation['name'] }}</b>
      </td>
      <td>
        Check In:<br />
        <b>
          <?php                       
            echo date("d/m/Y", strtotime($reservation['checkin']));
          ?>
        </b>
      </td>
      <td>
        Check Out:<br />
        <b>
          <?php                       
            echo date("d/m/Y", strtotime($reservation['checkout']));
          ?>
        </b>
      </td>
      <td nowrap>Total:<br />
        <b>{{ $reservation['price_total'] }} €</b>
      </td>
    </tr>
  </tbody>
</table>
@if ($emailOptions)
  @include('admin.callManager.reservation.emailOptions')
@endif
<br />
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    @foreach($checkIns as $key => $checkIn)
        <a class="nav-item nav-link {{ $key == 0 ? 'active' : '' }}" id="nav-home-tab" data-toggle="tab" href="#nav-{{ $key }}" role="tab" aria-controls="nav-home" aria-selected="true">
            {{ $checkIn['firstname'] }} {{ $checkIn['lastname'] }}
        </a>
    @endforeach    
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  @foreach($checkIns as $key => $checkIn)
  
    <div class="tab-pane fade {{ $key == 0 ? 'show' : '' }} {{ $key == 0 ? 'active' : '' }}" id="nav-{{ $key }}" role="tabpanel" aria-labelledby="nav-home-tab">
      <object data="{{ url('parte-viajero/parte_viajero_' . $checkIn['id'] . '.pdf') }}#toolbar=0" type="application/pdf" width="100%" height="700px">
        <param name="src" value="{{ url('parte-viajero/parte_viajero_' . $checkIn['id'] . '.pdf') }}">
        <param name="toolbar" value="0">    
        <param name="type" value="application/pdf">    
        <p>El navegador no puede mostrar este PDF. Puedes <a href="{{ url('parte-viajero/parte_viajero_' . $checkIn['id'] . '.pdf') }}">descargarlo aquí</a>.</p>
      </object>
    </div>
  @endforeach    
  
</div>

