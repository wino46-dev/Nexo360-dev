@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.checkIn.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.check-ins.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="cliente_id">{{ trans('cruds.checkIn.fields.cliente') }}</label>
                            <select class="form-control select2" name="cliente_id" id="cliente_id" required>
                                @foreach($clientes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('cliente_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cliente'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cliente') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.cliente_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="reserva_id">{{ trans('cruds.checkIn.fields.reserva') }}</label>
                            <select class="form-control select2" name="reserva_id" id="reserva_id" required>
                                @foreach($reservas as $id => $entry)
                                    <option value="{{ $id }}" {{ old('reserva_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reserva'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reserva') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.reserva_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="habitacion_id">{{ trans('cruds.checkIn.fields.habitacion') }}</label>
                            <select class="form-control select2" name="habitacion_id" id="habitacion_id">
                                @foreach($habitacions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('habitacion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('habitacion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('habitacion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.habitacion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="totem_id">{{ trans('cruds.checkIn.fields.totem') }}</label>
                            <select class="form-control select2" name="totem_id" id="totem_id" required>
                                @foreach($totems as $id => $entry)
                                    <option value="{{ $id }}" {{ old('totem_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('totem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('totem') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.totem_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="llave">{{ trans('cruds.checkIn.fields.llave') }}</label>
                            <input class="form-control" type="text" name="llave" id="llave" value="{{ old('llave', '') }}">
                            @if($errors->has('llave'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('llave') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.llave_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dni_anverso">{{ trans('cruds.checkIn.fields.dni_anverso') }}</label>
                            <div class="needsclick dropzone" id="dni_anverso-dropzone">
                            </div>
                            @if($errors->has('dni_anverso'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dni_anverso') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.dni_anverso_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dni_reverso">{{ trans('cruds.checkIn.fields.dni_reverso') }}</label>
                            <div class="needsclick dropzone" id="dni_reverso-dropzone">
                            </div>
                            @if($errors->has('dni_reverso'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dni_reverso') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.dni_reverso_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="firma_verificada" value="0">
                                <input type="checkbox" name="firma_verificada" id="firma_verificada" value="1" {{ old('firma_verificada', 0) == 1 ? 'checked' : '' }}>
                                <label for="firma_verificada">{{ trans('cruds.checkIn.fields.firma_verificada') }}</label>
                            </div>
                            @if($errors->has('firma_verificada'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('firma_verificada') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.firma_verificada_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="comentarios">{{ trans('cruds.checkIn.fields.comentarios') }}</label>
                            <textarea class="form-control ckeditor" name="comentarios" id="comentarios">{!! old('comentarios') !!}</textarea>
                            @if($errors->has('comentarios'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comentarios') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkIn.fields.comentarios_helper') }}</span>
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
    Dropzone.options.dniAnversoDropzone = {
    url: '{{ route('frontend.check-ins.storeMedia') }}',
    maxFilesize: 8, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 8,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="dni_anverso"]').remove()
      $('form').append('<input type="hidden" name="dni_anverso" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="dni_anverso"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($checkIn) && $checkIn->dni_anverso)
      var file = {!! json_encode($checkIn->dni_anverso) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="dni_anverso" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
<script>
    Dropzone.options.dniReversoDropzone = {
    url: '{{ route('frontend.check-ins.storeMedia') }}',
    maxFilesize: 8, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 8,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="dni_reverso"]').remove()
      $('form').append('<input type="hidden" name="dni_reverso" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="dni_reverso"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($checkIn) && $checkIn->dni_reverso)
      var file = {!! json_encode($checkIn->dni_reverso) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="dni_reverso" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
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
                xhr.open('POST', '{{ route('frontend.check-ins.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $checkIn->id ?? 0 }}');
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