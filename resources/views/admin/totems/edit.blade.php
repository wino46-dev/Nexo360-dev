@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.totem.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.totems.update", [$totem->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="required" for="establecimiento_id">{{ trans('cruds.totem.fields.establecimiento') }}</label>
                    <select class="form-control select2 {{ $errors->has('establecimiento') ? 'is-invalid' : '' }}" name="establecimiento_id" id="establecimiento_id" required>
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
                <div class="form-group  col-md-6">
                    <label class="required" for="codigo">{{ trans('cruds.totem.fields.codigo') }}</label>
                    <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', $totem->codigo) }}" required>
                    @if($errors->has('codigo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('codigo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.totem.fields.codigo_helper') }}</span>
                </div>
            </div>

            
            
            <!-- Pagina inicial -->
            <div class="card">
                <div class="card-header">
                    <b>Página Inicial</b>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.totem.fields.fuente_imagenes_pagina_1') }}</label>
                        <select class="form-control {{ $errors->has('fuente_imagenes_pagina_1') ? 'is-invalid' : '' }}" name="fuente_imagenes_pagina_1" id="fuente_imagenes_pagina_1" required>
                            <option value disabled {{ old('fuente_imagenes_pagina_1', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Totem::FUENTE_IMAGENES_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('fuente_imagenes_pagina_1', $totem->fuente_imagenes_pagina_1) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('fuente_imagenes_pagina_1'))
                            <div class="invalid-feedback">
                                {{ $errors->first('fuente_imagenes_pagina_1') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.fuente_imagenes_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="imagenes_pagina_inicial">{{ trans('cruds.totem.fields.imagenes_pagina_inicial') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('imagenes_pagina_inicial') ? 'is-invalid' : '' }}" id="imagenes_pagina_inicial-dropzone">
                        </div>
                        @if($errors->has('imagenes_pagina_inicial'))
                            <div class="invalid-feedback">
                                {{ $errors->first('imagenes_pagina_inicial') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.imagenes_pagina_inicial_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="texto_superior_pagina_1">{{ trans('cruds.totem.fields.texto_superior_pagina_1') }}</label>
                        <textarea class="form-control {{ $errors->has('texto_superior_pagina_1') ? 'is-invalid' : '' }}" name="texto_superior_pagina_1" id="texto_superior_pagina_1">{{ old('texto_superior_pagina_1', $totem->texto_superior_pagina_1) }}</textarea>
                        @if($errors->has('texto_superior_pagina_1'))
                            <div class="invalid-feedback">
                                {{ $errors->first('texto_superior_pagina_1') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.texto_superior_pagina_2_helper') }}</span>
                    </div>
                    
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <b>Pagina LLamada</b>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="imagen_pagina_llamada">{{ trans('cruds.totem.fields.imagen_pagina_llamada') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('imagen_pagina_llamada') ? 'is-invalid' : '' }}" id="imagen_pagina_llamada-dropzone">
                        </div>
                        @if($errors->has('imagen_pagina_llamada'))
                            <div class="invalid-feedback">
                                {{ $errors->first('imagen_pagina_llamada') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="pagina_llamada_texto_superior">{{ trans('cruds.totem.fields.pagina_llamada_texto_superior') }}</label>
                        <textarea class="form-control {{ $errors->has('pagina_llamada_texto_superior') ? 'is-invalid' : '' }}" name="pagina_llamada_texto_superior" id="pagina_llamada_texto_superior">{{ old('pagina_llamada_texto_superior', $totem->pagina_llamada_texto_superior) }}</textarea>
                        @if($errors->has('pagina_llamada_texto_superior'))
                            <div class="invalid-feedback">
                                {{ $errors->first('pagina_llamada_texto_superior') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="pagina_llamada_texto_inferior">{{ trans('cruds.totem.fields.pagina_llamada_texto_inferior') }}</label>
                        <textarea class="form-control {{ $errors->has('pagina_llamada_texto_inferior') ? 'is-invalid' : '' }}" name="pagina_llamada_texto_inferior" id="pagina_llamada_texto_inferior">{{ old('pagina_llamada_texto_inferior', $totem->pagina_llamada_texto_inferior) }}</textarea>
                        @if($errors->has('pagina_llamada_texto_inferior'))
                            <div class="invalid-feedback">
                                {{ $errors->first('pagina_llamada_texto_inferior') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="pagina_llamada_texto_boton">{{ trans('cruds.totem.fields.pagina_llamada_texto_boton') }}</label>
                        <textarea class="form-control {{ $errors->has('pagina_llamada_texto_boton') ? 'is-invalid' : '' }}" name="pagina_llamada_texto_boton" id="pagina_llamada_texto_boton" style="min-height:30px !important">{{ old('pagina_llamada_texto_boton', $totem->pagina_llamada_texto_boton) }}</textarea>
                        @if($errors->has('pagina_llamada_texto_boton'))
                            <div class="invalid-feedback">
                                {{ $errors->first('pagina_llamada_texto_boton') }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
            <!-- Pagina tres --->
            <div class="card">
                <div class="card-header">
                    <b>Pagina Dos</b>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check {{ $errors->has('mostrar_pagina_2') ? 'is-invalid' : '' }}">
                            <input type="hidden" name="mostrar_pagina_2" value="0">
                            <input class="form-check-input" type="checkbox" name="mostrar_pagina_2" id="mostrar_pagina_2" value="1" {{ $totem->mostrar_pagina_2 || old('mostrar_pagina_2', 0) === 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="mostrar_pagina_2">{{ trans('cruds.totem.fields.mostrar_pagina_2') }}</label>
                        </div>
                        @if($errors->has('mostrar_pagina_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('mostrar_pagina_2') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.mostrar_pagina_2_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="imagen_pagina_2">{{ trans('cruds.totem.fields.imagen_pagina_2') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('imagen_pagina_2') ? 'is-invalid' : '' }}" id="imagen_pagina_2-dropzone">
                        </div>
                        @if($errors->has('imagen_pagina_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('imagen_pagina_2') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.imagen_pagina_2_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="texto_superior_pagina_2">{{ trans('cruds.totem.fields.texto_superior_pagina_2') }}</label>
                        <textarea class="form-control {{ $errors->has('texto_superior_pagina_2') ? 'is-invalid' : '' }}" name="texto_superior_pagina_2" id="texto_superior_pagina_2">{{ old('texto_superior_pagina_2', $totem->texto_superior_pagina_2) }}</textarea>
                        @if($errors->has('texto_superior_pagina_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('texto_superior_pagina_2') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.texto_superior_pagina_2_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="texto_inferior_pagina_2">{{ trans('cruds.totem.fields.texto_inferior_pagina_2') }}</label>
                        <textarea class="form-control {{ $errors->has('texto_inferior_pagina_2') ? 'is-invalid' : '' }}" name="texto_inferior_pagina_2" id="texto_inferior_pagina_2">{{ old('texto_inferior_pagina_2', $totem->texto_inferior_pagina_2) }}</textarea>
                        @if($errors->has('texto_inferior_pagina_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('texto_inferior_pagina_2') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.texto_inferior_pagina_2_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('cruds.totem.fields.fuente_spinner') }}</label>
                        <select class="form-control {{ $errors->has('fuente_spinner') ? 'is-invalid' : '' }}" name="fuente_spinner" id="fuente_spinner">
                            <option value disabled {{ old('fuente_spinner', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Totem::FUENTE_SPINNER_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('fuente_spinner', $totem->fuente_spinner) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('fuente_spinner'))
                            <div class="invalid-feedback">
                                {{ $errors->first('fuente_spinner') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.fuente_spinner_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="spinner">{{ trans('cruds.totem.fields.spinner') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('spinner') ? 'is-invalid' : '' }}" id="spinner-dropzone">
                        </div>
                        @if($errors->has('spinner'))
                            <div class="invalid-feedback">
                                {{ $errors->first('spinner') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.spinner_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <b>Pagina Tres</b>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.totem.fields.fuente_imagenes') }}</label>
                        <select class="form-control {{ $errors->has('fuente_imagenes') ? 'is-invalid' : '' }}" name="fuente_imagenes" id="fuente_imagenes" required>
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
                        <div class="needsclick dropzone {{ $errors->has('imagenes') ? 'is-invalid' : '' }}" id="imagenes-dropzone">
                        </div>
                        @if($errors->has('imagenes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('imagenes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.totem.fields.imagenes_helper') }}</span>
                    </div>
                </div>
            </div>

            
            
            
            <div class="form-group">
                <label for="texto_inferior">{{ trans('cruds.totem.fields.texto_inferior') }}</label>
                <textarea class="form-control {{ $errors->has('texto_inferior') ? 'is-invalid' : '' }}" name="texto_inferior" id="texto_inferior">{{ old('texto_inferior', $totem->texto_inferior) }}</textarea>
                @if($errors->has('texto_inferior'))
                    <div class="invalid-feedback">
                        {{ $errors->first('texto_inferior') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="comentarios">{{ trans('cruds.totem.fields.comentarios') }}</label>
                <textarea class="form-control {{ $errors->has('comentarios') ? 'is-invalid' : '' }}" name="comentarios" id="comentarios">{{ old('comentarios', $totem->comentarios) }}</textarea>
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



@endsection

@section('scripts')
<script>
    var uploadedImagenesPaginaInicialMap = {}
Dropzone.options.imagenesPaginaInicialDropzone = {
    url: '{{ route('admin.totems.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
      $('form').append('<input type="hidden" name="imagenes_pagina_inicial[]" value="' + response.name + '">')
      uploadedImagenesPaginaInicialMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedImagenesPaginaInicialMap[file.name]
      }
      $('form').find('input[name="imagenes_pagina_inicial[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($totem) && $totem->imagenes_pagina_inicial)
      var files = {!! json_encode($totem->imagenes_pagina_inicial) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="imagenes_pagina_inicial[]" value="' + file.file_name + '">')
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
<script>
    var uploadedImagenesMap = {}
Dropzone.options.imagenesDropzone = {
    url: '{{ route('admin.totems.storeMedia') }}',
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
<script>
    Dropzone.options.spinnerDropzone = {
    url: '{{ route('admin.totems.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="spinner"]').remove()
      $('form').append('<input type="hidden" name="spinner" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="spinner"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($totem) && $totem->spinner)
      var file = {!! json_encode($totem->spinner) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="spinner" value="' + file.file_name + '">')
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
    Dropzone.options.imagenPagina2Dropzone = {
    url: '{{ route('admin.totems.storeMedia') }}',
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
      $('form').find('input[name="imagen_pagina_2"]').remove()
      $('form').append('<input type="hidden" name="imagen_pagina_2" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_pagina_2"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($totem) && $totem->imagen_pagina_2)
      var file = {!! json_encode($totem->imagen_pagina_2) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_pagina_2" value="' + file.file_name + '">')
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
    Dropzone.options.imagenPaginaLlamadaDropzone = {
    url: '{{ route('admin.totems.storeMedia') }}',
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
      $('form').find('input[name="imagen_pagina_llamada"]').remove()
      $('form').append('<input type="hidden" name="imagen_pagina_llamada" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="imagen_pagina_llamada"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($totem) && $totem->imagen_pagina_llamada)
      var file = {!! json_encode($totem->imagen_pagina_llamada) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="imagen_pagina_llamada" value="' + file.file_name + '">')
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