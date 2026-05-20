@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.docIncidenciaHotel.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.doc-incidencia-hotels.update", [$docIncidenciaHotel->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="establecimiento_id">{{ trans('cruds.docIncidenciaHotel.fields.establecimiento') }}</label>
                            <select class="form-control select2" name="establecimiento_id" id="establecimiento_id" required>
                                @foreach($establecimientos as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('establecimiento_id') ? old('establecimiento_id') : $docIncidenciaHotel->establecimiento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('establecimiento'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('establecimiento') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="fecha_dt">{{ trans('cruds.docIncidenciaHotel.fields.fecha') }}</label>
                            <input class="form-control" type="datetime-local" id="fecha_dt" required readonly onkeydown="return false" onclick="return false">
                            <input type="hidden" name="fecha" id="fecha" value="{{ old('fecha', $docIncidenciaHotel->fecha) }}">
                            @if($errors->has('fecha'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fecha') }}
                                </div>
                            @endif
                            <small class="form-text text-muted">Formato enviado: DD-MM-YYYY HH:mm:ss</small>
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.fecha_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="titulo">{{ trans('cruds.docIncidenciaHotel.fields.titulo') }}</label>
                            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ old('titulo', $docIncidenciaHotel->titulo) }}" required>
                            @if($errors->has('titulo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('titulo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.titulo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.docIncidenciaHotel.fields.estado') }}</label>
                            <select class="form-control" name="estado" id="estado" required>
                                <option value disabled {{ old('estado', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocIncidenciaHotel::ESTADO_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('estado', $docIncidenciaHotel->estado) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('estado'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('estado') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.estado_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dni">{{ trans('cruds.docIncidenciaHotel.fields.dni') }}</label>
                            <input class="form-control" type="text" name="dni" id="dni" value="{{ old('dni', $docIncidenciaHotel->dni) }}">
                            @if($errors->has('dni'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dni') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.dni_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nombre">{{ trans('cruds.docIncidenciaHotel.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', $docIncidenciaHotel->nombre) }}">
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">{{ trans('cruds.docIncidenciaHotel.fields.descripcion') }}</label>
                            <textarea class="form-control ckeditor" name="descripcion" id="descripcion">{!! old('descripcion', $docIncidenciaHotel->descripcion) !!}</textarea>
                            @if($errors->has('descripcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('descripcion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docIncidenciaHotel.fields.descripcion_helper') }}</span>
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
                xhr.open('POST', '{{ route('frontend.doc-incidencia-hotels.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $docIncidenciaHotel->id ?? 0 }}');
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
<script>
    document.addEventListener('DOMContentLoaded', function(){
        try{
            var hidden = document.getElementById('fecha');
            var visible = document.getElementById('fecha_dt');
            if(!visible || !hidden) return;
            function toLocalInputValue(m){ return (m && m.isValid && m.isValid()) ? m.format('YYYY-MM-DDTHH:mm') : ''; }
            function toBackendFormat(m){ return (m && m.isValid && m.isValid()) ? m.format('DD-MM-YYYY HH:mm:ss') : ''; }
            if (hidden.value) {
                var m = moment(hidden.value, 'DD-MM-YYYY HH:mm:ss', true);
                if(!m.isValid()) m = moment(hidden.value);
                visible.value = toLocalInputValue(m);
            } else {
                var now = moment();
                visible.value = toLocalInputValue(now);
                hidden.value = toBackendFormat(now);
            }
            visible.addEventListener('change', function(){
                var m = moment(visible.value);
                hidden.value = toBackendFormat(m);
            });
        }catch(e){ console.error(e); }
    });
</script>

@endsection
