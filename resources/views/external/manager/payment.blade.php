
@if(isset($emisors))




        <form id="pagoForm">
            @csrf
            <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
            <input type="hidden" name="estado" id="estado" value="Pendiente"></input>
            <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}">
            <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}">
            <input type="hidden" name="tipo_operacion" value="PAGO">
            <div class="row">
                <div class="form-group col-md-5">
                    <label class="required" for="importe">{{ trans('cruds.pagoTotem.fields.importe') }}</label>
                    <input class="form-control {{ $errors->has('importe') ? 'is-invalid' : '' }}" type="number" name="importe" id="importe" value="{{ old('importe', '') }}" step="0.01" required>
                    @if($errors->has('importe'))
                        <div class="invalid-feedback">
                            {{ $errors->first('importe') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.pagoTotem.fields.importe_helper') }}</span>
                </div>
                <div class="form-group  col-md-5">
                    <label class="required" for="factura">{{ trans('cruds.pagoTotem.fields.factura') }}</label>
                    <input class="form-control {{ $errors->has('factura') ? 'is-invalid' : '' }}" type="text" name="factura" id="factura" value="{{ old('factura', '') }}" required>
                    @if($errors->has('factura'))
                        <div class="invalid-feedback">
                            {{ $errors->first('factura') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.pagoTotem.fields.factura_helper') }}</span>
                </div>
                <div class="form-group col-md-2 pt-4">
                    <button class="btn btn-danger" type="submit">
                        Solicitar Pago
                    </button>
                </div>
            </div>
        </form>


@endif
<hr>
<div style="margin-top: 20px">
    <div class="alert alert-success" id="success-alert-push-pago" style="display: none">
        <strong>Bien Hecho! </strong> Solicitud de pago lanzada correctamente.
    </div>

    <div class="alert alert-danger" id="error-alert-push-pago" style="display: none">
        <strong>Ups! </strong> Ha ocurrido un error al lanzar la solicitud de pago.
    </div>
</div>
<br><br>
<h5 id="respuestaPagoPush" class="respuestaPagoPush" style="font-weight: bold;display: none;color: darkgreen"></h5>
<div class="responseCreatePayment">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style="color: black;border: 1px solid black" scope="col">Emisor</th>
                <th style="color: black;border: 1px solid black" scope="col">Importe</th>
                <th style="color: black;border: 1px solid black" scope="col">Factura</th>
                <th style="color: black;border: 1px solid black" scope="col">Creado</th>
                <th style="color: black;border: 1px solid black" scope="col">Estado</th>
            </tr>
        </thead>

        @if(isset($pagos_sesion))
            <tbody class="cuerpoRespuestaPago">

            @foreach($pagos_sesion as $pago)
                @if($pago->estado == 'Autorizada')
                    @php($color_fondo = 'green')
                @elseif($pago->estado == 'Pendiente')
                    @php($color_fondo = '')
                @else
                    @php($color_fondo = 'red')
                @endif
                <tr>
                        <th style="color: black;background: {{$color_fondo}}">{{$pago->emisor->name}}</th>
                        <th style="color: black;background: {{$color_fondo}}">{{$pago->importe}}</th>
                        <th style="color: black;background: {{$color_fondo}}">{{$pago->factura}}</th>
                        <th style="color: black;background: {{$color_fondo}}">{{$pago->created_at}}</th>
                        <th style="color: black;background: {{$color_fondo}}">{{$pago->estado}}</th>
                </tr>
            @endforeach

            </tbody>
        @else
            <tbody class="cuerpoRespuestaPago">

            </tbody>
        @endif

    </table>

</div>


<script type="text/javascript">
    $(document).ready(function(){

        $('#pagoForm').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route("external.pago-totems.store") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){

                    if(response['success']){
                        $("#success-alert-push-pago").show();
                        $("#success-alert-push-pago").fadeTo(2000, 500).slideUp(500, function() {
                            $("#success-alert-push-pago").slideUp(500);
                        });


                        setTimeout(function(){
                            window.location = window.location.href.split("?")[0] + "?step=" + 4;;
                        }, 2000);





                    }
                    if(response['error']){
                        $("#error-alert-push-pago").show();
                        $("#error-alert-push-pago").fadeTo(2000, 500).slideUp(500, function() {
                            $("#error-alert-push-pago").slideUp(500);
                        });
                        alert(response['error']['errorInfo'])
                    }







                },
                error: function(response) {

                },
            });
        });
    });
</script>
