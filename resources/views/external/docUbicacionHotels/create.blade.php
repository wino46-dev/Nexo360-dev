@extends('layouts.external')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.docUbicacionHotel.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('external.doc-ubicacion-hotels.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="establecimiento_id">{{ trans('cruds.docUbicacionHotel.fields.establecimiento') }}</label>
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
                <span class="help-block">{{ trans('cruds.docUbicacionHotel.fields.establecimiento_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.docUbicacionHotel.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docUbicacionHotel.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.docUbicacionHotel.fields.tipo') }}</label>
                <select class="form-control {{ $errors->has('tipo') ? 'is-invalid' : '' }}" name="tipo" id="tipo">
                    <option value disabled {{ old('tipo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\DocUbicacionHotel::TIPO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo', 'Trastero') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docUbicacionHotel.fields.tipo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="piso">{{ trans('cruds.docUbicacionHotel.fields.piso') }}</label>
                <input class="form-control {{ $errors->has('piso') ? 'is-invalid' : '' }}" type="number" name="piso" id="piso" value="{{ old('piso', '') }}" step="1">
                @if($errors->has('piso'))
                    <div class="invalid-feedback">
                        {{ $errors->first('piso') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docUbicacionHotel.fields.piso_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('zona_comun') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="zona_comun" value="0">
                    <input class="form-check-input" type="checkbox" name="zona_comun" id="zona_comun" value="1" {{ old('zona_comun', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="zona_comun">{{ trans('cruds.docUbicacionHotel.fields.zona_comun') }}</label>
                </div>
                @if($errors->has('zona_comun'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zona_comun') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docUbicacionHotel.fields.zona_comun_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comentarios">{{ trans('cruds.docUbicacionHotel.fields.comentarios') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('comentarios') ? 'is-invalid' : '' }}" name="comentarios" id="comentarios">{!! old('comentarios') !!}</textarea>
                @if($errors->has('comentarios'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comentarios') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docUbicacionHotel.fields.comentarios_helper') }}</span>
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
                xhr.open('POST', '{{ route('external.doc-ubicacion-hotels.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $docUbicacionHotel->id ?? 0 }}');
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
