@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.establecimiento.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.establecimientos.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="sociedad_id">{{ trans('cruds.establecimiento.fields.sociedad') }}</label>
                            <select class="form-control select2" name="sociedad_id" id="sociedad_id" required>
                                @foreach($sociedads as $id => $entry)
                                    <option value="{{ $id }}" {{ old('sociedad_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('sociedad'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sociedad') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.establecimiento.fields.sociedad_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="codigo">{{ trans('cruds.establecimiento.fields.codigo') }}</label>
                            <input class="form-control" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}" required>
                            @if($errors->has('codigo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('codigo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.establecimiento.fields.codigo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="nombre">{{ trans('cruds.establecimiento.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.establecimiento.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="imagenes">{{ trans('cruds.establecimiento.fields.imagenes') }}</label>
                            <div class="needsclick dropzone" id="imagenes-dropzone">
                            </div>
                            @if($errors->has('imagenes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagenes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.establecimiento.fields.imagenes_helper') }}</span>
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
    url: '{{ route('frontend.establecimientos.storeMedia') }}',
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
@if(isset($establecimiento) && $establecimiento->imagenes)
      var files = {!! json_encode($establecimiento->imagenes) !!}
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