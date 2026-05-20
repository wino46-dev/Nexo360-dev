@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.firmaCheckIn.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.firma-check-ins.update", [$firmaCheckIn->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="documento">{{ trans('cruds.firmaCheckIn.fields.documento') }}</label>
                <div class="needsclick dropzone {{ $errors->has('documento') ? 'is-invalid' : '' }}" id="documento-dropzone">
                </div>
                @if($errors->has('documento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('documento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.firmaCheckIn.fields.documento_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="firma_texto">{{ trans('cruds.firmaCheckIn.fields.firma_texto') }}</label>
                <textarea class="form-control {{ $errors->has('firma_texto') ? 'is-invalid' : '' }}" name="firma_texto" id="firma_texto">{{ old('firma_texto', $firmaCheckIn->firma_texto) }}</textarea>
                @if($errors->has('firma_texto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('firma_texto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.firmaCheckIn.fields.firma_texto_helper') }}</span>
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
    Dropzone.options.documentoDropzone = {
    url: '{{ route('admin.firma-check-ins.storeMedia') }}',
    maxFilesize: 4, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 4
    },
    success: function (file, response) {
      $('form').find('input[name="documento"]').remove()
      $('form').append('<input type="hidden" name="documento" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="documento"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($firmaCheckIn) && $firmaCheckIn->documento)
      var file = {!! json_encode($firmaCheckIn->documento) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="documento" value="' + file.file_name + '">')
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
@endsection