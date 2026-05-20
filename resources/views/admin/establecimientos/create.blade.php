@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.create') }} {{ trans('cruds.establecimiento.title_singular') }}
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route("admin.establecimientos.store") }}" enctype="multipart/form-data">
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
                            <div class="row">
                                <div class="col-6">
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
                                </div>

                                <div class="col-3">
                                <div class="form-group">
                                    <label for="remote_hotel_id">Proveedor Cerradura</label>
                                    {!!
                                        UtilService::drowDownList('proveedor_cerradura', $proveedorCerraduraList, '',
                                            ['id' => 'proveedor_cerradura', 'class' => 'form-control',
                                            'placeholder' => 'Seleccione...' ]
                                        )
                                    !!}
                                </div>
                            </div>
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
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label >NIF:</label>
                                    <input class="form-control" type="text" name="nif" id="nif" value="{{ old('nif', '') }}" >
                                </div>
                                <div class="col-md-6 form-group">
                                    <label >Dirección:</label>
                                    <input class="form-control" type="text" name="direccion" id="direccion" value="{{ old('direccion', '') }}" >
                                </div>
                                <div class="col-md-6 form-group">
                                    <label >Ciudad:</label>
                                    <input class="form-control" type="text" name="ciudad" id="ciudad" value="{{ old('ciudad', '') }}" >
                                </div>
                                <div class="col-md-6 form-group">
                                    <label >ZIP:</label>
                                    <input class="form-control" type="text" name="zip" id="zip" value="{{ old('zip', '') }}" >
                                </div>
                                <div class="col-md-6 form-group">
                                    <label >Categoria Hotel:</label>
                                    <input class="form-control" type="text" name="categoria" id="categoria" value="{{ old('categoria', '') }}" >
                                </div>
                             </div>

                            <div class="form-group">
                                <label for="extensiones">Extensiones</label>
                                <textarea class="form-control" name="extensiones" id="extensiones" rows="2">{{ old('extensiones', '') }}</textarea>
                                @if($errors->has('extensiones'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('extensiones') }}
                                    </div>
                                @endif
                                <span class="help-block">Introduce extensiones separadas por punto y coma (;). Ejemplo: 100;101;102</span>
                            </div>
                            <div class="form-group">
                                <label class="required" for="logo_establecimiento">{{ trans('cruds.establecimiento.fields.logo_establecimiento') }}</label>
                                <div class="needsclick dropzone {{ $errors->has('logo_establecimiento') ? 'is-invalid' : '' }}" id="logo_establecimiento-dropzone">
                                </div>
                                @if($errors->has('logo_establecimiento'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('logo_establecimiento') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.establecimiento.fields.logo_establecimiento_helper') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="mensaje_conectado">{{ trans('cruds.establecimiento.fields.mensaje_conectado') }}</label>
                                <textarea class="form-control {{ $errors->has('mensaje_conectado') ? 'is-invalid' : '' }}" name="mensaje_conectado" id="mensaje_conectado">{{ old('mensaje_conectado') }}</textarea>
                                @if($errors->has('mensaje_conectado'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('mensaje_conectado') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.establecimiento.fields.mensaje_conectado_helper') }}</span>
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
                            <hr />
                            <div class="row">
                                <div class="form-group col-3">
                                    <label for="api_pms">{{ trans('cruds.establecimiento.fields.api_pms') }}</label>
                                    <input class="form-control {{ $errors->has('api_pms') ? 'is-invalid' : '' }}" type="text" name="api_pms" id="api_pms" value="{{ old('api_pms', '') }}">
                                    @if($errors->has('api_pms'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('api_pms') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_helper') }}</span>
                                </div>
                                <div class="form-group col-6">
                                    <label for="api_pms_url">{{ trans('cruds.establecimiento.fields.api_pms_url') }}</label>
                                    <input class="form-control {{ $errors->has('api_pms_url') ? 'is-invalid' : '' }}" type="text" name="api_pms_url" id="api_pms_url" value="{{ old('api_pms_url', '') }}">
                                    @if($errors->has('api_pms_url'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('api_pms_url') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_url_helper') }}</span>
                                </div>
                                <div class="form-group col-3">
                                        <label for="remote_hotel_id">Remote Hotel ID</label>
                                        <input class="form-control" type="text" name="remote_hotel_id" id="remote_hotel_id" value="{{ old('remote_hotel_id', '') }}" >
                                        @if($errors->has('remote_hotel_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('remote_hotel_id') }}
                                            </div>
                                        @endif
                                    </div>
                                <div class="form-group col-6">
                                    <label for="api_pms_username">{{ trans('cruds.establecimiento.fields.api_pms_username') }}</label>
                                    <input class="form-control {{ $errors->has('api_pms_username') ? 'is-invalid' : '' }}" type="text" name="api_pms_username" id="api_pms_username" value="{{ old('api_pms_username', '') }}">
                                    @if($errors->has('api_pms_username'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('api_pms_username') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_username_helper') }}</span>
                                </div>
                                <div class="form-group col-6">
                                    <label for="api_pms_password">{{ trans('cruds.establecimiento.fields.api_pms_password') }}</label>
                                    <input class="form-control {{ $errors->has('api_pms_password') ? 'is-invalid' : '' }}" type="text" name="api_pms_password" id="api_pms_password" value="{{ old('api_pms_password', '') }}">
                                    @if($errors->has('api_pms_password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('api_pms_password') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_password_helper') }}</span>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="tour_images">{{ trans('cruds.establecimiento.fields.tour_images') }}</label>
                                <div class="needsclick dropzone" id="tour_images-dropzone">
                                </div>
                                @if($errors->has('tour_images'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('tour_images') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.establecimiento.fields.tour_images_helper') }}</span>
                            </div>-->

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
    Dropzone.options.logoEstablecimientoDropzone = {
    url: '{{ route('admin.establecimientos.storeMedia') }}',
    maxFilesize: 4, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 4,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="logo_establecimiento"]').remove()
      $('form').append('<input type="hidden" name="logo_establecimiento" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="logo_establecimiento"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($establecimiento) && $establecimiento->logo_establecimiento)
      var file = {!! json_encode($establecimiento->logo_establecimiento) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="logo_establecimiento" value="' + file.file_name + '">')
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
    <script>
        var uploadedTourImagesMap = {}
        Dropzone.options.tourImagesDropzone = {
            url: '{{ route('frontend.establecimientos.storeMedia') }}',
            maxFilesize: 4, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 4,
                width: 4096,
                height: 4096
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="tour_images[]" value="' + response.name + '">')
                uploadedTourImagesMap[file.name] = response.name
            },
            removedfile: function (file) {
                console.log(file)
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedTourImagesMap[file.name]
                }
                $('form').find('input[name="tour_images[]"][value="' + name + '"]').remove()
            },
            init: function () {
                @if(isset($establecimiento) && $establecimiento->tour_images)
                var files = {!! json_encode($establecimiento->tour_images) !!}
                for (var i in files) {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="tour_images[]" value="' + file.file_name + '">')
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
            url: '{{ route('frontend.establecimientos.storeMedia') }}',
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
                @if(isset($establecimiento) && $establecimiento->spinner)
                var file = {!! json_encode($establecimiento->spinner) !!}
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
@endsection
