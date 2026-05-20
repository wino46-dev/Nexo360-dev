@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.dockStockHotel.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.dock-stock-hotels.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="nombre">{{ trans('cruds.dockStockHotel.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dockStockHotel.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="ubicacion_id">{{ trans('cruds.dockStockHotel.fields.ubicacion') }}</label>
                            <select class="form-control select2" name="ubicacion_id" id="ubicacion_id">
                                @foreach($ubicacions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('ubicacion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ubicacion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ubicacion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dockStockHotel.fields.ubicacion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">{{ trans('cruds.dockStockHotel.fields.cantidad') }}</label>
                            <input class="form-control" type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', '') }}" step="0.01">
                            @if($errors->has('cantidad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cantidad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dockStockHotel.fields.cantidad_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="comentarios">{{ trans('cruds.dockStockHotel.fields.comentarios') }}</label>
                            <textarea class="form-control ckeditor" name="comentarios" id="comentarios">{!! old('comentarios') !!}</textarea>
                            @if($errors->has('comentarios'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comentarios') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dockStockHotel.fields.comentarios_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.dockStockHotel.fields.unidad') }}</label>
                            <select class="form-control" name="unidad" id="unidad">
                                <option value disabled {{ old('unidad', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DockStockHotel::UNIDAD_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('unidad', 'Unidades') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('unidad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('unidad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dockStockHotel.fields.unidad_helper') }}</span>
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
                xhr.open('POST', '{{ route('frontend.dock-stock-hotels.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $dockStockHotel->id ?? 0 }}');
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