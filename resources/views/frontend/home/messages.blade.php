<div class="row justify-content-center">
    <div class="col-md-12">     
        <!-- Mensajes -->           
        @if(!isset($grabador->id))
            <div class="card" style="padding: 20px;color: red;margin-bottom: 20px">
                <h4><b>ATENCIÓN: </b> No se ha podido encontrar la configuración de Grabación de tarjetas para este Totem</h4>
            </div>
        @endif

        @if(!isset($tpv->id))
            <div class="card" style="padding: 20px;color: red;margin-bottom: 20px">
                <h4><b>ATENCIÓN: </b> No se ha podido encontrar la configuración de Redsys para este Totem</h4>
            </div>
        @endif       
    </div>
</div>