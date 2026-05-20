
<form id="mensajeInicial">
    @csrf
    <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
    <div class="form-group" style="display: none">
        <input type="hidden" name="step" id="step" value="1">
        <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}">
        @if($errors->has('emisor'))
            <div class="invalid-feedback">
                {{ $errors->first('emisor') }}
            </div>
        @endif
        <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.emisor_helper') }}</span>
    </div>
    <div class="form-group" style="display: none">
        <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}">
        @if($errors->has('receptor'))
            <div class="invalid-feedback">
                {{ $errors->first('receptor') }}
            </div>
        @endif
        <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.receptor_helper') }}</span>
    </div>
    <div class="row">
        <div class="col-12 text-right">
            <button style="float: right" class="btn btn-info" type="submit">
                Notificar Colocación de tarjeta
            </button>
        </div>
    </div>
</form>
    <form id="grabarTarjetaForm">
        @csrf
        <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
        <input type="hidden" name="status" id="status" value="Pendiente"></input>
        <div class="form-group">
            <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}">
            @if($errors->has('emisor'))
                <div class="invalid-feedback">
                    {{ $errors->first('emisor') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.emisor_helper') }}</span>
        </div>
        <div class="form-group">
            <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}">
            @if($errors->has('receptor'))
                <div class="invalid-feedback">
                    {{ $errors->first('receptor') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.receptor_helper') }}</span>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="required" for="date_in">{{ trans('cruds.grabacionTarjetum.fields.date_in') }}</label>
                <input class="form-control date  {{ $errors->has('date_in') ? 'is-invalid' : '' }}" type="text" name="date_in" id="date_in" value="{{date("d-m-Y")}}" required>

                @if($errors->has('date_in'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_in') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.date_in_helper') }}</span>
            </div>
            <div class="form-group col-md-6">
                <label class="required" for="time_in">{{ trans('cruds.grabacionTarjetum.fields.time_in') }}</label>
                <input class="form-control timepicker  {{ $errors->has('time_in') ? 'is-invalid' : '' }}" type="text" name="time_in" id="time_in" value="{{date('H:i:sa')}}" required>
                @if($errors->has('time_in'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_in') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.time_in_helper') }}</span>
            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="required" for="date_out">{{ trans('cruds.grabacionTarjetum.fields.date_out') }}</label>
                <input class="form-control date {{ $errors->has('date_out') ? 'is-invalid' : '' }}" type="text" name="date_out" id="date_out" value="{{ old('date_out') }}" required>
                @if($errors->has('date_out'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_out') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.date_out_helper') }}</span>
            </div>
            <div class="form-group col-md-6">
                <label class="required" for="time_out">{{ trans('cruds.grabacionTarjetum.fields.time_out') }}</label>
                <input class="form-control timepicker {{ $errors->has('time_out') ? 'is-invalid' : '' }}" type="text" name="time_out" id="time_out" value="11:00:00" required>
                @if($errors->has('time_out'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_out') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.time_out_helper') }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label class="required" for="room_no">{{ trans('cruds.grabacionTarjetum.fields.room_no') }}</label>
                <em  style="color: lightslategray;float: right" class="help-block">{{ trans('cruds.grabacionTarjetum.fields.room_no_helper') }}&nbsp;&nbsp;</em>
                <input class="form-control {{ $errors->has('room_no') ? 'is-invalid' : '' }}" type="number" name="room_no" id="room_no" value="{{ old('room_no', '') }}" step="1" required>
                @if($errors->has('room_no'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_no') }}
                    </div>
                @endif

            </div>
            <div class="form-group col-md-3">
                <label for="room_no_2">{{ trans('cruds.grabacionTarjetum.fields.room_no_2') }}</label>
                <em  style="color: lightslategray;float: right" class="help-block">{{ trans('cruds.grabacionTarjetum.fields.room_no_2_helper') }}&nbsp;&nbsp;</em>
                <input class="form-control {{ $errors->has('room_no_2') ? 'is-invalid' : '' }}" type="number" name="room_no_2" id="room_no_2" value="{{ old('room_no_2', '') }}" step="1">
                @if($errors->has('room_no_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_no_2') }}
                    </div>
                @endif
            </div>
            <div class="form-group col-md-3">
                <label for="room_no_3">{{ trans('cruds.grabacionTarjetum.fields.room_no_3') }}</label>
                <em  style="color: lightslategray;float: right" class="help-block">{{ trans('cruds.grabacionTarjetum.fields.room_no_3_helper') }}&nbsp;&nbsp;</em>
                <input class="form-control {{ $errors->has('room_no_3') ? 'is-invalid' : '' }}" type="number" name="room_no_3" id="room_no_3" value="{{ old('room_no_3', '') }}" step="1">
                @if($errors->has('room_no_3'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_no_3') }}
                    </div>
                @endif

            </div>
            <div class="form-group col-md-3">
                <label for="seq_mode">{{ trans('cruds.grabacionTarjetum.fields.seq_mode') }}</label>
                <em  style="color: lightslategray;float: right" class="help-block">{{ trans('cruds.grabacionTarjetum.fields.seq_mode_helper') }}&nbsp;&nbsp;</em>                
                <select class="form-control {{ $errors->has('seq_mode') ? 'is-invalid' : '' }}" name="seq_mode" id="seq_mode" required>
                    <option value="C" >C</option>                    
                </select>

                @if($errors->has('seq_mode'))
                    <div class="invalid-feedback">
                        {{ $errors->first('seq_mode') }}
                    </div>
                @endif

            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    <label class="required" for="safe_box">{{ trans('cruds.grabacionTarjetum.fields.safe_box') }}</label>
                    <em  style="color: lightslategray;float: right" class="help-block">{{ trans('cruds.grabacionTarjetum.fields.safe_box_helper') }}&nbsp;&nbsp;</em>
                    <input class="form-control {{ $errors->has('safe_box') ? 'is-invalid' : '' }}" type="text" name="safe_box" id="safe_box" value="{{ old('safe_box', '0') }}" required>
                    @if($errors->has('safe_box'))
                        <div class="invalid-feedback">
                            {{ $errors->first('safe_box') }}
                        </div>
                    @endif

                </div>
                <input type="hidden" name="card_qty" value="1">

            </div>
            <div class="form-group col-md-6">
                <label for="common_doors">{{ trans('cruds.grabacionTarjetum.fields.common_doors') }}</label>
                <textarea class="form-control {{ $errors->has('common_doors') ? 'is-invalid' : '' }}" name="common_doors" id="common_doors">{{ old('common_doors') }}</textarea>
                @if($errors->has('common_doors'))
                    <div class="invalid-feedback">
                        {{ $errors->first('common_doors') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.common_doors_helper') }}</span>
            </div>
            <input type="hidden" name="uid_card" id="uid_card" value="Undefined" >

        </div>



        <div class="form-group">
            <button class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
        <hr>
        <br>
        <div style="margin-top: 20px">
            <div class="alert alert-success" id="success-alert-push-tarjeta" style="display: none">
                <strong>Bien Hecho! </strong> Evento lanzado correctamente.
            </div>

            <div class="alert alert-danger" id="error-alert-push-tarjeta" style="display: none">
                <strong>Ups! </strong> Ha ocurrido un error.
            </div>
        </div>
        <br><br>
        <h5 id="respuestaPagoPush" class="respuestaTarjetaPush" style="font-weight: bold;display: none;color: darkgreen"></h5>

        <div class="responseCreateCard">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">Emisor</th>
                    <th scope="col">Entrada</th>
                    <th scope="col">Salida</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Detalles</th>

                </tr>
                </thead>

                @if(isset($tarjetas_sesion))
                    <tbody class="cuerpoRespuestaCard">

                    @foreach($tarjetas_sesion as $tarjeta)
                        <tr>
                            <th>{{$tarjeta->emisor->name}}</th>
                            <th> {{$tarjeta->time_in}}</th>
                            <th> {{$tarjeta->time_out}}</th>
                            <th>{{$tarjeta->status}}</th>
                            <th>Habitación 1 : {{$tarjeta->room_no}} <br>Habitación 2 : {{$tarjeta->room_no2}} <br>Habitación 3 : {{$tarjeta->room_no3}} <br> Uid Card : {{$tarjeta->uid}}<br> Creado :  {{$tarjeta->created_at}}</th>

                        </tr>
                    @endforeach

                    </tbody>
                @else
                    <tbody class="cuerpoRespuestaCard">

                    </tbody>
                @endif

            </table>

        </div>

        <script type="text/javascript">
            $(document).ready(function(){



                $('#grabarTarjetaForm').on('submit',function(e){
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route("admin.grabacion-tarjeta.store") }}",
                        method: 'POST',
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                        data:new FormData(this),
                        success:function(response){

                            if(response['success']){
                                $("#success-alert-push-tarjeta").show();
                                $("#success-alert-push-tarjeta").fadeTo(2000, 500).slideUp(500, function() {
                                    $("#success-alert-push-tarjeta").slideUp(500);
                                });

                                $(".respuestaTarjetaPush").empty();
                                $(".respuestaTarjetaPush").show();
                                $(".respuestaTarjetaPush").append(response['success']);

                                $(".cuerpoRespuestaCard").empty();
                                response['success'].forEach((element) =>
                                    $(".cuerpoRespuestaCard").append("<tr><th>" + element['emisor']['name'] + "</th><th>"  + element['date_in'] +" <br>"+ element['time_in'] + "</th><th>"  + element['date_out'] +" <br>"+ element['time_out'] + "</th><th>"  + element['status'] + "</th><th>Habitación 1 : " + element['room_no'] + "<br>Habitación 2 : " + element['room_no2'] + "<br>Habitación 3 : " + element['room_no3'] + "<br> Uid Card : " + element['uid_card'] + "<br> Creado :  " + element['created_at'] + "</th>" )

                                );


                            }
                            if(response['error']){
                                $("#error-alert-push-tarjeta").show();
                                $("#error-alert-push-tarjeta").fadeTo(2000, 500).slideUp(500, function() {
                                    $("#error-alert-push-tarjeta").slideUp(500);
                                });
                                alert(response['error']['errorInfo'])
                            }







                        },
                        error: function(response) {

                        },
                    });
                });

                $('#mensajeInicial').on('submit',function(e){
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route("admin.grabacion-tarjeta.notify-put-card-on-reader") }}",
                        method: 'POST',
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                        data:new FormData(this),
                        success:function(response){

                            if(response['success']){
                                $("#success-alert-push-tarjeta").show();
                                $("#success-alert-push-tarjeta").fadeTo(2000, 500).slideUp(500, function() {
                                    $("#success-alert-push-tarjeta").slideUp(500);
                                });

                                $(".respuestaTarjetaPush").empty();
                                $(".respuestaTarjetaPush").show();
                                $(".respuestaTarjetaPush").append(response['success']);

                            }
                            if(response['error']){
                                $("#error-alert-push-tarjeta").show();
                                $("#error-alert-push-tarjeta").fadeTo(2000, 500).slideUp(500, function() {
                                    $("#error-alert-push-tarjeta").slideUp(500);
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
