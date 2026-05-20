@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.docHabitacionHotel.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.doc-habitacion-hotels.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="establecimiento_id">{{ trans('cruds.docHabitacionHotel.fields.establecimiento') }}</label>
                            <select class="form-control select2" name="establecimiento_id" id="establecimiento_id" required>
                                @foreach($establecimientos as $id => $entry)
                                    <option value="{{ $id }}" {{ old('establecimiento_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('establecimiento'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('establecimiento') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="nombre">{{ trans('cruds.docHabitacionHotel.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docHabitacionHotel.fields.tipo') }}</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value disabled {{ old('tipo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocHabitacionHotel::TIPO_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('tipo', 'Económica') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tipo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.tipo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.docHabitacionHotel.fields.capacidad') }}</label>
                            <select class="form-control" name="capacidad" id="capacidad" required>
                                <option value disabled {{ old('capacidad', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocHabitacionHotel::CAPACIDAD_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('capacidad', '1') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('capacidad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('capacidad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.capacidad_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docHabitacionHotel.fields.tipo_cerradura') }}</label>
                            <select class="form-control" name="tipo_cerradura" id="tipo_cerradura">
                                <option value disabled {{ old('tipo_cerradura', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocHabitacionHotel::TIPO_CERRADURA_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('tipo_cerradura', 'LLave') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo_cerradura'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tipo_cerradura') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.tipo_cerradura_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="codigo">{{ trans('cruds.docHabitacionHotel.fields.codigo') }}</label>
                            <input class="form-control" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}">
                            @if($errors->has('codigo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('codigo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.codigo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="comentarios">{{ trans('cruds.docHabitacionHotel.fields.comentarios') }}</label>
                            <textarea class="form-control ckeditor" name="comentarios" id="comentarios">{!! old('comentarios') !!}</textarea>
                            @if($errors->has('comentarios'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comentarios') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHabitacionHotel.fields.comentarios_helper') }}</span>
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
                xhr.open('POST', '{{ route('frontend.doc-habitacion-hotels.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $docHabitacionHotel->id ?? 0 }}');
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