
@include('external.manager.captureClient')
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<div class="card" style="border: none; background: none;margin-bottom: 20px;box-shadow: none">
    <form id="fotoAnverso">
        @csrf
        <div class="row">
            <div class="form-group" style="display: none">
                <label class="required" for="receptor_id">{{ trans('cruds.eventoHomeTotem.fields.receptor') }}</label>
                <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}"></input>
                <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}"></input>
                <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
                @if($errors->has('receptor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receptor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.receptor_helper') }}</span>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <input type="hidden" name="tipo_evento_id" value="3">
                    @if($errors->has('tipo_evento'))
                        <div class="invalid-feedback">
                            {{ $errors->first('tipo_evento') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.tipo_evento_helper') }}</span>
                </div>
                <div class="form-group" style="display: none">
                    <label>{{ trans('cruds.eventoHomeTotem.fields.canal_transmision') }}</label>
                    <select class="form-control select2 {{ $errors->has('canal_transmision') ? 'is-invalid' : '' }}" name="canal_transmision" id="canal_transmision">
                        @foreach(App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('canal_transmision', '') === (string) 'Home Inferior' ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('canal_transmision'))
                        <div class="invalid-feedback">
                            {{ $errors->first('canal_transmision') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.canal_transmision_helper') }}</span>
                </div>



            </div>
            <div class="col-md-12" style="display: none">


                <div class="form-group">
                    <label for="objeto">{{ trans('cruds.eventoHomeTotem.fields.objeto') }}</label>
                    <textarea class="form-control {{ $errors->has('objeto') ? 'is-invalid' : '' }}" name="objeto" id="objeto">{{ old('objeto') }}</textarea>
                    @if($errors->has('objeto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('objeto') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.objeto_helper') }}</span>
                </div>

            </div>

        </div>
        <button class="btn btn-danger" style="float:left;margin-top: 5px" type="submit">
            Solicitar Parte delantera
        </button>
    </form>

    <form id="fotoReverso">
        @csrf
        <div class="row">
            <div class="form-group" style="display: none">
                <label class="required" for="receptor_id">{{ trans('cruds.eventoHomeTotem.fields.receptor') }}</label>
                <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}"></input>
                <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}"></input>
                <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
                @if($errors->has('receptor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receptor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.receptor_helper') }}</span>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <input type="hidden" name="tipo_evento_id" value="6">
                    @if($errors->has('tipo_evento'))
                        <div class="invalid-feedback">
                            {{ $errors->first('tipo_evento') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.tipo_evento_helper') }}</span>
                </div>
                <div class="form-group" style="display: none">
                    <label>{{ trans('cruds.eventoHomeTotem.fields.canal_transmision') }}</label>
                    <select class="form-control select2 {{ $errors->has('canal_transmision') ? 'is-invalid' : '' }}" name="canal_transmision" id="canal_transmision">
                        @foreach(App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('canal_transmision', '') === (string) 'Home Inferior' ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('canal_transmision'))
                        <div class="invalid-feedback">
                            {{ $errors->first('canal_transmision') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.canal_transmision_helper') }}</span>
                </div>


            </div>
            <div class="col-md-12" style="display: none">


                <div class="form-group">
                    <label for="objeto">{{ trans('cruds.eventoHomeTotem.fields.objeto') }}</label>
                    <textarea class="form-control {{ $errors->has('objeto') ? 'is-invalid' : '' }}" name="objeto" id="objeto">{{ old('objeto') }}</textarea>
                    @if($errors->has('objeto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('objeto') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.objeto_helper') }}</span>
                </div>

            </div>

        </div>
        <button class="btn btn-warning" style="float:left;margin-top: 10px;" type="submit">
            Solicitar Parte trasera
        </button>

    </form>
</div>

<form id="refreshDivCaptureForm">
    <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}"></input>
    <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}"></input>
    <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
    <button type="submit" class="btn btn-success" style="float: right;display: none";>Actualizar contenido</button>
</form>

<div style="margin-top: 20px">
    <div class="alert alert-success" id="success-alert-push-documento" style="display: none">
        <strong>Bien Hecho! </strong> Evento lanzado correctamente.
    </div>

    <div class="alert alert-danger" id="error-alert-push-documento" style="display: none">
        <strong>Ups! </strong> Ha ocurrido un error.
    </div>
</div>
<br><br>
<div class="respuestaAnversoPush">

    @if(isset($documento_anverso))
        <h5>Parte delantera del documento</h5>
        <br>
        <img src="{{$documento_anverso->respuesta_texto}}" width="100%">
        <br><br><hr><br>
    @endif

</div>
<br><br>
<div class="respuestaReversoPush" id="">
    @if(isset($documento_reverso))
        <h5>Parte trasera del documento</h5>
        <br>
        <img src="{{$documento_reverso->respuesta_texto}}" width="100%">
    @endif

</div>


<script type="text/javascript">
    $(document).ready(function(){

        $('#refreshDivCaptureForm').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route("external.evento-home-totems.updateContentDocument") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){


                    if(response['anverso']){
                        $(".respuestaAnversoPush").append(response['anverso']);


                        $(".respuestaAnversoPush").empty();
                        $(".respuestaAnversoPush").append('<br><h5>Parte delantera del documento</h5><br>');
                        $(".respuestaAnversoPush").append('<img src="'+ response['anverso'] +'" width="100%">');
                        $(".respuestaAnversoPush").append("<br><br><hr><br>")
                    }
                    if(response['reverso']){

                        $(".respuestaReversoPush").empty();
                        $(".respuestaAnversoPush").append('<br><h5>Parte trasera del documento</h5>');
                        $(".respuestaReversoPush").append('<img src="'+ response['reverso'] +'" width="100%">');

                    }
                },
                error: function(response) {
                },
            });
        });

        $('#fotoAnverso').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route("external.evento-home-totems.store") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){

                    if(response['success']){
                        $("#success-alert-push-documento").show();
                        $("#success-alert-push-documento").fadeTo(2000, 500).slideUp(500, function() {
                            $("#success-alert-push-documento").slideUp(500);
                        });

                        $(".respuestaAnversoPush").empty();
                        $(".respuestaAnversoPush").append(response['success']);

                        console.log(response)


                    }
                    if(response['error']){
                        $("#error-alert-push-documento").show();
                        $("#error-alert-push-documento").fadeTo(2000, 500).slideUp(500, function() {
                            $("#error-alert-push-documento").slideUp(500);
                        });
                        alert(response['error']['errorInfo'])
                    }







                },
                error: function(response) {
                    $('#successMsgCamera').hide();
                    $('#ErrorMsgCamera').text(response['error']);
                    $('#ErrorMsgCamera').show();
                },
            });
        });



        $('#fotoReverso').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route("external.evento-home-totems.store") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){

                    if(response['success']){
                        $("#success-alert-push-documento").show();
                        $("#success-alert-push-documento").fadeTo(2000, 500).slideUp(500, function() {
                            $("#success-alert-push").slideUp(500);
                        });


                        $(".respuestaReversoPush").empty();
                        $(".respuestaReversoPush").append(response['success']);
                        console.log(response)
                    }
                    if(response['error']){
                        $("#error-alert-push-documento").show();
                        $("#error-alert-push-documento").fadeTo(2000, 500).slideUp(500, function() {
                            $("#error-alert-push").slideUp(500);
                        });
                        alert(response['error']['errorInfo'])
                    }







                },
                error: function(response) {
                    $('#successMsgCamera').hide();
                    $('#ErrorMsgCamera').text(response['error']);
                    $('#ErrorMsgCamera').show();
                },
            });
        });
    });
</script>


