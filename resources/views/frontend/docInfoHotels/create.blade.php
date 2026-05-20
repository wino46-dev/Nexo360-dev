@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.docInfoHotel.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.doc-info-hotels.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="hotel_id">{{ trans('cruds.docInfoHotel.fields.hotel') }}</label>
                            <select class="form-control select2" name="hotel_id" id="hotel_id" required>
                                @foreach($hotels as $id => $entry)
                                    <option value="{{ $id }}" {{ old('hotel_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('hotel'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('hotel') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.hotel_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">{{ trans('cruds.docInfoHotel.fields.descripcion') }}</label>
                            <textarea class="form-control ckeditor" name="descripcion" id="descripcion">{!! old('descripcion') !!}</textarea>
                            @if($errors->has('descripcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('descripcion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.descripcion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="exteriores">{{ trans('cruds.docInfoHotel.fields.exteriores') }}</label>
                            <textarea class="form-control ckeditor" name="exteriores" id="exteriores">{!! old('exteriores') !!}</textarea>
                            @if($errors->has('exteriores'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('exteriores') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.exteriores_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docInfoHotel.fields.categoria') }}</label>
                            <select class="form-control" name="categoria" id="categoria">
                                <option value disabled {{ old('categoria', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocInfoHotel::CATEGORIA_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('categoria', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('categoria'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('categoria') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.categoria_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="pais_id">{{ trans('cruds.docInfoHotel.fields.pais') }}</label>
                            <select class="form-control select2" name="pais_id" id="pais_id">
                                @foreach($pais as $id => $entry)
                                    <option value="{{ $id }}" {{ old('pais_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('pais'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pais') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.pais_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="provincia_id">{{ trans('cruds.docInfoHotel.fields.provincia') }}</label>
                            <select class="form-control select2" name="provincia_id" id="provincia_id">
                                @foreach($provincias as $id => $entry)
                                    <option value="{{ $id }}" {{ old('provincia_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('provincia'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('provincia') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.provincia_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="ciudad_id">{{ trans('cruds.docInfoHotel.fields.ciudad') }}</label>
                            <select class="form-control select2" name="ciudad_id" id="ciudad_id">
                                @foreach($ciudads as $id => $entry)
                                    <option value="{{ $id }}" {{ old('ciudad_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ciudad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ciudad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.ciudad_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="direccion">{{ trans('cruds.docInfoHotel.fields.direccion') }}</label>
                            <input class="form-control" type="text" name="direccion" id="direccion" value="{{ old('direccion', '') }}">
                            @if($errors->has('direccion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('direccion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.direccion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="codigo_postal">{{ trans('cruds.docInfoHotel.fields.codigo_postal') }}</label>
                            <input class="form-control" type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal', '') }}">
                            @if($errors->has('codigo_postal'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('codigo_postal') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.codigo_postal_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="latitud">{{ trans('cruds.docInfoHotel.fields.latitud') }}</label>
                            <input class="form-control" type="text" name="latitud" id="latitud" value="{{ old('latitud', '') }}">
                            @if($errors->has('latitud'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('latitud') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.latitud_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="longitud">{{ trans('cruds.docInfoHotel.fields.longitud') }}</label>
                            <input class="form-control" type="text" name="longitud" id="longitud" value="{{ old('longitud', '') }}">
                            @if($errors->has('longitud'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('longitud') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.longitud_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="telefono">{{ trans('cruds.docInfoHotel.fields.telefono') }}</label>
                            <input class="form-control" type="text" name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                            @if($errors->has('telefono'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('telefono') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.telefono_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="emergencias">{{ trans('cruds.docInfoHotel.fields.emergencias') }}</label>
                            <input class="form-control" type="text" name="emergencias" id="emergencias" value="{{ old('emergencias', '') }}">
                            @if($errors->has('emergencias'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('emergencias') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.emergencias_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ trans('cruds.docInfoHotel.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="web">{{ trans('cruds.docInfoHotel.fields.web') }}</label>
                            <input class="form-control" type="text" name="web" id="web" value="{{ old('web', '') }}">
                            @if($errors->has('web'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('web') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.web_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="enlace_fotos">{{ trans('cruds.docInfoHotel.fields.enlace_fotos') }}</label>
                            <input class="form-control" type="text" name="enlace_fotos" id="enlace_fotos" value="{{ old('enlace_fotos', '') }}">
                            @if($errors->has('enlace_fotos'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enlace_fotos') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.enlace_fotos_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="aparcamiento">{{ trans('cruds.docInfoHotel.fields.aparcamiento') }}</label>
                            <textarea class="form-control ckeditor" name="aparcamiento" id="aparcamiento">{!! old('aparcamiento') !!}</textarea>
                            @if($errors->has('aparcamiento'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('aparcamiento') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.aparcamiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="recomendaciones">{{ trans('cruds.docInfoHotel.fields.recomendaciones') }}</label>
                            <textarea class="form-control ckeditor" name="recomendaciones" id="recomendaciones">{!! old('recomendaciones') !!}</textarea>
                            @if($errors->has('recomendaciones'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('recomendaciones') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.recomendaciones_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="horas_chekin">{{ trans('cruds.docInfoHotel.fields.horas_chekin') }}</label>
                            <textarea class="form-control ckeditor" name="horas_chekin" id="horas_chekin">{!! old('horas_chekin') !!}</textarea>
                            @if($errors->has('horas_chekin'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('horas_chekin') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.horas_chekin_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="observaciones">{{ trans('cruds.docInfoHotel.fields.observaciones') }}</label>
                            <textarea class="form-control ckeditor" name="observaciones" id="observaciones">{!! old('observaciones') !!}</textarea>
                            @if($errors->has('observaciones'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('observaciones') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.observaciones_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cuenta_bancaria">{{ trans('cruds.docInfoHotel.fields.cuenta_bancaria') }}</label>
                            <input class="form-control" type="text" name="cuenta_bancaria" id="cuenta_bancaria" value="{{ old('cuenta_bancaria', '') }}">
                            @if($errors->has('cuenta_bancaria'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cuenta_bancaria') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.cuenta_bancaria_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="modos_cobro">{{ trans('cruds.docInfoHotel.fields.modos_cobro') }}</label>
                            <input class="form-control" type="text" name="modos_cobro" id="modos_cobro" value="{{ old('modos_cobro', '') }}">
                            @if($errors->has('modos_cobro'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('modos_cobro') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.modos_cobro_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="datos_responsable">{{ trans('cruds.docInfoHotel.fields.datos_responsable') }}</label>
                            <textarea class="form-control ckeditor" name="datos_responsable" id="datos_responsable">{!! old('datos_responsable') !!}</textarea>
                            @if($errors->has('datos_responsable'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('datos_responsable') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.datos_responsable_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="regional_manager">{{ trans('cruds.docInfoHotel.fields.regional_manager') }}</label>
                            <textarea class="form-control ckeditor" name="regional_manager" id="regional_manager">{!! old('regional_manager') !!}</textarea>
                            @if($errors->has('regional_manager'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('regional_manager') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.regional_manager_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="revenue_manager">{{ trans('cruds.docInfoHotel.fields.revenue_manager') }}</label>
                            <textarea class="form-control ckeditor" name="revenue_manager" id="revenue_manager">{!! old('revenue_manager') !!}</textarea>
                            @if($errors->has('revenue_manager'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('revenue_manager') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.revenue_manager_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docInfoHotel.fields.pet_friendly') }}</label>
                            <select class="form-control" name="pet_friendly" id="pet_friendly">
                                <option value disabled {{ old('pet_friendly', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocInfoHotel::PET_FRIENDLY_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('pet_friendly', 'NO') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('pet_friendly'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pet_friendly') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docInfoHotel.fields.pet_friendly_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('frontend.doc-info-hotels.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $docInfoHotel->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection