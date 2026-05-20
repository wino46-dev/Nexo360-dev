@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.layoutHome.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.layout-homes.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label>{{ trans('cruds.layoutHome.fields.tipo') }}</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value disabled {{ old('tipo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\LayoutHome::TIPO_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('tipo', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tipo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.layoutHome.fields.tipo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="imagen_1">{{ trans('cruds.layoutHome.fields.imagen_1') }}</label>
                            <div class="needsclick dropzone" id="imagen_1-dropzone">
                            </div>
                            @if($errors->has('imagen_1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagen_1') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.layoutHome.fields.imagen_1_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="imagen_2">{{ trans('cruds.layoutHome.fields.imagen_2') }}</label>
                            <div class="needsclick dropzone" id="imagen_2-dropzone">
                            </div>
                            @if($errors->has('imagen_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagen_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.layoutHome.fields.imagen_2_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="imagen_3">{{ trans('cruds.layoutHome.fields.imagen_3') }}</label>
                            <div class="needsclick dropzone" id="imagen_3-dropzone">
                            </div>
                            @if($errors->has('imagen_3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagen_3') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.layoutHome.fields.imagen_3_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="imagen_4">{{ trans('cruds.layoutHome.fields.imagen_4') }}</label>
                            <div class="needsclick dropzone" id="imagen_4-dropzone">
                            </div>
                            @if($errors->has('imagen_4'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagen_4') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.layoutHome.fields.imagen_4_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="imagen_5">{{ trans('cruds.layoutHome.fields.imagen_5') }}</label>
                            <div class="needsclick dropzone" id="imagen_5-dropzone">
                            </div>
                            @if($errors->has('imagen_5'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('imagen_5') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.layoutHome.fields.imagen_5_helper') }}</span>
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
    Dropzone.options.imagen1Dropzone = {
    url: '{{ route('frontend.layout-homes.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="imagen_1"]').remove()
      $('form').append('<input type="hidden" name="imagen_1" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_1"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($layoutHome) && $layoutHome->imagen_1)
      var file = {!! json_encode($layoutHome->imagen_1) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_1" value="' + file.file_name + '">')
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
    Dropzone.options.imagen2Dropzone = {
    url: '{{ route('frontend.layout-homes.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="imagen_2"]').remove()
      $('form').append('<input type="hidden" name="imagen_2" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_2"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($layoutHome) && $layoutHome->imagen_2)
      var file = {!! json_encode($layoutHome->imagen_2) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_2" value="' + file.file_name + '">')
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
    Dropzone.options.imagen3Dropzone = {
    url: '{{ route('frontend.layout-homes.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="imagen_3"]').remove()
      $('form').append('<input type="hidden" name="imagen_3" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_3"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($layoutHome) && $layoutHome->imagen_3)
      var file = {!! json_encode($layoutHome->imagen_3) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_3" value="' + file.file_name + '">')
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
    Dropzone.options.imagen4Dropzone = {
    url: '{{ route('frontend.layout-homes.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="imagen_4"]').remove()
      $('form').append('<input type="hidden" name="imagen_4" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_4"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($layoutHome) && $layoutHome->imagen_4)
      var file = {!! json_encode($layoutHome->imagen_4) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_4" value="' + file.file_name + '">')
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
    Dropzone.options.imagen5Dropzone = {
    url: '{{ route('frontend.layout-homes.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="imagen_5"]').remove()
      $('form').append('<input type="hidden" name="imagen_5" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_5"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($layoutHome) && $layoutHome->imagen_5)
      var file = {!! json_encode($layoutHome->imagen_5) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_5" value="' + file.file_name + '">')
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