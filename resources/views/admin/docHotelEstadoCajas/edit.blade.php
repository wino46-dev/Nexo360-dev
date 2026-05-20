@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.docHotelEstadoCaja.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.doc-hotel-estado-cajas.update", [$docHotelEstadoCaja->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="establecimiento_id">{{ trans('cruds.docHotelEstadoCaja.fields.establecimiento') }}</label>
                <select class="form-control select2 {{ $errors->has('establecimiento') ? 'is-invalid' : '' }}" name="establecimiento_id" id="establecimiento_id" required>
                    @foreach($establecimientos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('establecimiento_id') ? old('establecimiento_id') : $docHotelEstadoCaja->establecimiento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('establecimiento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('establecimiento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.establecimiento_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="caja">{{ trans('cruds.docHotelEstadoCaja.fields.caja') }}</label>
                <input class="form-control {{ $errors->has('caja') ? 'is-invalid' : '' }}" type="number" name="caja" id="caja" value="{{ old('caja', $docHotelEstadoCaja->caja) }}" step="1" required>
                @if($errors->has('caja'))
                    <div class="invalid-feedback">
                        {{ $errors->first('caja') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.caja_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="code">{{ trans('cruds.docHotelEstadoCaja.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', $docHotelEstadoCaja->code) }}">
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="room_id">{{ trans('cruds.docHotelEstadoCaja.fields.room') }}</label>
                <select class="form-control select2 {{ $errors->has('room') ? 'is-invalid' : '' }}" name="room_id" id="room_id">
                    @foreach($rooms as $id => $entry)
                        <option value="{{ $id }}" {{ (old('room_id') ? old('room_id') : $docHotelEstadoCaja->room->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('room'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.room_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.docHotelEstadoCaja.fields.room_status') }}</label>
                <select class="form-control {{ $errors->has('room_status') ? 'is-invalid' : '' }}" name="room_status" id="room_status">
                    <option value disabled {{ old('room_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\DocHotelEstadoCaja::ROOM_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('room_status', $docHotelEstadoCaja->room_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('room_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.room_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cliente">{{ trans('cruds.docHotelEstadoCaja.fields.cliente') }}</label>
                <input class="form-control {{ $errors->has('cliente') ? 'is-invalid' : '' }}" type="text" name="cliente" id="cliente" value="{{ old('cliente', $docHotelEstadoCaja->cliente) }}">
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="documento_cliente">{{ trans('cruds.docHotelEstadoCaja.fields.documento_cliente') }}</label>
                <input class="form-control {{ $errors->has('documento_cliente') ? 'is-invalid' : '' }}" type="text" name="documento_cliente" id="documento_cliente" value="{{ old('documento_cliente', $docHotelEstadoCaja->documento_cliente) }}">
                @if($errors->has('documento_cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('documento_cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.documento_cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comments">{{ trans('cruds.docHotelEstadoCaja.fields.comments') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('comments') ? 'is-invalid' : '' }}" name="comments" id="comments">{!! old('comments', $docHotelEstadoCaja->comments) !!}</textarea>
                @if($errors->has('comments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.comments_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.docHotelEstadoCaja.fields.pay') }}</label>
                <select class="form-control {{ $errors->has('pay') ? 'is-invalid' : '' }}" name="pay" id="pay">
                    <option value disabled {{ old('pay', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\DocHotelEstadoCaja::PAY_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('pay', $docHotelEstadoCaja->pay) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('pay'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pay') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.pay_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha">{{ trans('cruds.docHotelEstadoCaja.fields.fecha') }}</label>
                <input class="form-control date {{ $errors->has('fecha') ? 'is-invalid' : '' }}" type="text" name="fecha" id="fecha" value="{{ old('fecha', $docHotelEstadoCaja->fecha) }}">
                @if($errors->has('fecha'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docHotelEstadoCaja.fields.fecha_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.doc-hotel-estado-cajas.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $docHotelEstadoCaja->id ?? 0 }}');
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