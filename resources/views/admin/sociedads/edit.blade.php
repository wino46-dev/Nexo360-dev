<?php
use App\Models\Sociedad;

$show_btns_capture_document_list = Sociedad::show_btns_capture_document_list();

?>
@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.sociedad.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sociedads.update", [$sociedad->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="codigo">{{ trans('cruds.sociedad.fields.codigo') }}</label>
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', $sociedad->codigo) }}" required>
                @if($errors->has('codigo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.sociedad.fields.codigo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.sociedad.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $sociedad->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.sociedad.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="imagenes">{{ trans('cruds.sociedad.fields.imagenes') }}</label>
                <div class="needsclick dropzone {{ $errors->has('imagenes') ? 'is-invalid' : '' }}" id="imagenes-dropzone">
                </div>
                @if($errors->has('imagenes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('imagenes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.sociedad.fields.imagenes_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="imagenes_pagina_1">Carrousel Página 1</label>
                <div class="needsclick dropzone {{ $errors->has('imagenes_pagina_1') ? 'is-invalid' : '' }}" id="imagenes_pagina_1-dropzone">
                </div>
                @if($errors->has('imagenes_pagina_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('imagenes_pagina_1') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="pago_html_inferior">{{ trans('cruds.sociedad.fields.pago_html_inferior') }}</label>
                <textarea class="form-control {{ $errors->has('pago_html_inferior') ? 'is-invalid' : '' }}" name="pago_html_inferior" id="pago_html_inferior">{{ old('pago_html_inferior', $sociedad->pago_html_inferior) }}</textarea>
            </div>
            <div class="form-group">
                <label for="parte_viajero_html">{{ trans('cruds.sociedad.fields.parte_viajero_html') }} (legacy)</label>
                <textarea class="form-control {{ $errors->has('parte_viajero_html') ? 'is-invalid' : '' }}" name="parte_viajero_html" id="parte_viajero_html">{{ old('parte_viajero_html', $sociedad->parte_viajero_html) }}</textarea>
                <span class="help-block">Texto legal por defecto. Se usará como último recurso si no hay versión ES/EN configurada.</span>
            </div>
            <div class="form-group">
                <label for="parte_viajero_html_es">Parte de viajeros (ES)</label>
                <textarea class="form-control {{ $errors->has('parte_viajero_html_es') ? 'is-invalid' : '' }}" name="parte_viajero_html_es" id="parte_viajero_html_es">{{ old('parte_viajero_html_es', $sociedad->parte_viajero_html_es) }}</textarea>
            </div>
            <div class="form-group">
                <label for="parte_viajero_html_en">Travellers form (EN)</label>
                <textarea class="form-control {{ $errors->has('parte_viajero_html_en') ? 'is-invalid' : '' }}" name="parte_viajero_html_en" id="parte_viajero_html_en">{{ old('parte_viajero_html_en', $sociedad->parte_viajero_html_en) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info" style="color:#0c5460;background:#d1ecf1;border-color:#bee5eb">
                        <strong>Ayuda para textos del Parte de Viajeros</strong>
                        <ul style="margin-top:8px">
                            <li>Orden de uso: EN (si el idioma activo es ingles) - ES - texto legacy.</li>
                            <li>Formato: puedes introducir HTML basico (parrafos, listas, enlaces). Se renderiza tal cual en el PDF.</li>
                            <li>Variables: estos textos no procesan variables. Para textos dinamicos, personaliza la cabecera por Sociedad en: resources/views/admin/pdf/sociedades/{sociedad_id}/parte_header.blade.php. Si no existe, se usa la generica.</li>
                        </ul>
                        <p style="margin:6px 0 0 0"><small>En la cabecera personalizada tendras disponibles: establecimiento, sociedad, reservation y checkin. Puedes copiar como base el archivo: resources/views/admin/pdf/parte_header.blade.php</small></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="remote_hotel_id">Botones Captura Documento</label>
                        {!! UtilService::drowDownList(
                            'show_btns_capture_document',
                            $show_btns_capture_document_list,
                            $sociedad->show_btns_capture_document,
                            ['id'=> 'show_btns_capture_document','class' => 'form-control', 'placeholder' => 'Seleccione...'],
                        ) !!}
                    </div>
                </div>
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
    var uploadedImagenesMap = {}
Dropzone.options.imagenesDropzone = {
    url: '{{ route('admin.sociedads.storeMedia') }}',
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
@if(isset($sociedad) && $sociedad->imagenes)
      var files = {!! json_encode($sociedad->imagenes) !!}
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
    var uploadedImagenesPagina1Map = {}
Dropzone.options.imagenesPagina1Dropzone = {
    url: '{{ route('admin.sociedads.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="imagenes_pagina_1[]" value="' + response.name + '">')
      uploadedImagenesPagina1Map[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedImagenesPagina1Map[file.name]
      }
      $('form').find('input[name="imagenes_pagina_1[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($sociedad) && $sociedad->imagenes_pagina_1)
      var files = {!! json_encode($sociedad->imagenes_pagina_1) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="imagenes_pagina_1[]" value="' + file.file_name + '">')
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
