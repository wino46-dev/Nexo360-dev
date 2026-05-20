@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.totem.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.totems.update", [$totem->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="establecimiento_id">{{ trans('cruds.totem.fields.establecimiento') }}</label>
                            <select class="form-control select2" name="establecimiento_id" id="establecimiento_id" required>
                                @foreach($establecimientos as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('establecimiento_id') ? old('establecimiento_id') : $totem->establecimiento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('establecimiento'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('establecimiento') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.totem.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="codigo">{{ trans('cruds.totem.fields.codigo') }}</label>
                            <input class="form-control" type="text" name="codigo" id="codigo" value="{{ old('codigo', $totem->codigo) }}" required>
                            @if($errors->has('codigo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('codigo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.totem.fields.codigo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.totem.fields.fuente_imagenes') }}</label>
                            <select class="form-control" name="fuente_imagenes" id="fuente_imagenes" required>
                                <option value disabled {{ old('fuente_imagenes', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Totem::FUENTE_IMAGENES_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('fuente_imagenes', $totem->fuente_imagenes) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('fuente_imagenes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fuente_imagenes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.totem.fields.fuente_imagenes_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="imagenes">{{ trans('cruds.totem.fields.imagenes') }}</label>
                            <div class="needsclick dropzone" id="imagenes-dropzone">
                            </div>
                            @if($errors->has('imagenes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagenes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.totem.fields.imagenes_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="comentarios">{{ trans('cruds.totem.fields.comentarios') }}</label>
                            <textarea class="form-control" name="comentarios" id="comentarios">{{ old('comentarios', $totem->comentarios) }}</textarea>
                            @if($errors->has('comentarios'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comentarios') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.totem.fields.comentarios_helper') }}</span>
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
    var uploadedImagenesMap = {}
Dropzone.options.imagenesDropzone = {
    url: '{{ route('frontend.totems.storeMedia') }}',
    maxFilesize: 6, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 6,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="imagenes[]" value="' + response.name + '">')
      uploadedImagenesMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedImagenesMap[file.name]
      }
      $('form').find('input[name="imagenes[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($totem) && $totem->imagenes)
      var files = {!! json_encode($totem->imagenes) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="imagenes[]" value="' + file.file_name + '">')
        }
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
@endsection