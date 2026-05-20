
            <form id="formPush" class="formPush">
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

                    <div class="col-md-10">
                        <div class="form-group">
                            <label class="required" for="tipo_evento_id">{{ trans('cruds.eventoHomeTotem.fields.tipo_evento') }}</label>
                            <select class="form-control select2 {{ $errors->has('tipo_evento') ? 'is-invalid' : '' }}" name="tipo_evento_id" id="tipo_evento_id" required>
                                @foreach($tipo_eventos as $id => $entry)
                                    @if($id != 3)
                                        @if($id != 4)
                                            @if($id != 6)
                                        <option value="{{ $id }}" {{ old('tipo_evento_id') == $id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;{{ $entry }}</option>
                                           @endif
                                        @endif
                                    @endif

                                @endforeach
                            </select>
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
                    <div class="col-md-2 pt-4">
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
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

            </form>
            <form action="{{ route("external.evento-home-totems.store") }}" id="notifyConection" name="notifyConection" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group" style="display: none">
                        <label class="required" for="receptor_id">{{ trans('cruds.eventoHomeTotem.fields.receptor') }}</label>
                        <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}"></input>
                        <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}"></input>
                        <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
                        <input type="hidden" name="tipo_evento_id" id="tipo_evento_id" value="1">
                        <input type="hidden" name="tipo" value="1">

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

            </form>

                <div class="alert alert-success" id="success-alert-push" style="display: none">
                    <strong>Bien Hecho! </strong> Evento lanzado correctamente.
                </div>

                <div class="alert alert-danger" id="error-alert-push" style="display: none">
                    <strong>Ups! </strong> Ha ocurrido un error.
                </div>

            <div class="responseCreatePush">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Sesion_id</th>
                            <th scope="col">Emisor</th>
                            <th scope="col">Tipo Evento</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>

                    @if(isset($eventos_sesion_push))
                        <tbody class="cuerpoRespuestaPush">

                            @foreach($eventos_sesion_push as $evento)
                                @if($evento->tipo_evento_id == 1 or $evento->tipo_evento_id == 2 or $evento->tipo_evento_id == 5)
                                    <tr>
                                        <td>{{$evento->sesion_id}}</td>
                                        <td>{{$evento->name}}</td>
                                        <td>{{$evento->nombre}}</td>
                                        <td>{{$evento->created_at}}</td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    @else
                        <tbody class="cuerpoRespuestaPush">

                        </tbody>
                    @endif

                </table>

            </div>

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}

                    <script type="text/javascript">
                        $(document).ready(function(){
                            $("#notifyConection").submit();
                        });

                    </script>
                </div>
            @endif

            <script type="text/javascript">
                $(document).ready(function(){

                    $('#formPush').on('submit',function(e){
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
                                    $("#success-alert-push").show();
                                    $("#success-alert-push").fadeTo(2000, 500).slideUp(500, function() {
                                        $("#success-alert-push").slideUp(500);
                                    });

                                    $(".respuestaPush").append(response['success']);
                                    $(".cuerpoRespuestaPush").empty();
                                    response['success'].forEach((element) =>
                                            $(".cuerpoRespuestaPush").append("<tr><th>" + element['sesion_id'] + "</th><th>"  + element['name'] + "</th><th>"  + element['nombre'] + "</th><th>"  + element['created_at'] + "</th>")

                                    );


                                }
                                if(response['error']){
                                    $("#error-alert-push").show();
                                    $("#error-alert-push").fadeTo(2000, 500).slideUp(500, function() {
                                        $("#error-alert-push").slideUp(500);
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
