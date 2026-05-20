@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.docNoDeseadoHotel.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.doc-no-deseado-hotels.update", [$docNoDeseadoHotel->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="establecimiento_id">{{ trans('cruds.docNoDeseadoHotel.fields.establecimiento') }}</label>
                <select class="form-control select2 {{ $errors->has('establecimiento') ? 'is-invalid' : '' }}" name="establecimiento_id" id="establecimiento_id" required>
                    @foreach($establecimientos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('establecimiento_id') ? old('establecimiento_id') : $docNoDeseadoHotel->establecimiento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('establecimiento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('establecimiento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docNoDeseadoHotel.fields.establecimiento_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_dt">{{ trans('cruds.docNoDeseadoHotel.fields.fecha') }}</label>
                <input class="form-control {{ $errors->has('fecha') ? 'is-invalid' : '' }}" type="datetime-local" id="fecha_dt" readonly onkeydown="return false" onclick="return false">
                <input type="hidden" name="fecha" id="fecha" value="{{ old('fecha', $docNoDeseadoHotel->fecha) }}">
                @if($errors->has('fecha'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha') }}
                    </div>
                @endif
                <small class="form-text text-muted">Formato enviado: DD-MM-YYYY HH:mm:ss</small>
                <span class="help-block">{{ trans('cruds.docNoDeseadoHotel.fields.fecha_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="datos_cliente">{{ trans('cruds.docNoDeseadoHotel.fields.datos_cliente') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('datos_cliente') ? 'is-invalid' : '' }}" name="datos_cliente" id="datos_cliente">{!! old('datos_cliente', $docNoDeseadoHotel->datos_cliente) !!}</textarea>
                @if($errors->has('datos_cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('datos_cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docNoDeseadoHotel.fields.datos_cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('no_deseado') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="no_deseado" value="0">
                    <input class="form-check-input" type="checkbox" name="no_deseado" id="no_deseado" value="1" {{ $docNoDeseadoHotel->no_deseado || old('no_deseado', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="no_deseado">{{ trans('cruds.docNoDeseadoHotel.fields.no_deseado') }}</label>
                </div>
                @if($errors->has('no_deseado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('no_deseado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docNoDeseadoHotel.fields.no_deseado_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="motivo">{{ trans('cruds.docNoDeseadoHotel.fields.motivo') }}</label>
                <input class="form-control {{ $errors->has('motivo') ? 'is-invalid' : '' }}" type="text" name="motivo" id="motivo" value="{{ old('motivo', $docNoDeseadoHotel->motivo) }}" required>
                @if($errors->has('motivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('motivo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docNoDeseadoHotel.fields.motivo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comentarios">{{ trans('cruds.docNoDeseadoHotel.fields.comentarios') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('comentarios') ? 'is-invalid' : '' }}" name="comentarios" id="comentarios">{!! old('comentarios', $docNoDeseadoHotel->comentarios) !!}</textarea>
                @if($errors->has('comentarios'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comentarios') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docNoDeseadoHotel.fields.comentarios_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
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
                xhr.open('POST', '{{ route('admin.doc-no-deseado-hotels.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $docNoDeseadoHotel->id ?? 0 }}');
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
