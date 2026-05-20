@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.ayudaStepTotem.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ayuda-step-totems.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="establecimiento_id">{{ trans('cruds.ayudaStepTotem.fields.establecimiento') }}</label>
                <select class="form-control select2 {{ $errors->has('establecimiento') ? 'is-invalid' : '' }}" name="establecimiento_id" id="establecimiento_id" required>
                    @foreach($establecimientos as $id => $entry)
                        <option value="{{ $id }}" {{ old('establecimiento_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('establecimiento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('establecimiento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.establecimiento_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eventos_push">{{ trans('cruds.ayudaStepTotem.fields.eventos_push') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('eventos_push') ? 'is-invalid' : '' }}" name="eventos_push" id="eventos_push">{!! old('eventos_push') !!}</textarea>
                @if($errors->has('eventos_push'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eventos_push') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.eventos_push_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pase_imagenes">{{ trans('cruds.ayudaStepTotem.fields.pase_imagenes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('pase_imagenes') ? 'is-invalid' : '' }}" name="pase_imagenes" id="pase_imagenes">{!! old('pase_imagenes') !!}</textarea>
                @if($errors->has('pase_imagenes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pase_imagenes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.pase_imagenes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="captura_documentos">{{ trans('cruds.ayudaStepTotem.fields.captura_documentos') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('captura_documentos') ? 'is-invalid' : '' }}" name="captura_documentos" id="captura_documentos">{!! old('captura_documentos') !!}</textarea>
                @if($errors->has('captura_documentos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('captura_documentos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.captura_documentos_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pago_reserva">{{ trans('cruds.ayudaStepTotem.fields.pago_reserva') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('pago_reserva') ? 'is-invalid' : '' }}" name="pago_reserva" id="pago_reserva">{!! old('pago_reserva') !!}</textarea>
                @if($errors->has('pago_reserva'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pago_reserva') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.pago_reserva_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="grabacion_tarjeta">{{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('grabacion_tarjeta') ? 'is-invalid' : '' }}" name="grabacion_tarjeta" id="grabacion_tarjeta">{!! old('grabacion_tarjeta') !!}</textarea>
                @if($errors->has('grabacion_tarjeta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('grabacion_tarjeta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="parte_viajero">{{ trans('cruds.ayudaStepTotem.fields.parte_viajero') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('parte_viajero') ? 'is-invalid' : '' }}" name="parte_viajero" id="parte_viajero">{!! old('parte_viajero') !!}</textarea>
                @if($errors->has('parte_viajero'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parte_viajero') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.parte_viajero_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.ayuda-step-totems.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $ayudaStepTotem->id ?? 0 }}');
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