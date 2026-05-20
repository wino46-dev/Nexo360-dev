@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.edit') }} {{ trans('cruds.establecimiento.title_singular') }}


                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.establecimientos.update', [$establecimiento->id]) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label class="required"
                                        for="sociedad_id">{{ trans('cruds.establecimiento.fields.sociedad') }}</label>
                                    <select class="form-control select2" name="sociedad_id" id="sociedad_id" required>
                                        @foreach ($sociedads as $id => $entry)
                                            <option value="{{ $id }}"
                                                {{ (old('sociedad_id') ? old('sociedad_id') : $establecimiento->sociedad->id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $entry }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('sociedad'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('sociedad') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.establecimiento.fields.sociedad_helper') }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required"
                                                for="codigo">{{ trans('cruds.establecimiento.fields.codigo') }}</label>
                                            <input class="form-control" type="text" name="codigo" id="codigo"
                                                value="{{ old('codigo', $establecimiento->codigo) }}" required>
                                            @if ($errors->has('codigo'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('codigo') }}
                                                </div>
                                            @endif
                                            <span
                                                class="help-block">{{ trans('cruds.establecimiento.fields.codigo_helper') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="remote_hotel_id">Proveedor Cerradura</label>
                                            {!! UtilService::drowDownList(
                                                'proveedor_cerradura',
                                                $proveedorCerraduraList,
                                                $establecimiento->proveedor_cerradura,
                                                ['id' => 'proveedor_cerradura', 'class' => 'form-control', 'placeholder' => 'Seleccione...'],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required"
                                        for="nombre">{{ trans('cruds.establecimiento.fields.nombre') }}</label>
                                    <input class="form-control" type="text" name="nombre" id="nombre"
                                        value="{{ old('nombre', $establecimiento->nombre) }}" required>
                                    @if ($errors->has('nombre'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.establecimiento.fields.nombre_helper') }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>NIF:</label>
                                        <input class="form-control" type="text" name="nif" id="nif"
                                            value="{{ old('nif', $establecimiento->nif) }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Dirección:</label>
                                        <input class="form-control" type="text" name="direccion" id="direccion"
                                            value="{{ old('direccion', $establecimiento->direccion) }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Ciudad:</label>
                                        <input class="form-control" type="text" name="ciudad" id="ciudad"
                                            value="{{ old('ciudad', $establecimiento->ciudad) }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>ZIP:</label>
                                        <input class="form-control" type="text" name="zip" id="zip"
                                            value="{{ old('zip', $establecimiento->zip) }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Categoria Hotel:</label>
                                        <input class="form-control" type="text" name="categoria" id="categoria"
                                            value="{{ old('categoria', $establecimiento->categoria) }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="extensiones">Extensiones</label>
                                    <textarea class="form-control" name="extensiones" id="extensiones" rows="2">{{ old('extensiones', $establecimiento->extensiones) }}</textarea>
                                    @if ($errors->has('extensiones'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('extensiones') }}
                                        </div>
                                    @endif
                                    <span class="help-block">Introduce extensiones separadas por punto y coma (;). Ejemplo: 100;101;102</span>
                                </div>
                                <div class="form-group">
                                    <label class="required"
                                        for="logo_establecimiento">{{ trans('cruds.establecimiento.fields.logo_establecimiento') }}</label>
                                    <div class="needsclick dropzone {{ $errors->has('logo_establecimiento') ? 'is-invalid' : '' }}"
                                        id="logo_establecimiento-dropzone">
                                    </div>
                                    @if ($errors->has('logo_establecimiento'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('logo_establecimiento') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.establecimiento.fields.logo_establecimiento_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="mensaje_conectado">{{ trans('cruds.establecimiento.fields.mensaje_conectado') }}</label>
                                    <br>
                                    <?php
                                    $storing = $establecimiento->mensaje_conectado;
                                    $variables = [
                                        'empleado' => auth()->user()->name,
                                        'codigo_hotel' => $establecimiento->codigo,
                                        'hotel' => $establecimiento->nombre,
                                        //'sociedad' => $establecimiento->sociedad->codigo,
                                    ];

                                    $palabras = explode(' ', $storing);

                                    foreach ($palabras as $key => $palabra) {
                                        if (preg_match('/\[(.*?)\]/', $palabra, $coincidencias)) {
                                            $variable = $coincidencias[1];
                                            if (isset($variables[$variable])) {
                                                $palabras[$key] = $variables[$variable];
                                            }
                                        }
                                    }

                                    $storingModificado = implode(' ', $palabras);

                                    ?>
                                    {!! $storingModificado !!}
                                    <div class="row">
                                        <div class="col-md-8">
                                            <br>
                                            <textarea class="form-control {{ $errors->has('mensaje_conectado') ? 'is-invalid' : '' }}" name="mensaje_conectado"
                                                id="mensaje_conectado">{{ old('mensaje_conectado', $establecimiento->mensaje_conectado) }}</textarea>
                                            @if ($errors->has('mensaje_conectado'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('mensaje_conectado') }}
                                                </div>
                                            @endif
                                            <span
                                                class="help-block">{{ trans('cruds.establecimiento.fields.mensaje_conectado_helper') }}</span>

                                        </div>
                                        <div class="col-md-4" style="color: black;font-weight: initial">
                                            <br>
                                            <p>Variables Permitidas:</p>
                                            <ul>
                                                <li>
                                                    [empleado] => {{ auth()->user()->name }}
                                                </li>
                                                <li>
                                                    [hotel] => {{ $establecimiento->nombre }}
                                                </li>
                                                <li>
                                                    [codigo_hotel] => {{ $establecimiento->codigo }}
                                                </li>

                                            </ul>

                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="imagenes">{{ trans('cruds.establecimiento.fields.imagenes') }}</label>
                                    <div class="needsclick dropzone" id="imagenes-dropzone">
                                    </div>
                                    @if ($errors->has('imagenes'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('imagenes') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.establecimiento.fields.imagenes_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="tour_images"> Carrousel Crosselling</label>
                                    <div class="needsclick dropzone" id="tour_images-dropzone">
                                    </div>
                                    @if ($errors->has('tour_images'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('tour_images') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.establecimiento.fields.tour_images_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="imagenes_pagina_1"> Carrousel Página 1</label>
                                    <div class="needsclick dropzone" id="imagenes_pagina_1-dropzone">
                                    </div>
                                    @if ($errors->has('imagenes_pagina_1'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('imagenes_pagina_1') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div
                                        class="form-check {{ $errors->has('ocultar_header_totem') ? 'is-invalid' : '' }}">
                                        <input type="hidden" name="ocultar_header_totem" value="0">
                                        <input class="form-check-input" type="checkbox" name="ocultar_header_totem"
                                            id="ocultar_header_totem" value="1"
                                            {{ $establecimiento->ocultar_header_totem || old('ocultar_header_totem', 0) === 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ocultar_header_totem">Ocultar Header en el
                                            Totem</label>
                                    </div>
                                    @if ($errors->has('ocultar_header_totem'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('mostrar_pagina_2') }}
                                        </div>
                                    @endif
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label for="departure_time_card">Hora Salida Grabación de Tarjeta </label>
                                        <input class="form-control" type="time" name="departure_time_card"
                                            id="departure_time_card"
                                            value="{{ old('departure_time_card', $establecimiento->departure_time_card) }}">

                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label for="api_pms">{{ trans('cruds.establecimiento.fields.api_pms') }}</label>

                                        {!! UtilService::drowDownList(
                                            'api_pms',
                                            $pms_list,
                                            $establecimiento->api_pms,
                                            ['id' => 'api_pms', 'class' => 'form-control', 'placeholder' => 'Seleccione...'],
                                        ) !!}
                                    </div>
                                    <div class="form-group col-6">
                                        <label
                                            for="api_pms_url">{{ trans('cruds.establecimiento.fields.api_pms_url') }}</label>
                                        <input class="form-control {{ $errors->has('api_pms_url') ? 'is-invalid' : '' }}"
                                            type="text" name="api_pms_url" id="api_pms_url"
                                            value="{{ old('api_pms_url', $establecimiento->api_pms_url) }}">
                                        @if ($errors->has('api_pms_url'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('api_pms_url') }}
                                            </div>
                                        @endif
                                        <span
                                            class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_url_helper') }}</span>
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="remote_hotel_id">Api Remote Hotel ID</label>
                                        <input class="form-control" type="text" name="remote_hotel_id"
                                            id="remote_hotel_id"
                                            value="{{ old('remote_hotel_id', $establecimiento->remote_hotel_id) }}">
                                        @if ($errors->has('remote_hotel_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('remote_hotel_id') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-6 pms-roomdoo-fields" style="{{ $establecimiento->api_pms == 'misterplan' ? 'display:none' : '' }}">
                                        <label
                                            for="api_pms_username">{{ trans('cruds.establecimiento.fields.api_pms_username') }}</label>
                                        <input
                                            class="form-control {{ $errors->has('api_pms_username') ? 'is-invalid' : '' }}"
                                            type="text" name="api_pms_username" id="api_pms_username"
                                            value="{{ old('api_pms_username', $establecimiento->api_pms_username) }}">
                                        @if ($errors->has('api_pms_username'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('api_pms_username') }}
                                            </div>
                                        @endif
                                        <span
                                            class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_username_helper') }}</span>
                                    </div>
                                    <div class="form-group col-6 pms-roomdoo-fields" style="{{ $establecimiento->api_pms == 'misterplan' ? 'display:none' : '' }}">
                                        <label
                                            for="api_pms_password">{{ trans('cruds.establecimiento.fields.api_pms_password') }}</label>
                                        <input
                                            class="form-control {{ $errors->has('api_pms_password') ? 'is-invalid' : '' }}"
                                            type="text" name="api_pms_password" id="api_pms_password"
                                            value="{{ old('api_pms_password', $establecimiento->api_pms_password) }}">
                                        @if ($errors->has('api_pms_password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('api_pms_password') }}
                                            </div>
                                        @endif
                                        <span
                                            class="help-block">{{ trans('cruds.establecimiento.fields.api_pms_password_helper') }}</span>
                                    </div>
                                    <div class="form-group col-6 pms-misterplan-fields" style="{{ $establecimiento->api_pms == 'misterplan' ? '' : 'display:none' }}">
                                        <label for="misterplan_api_key">MisterPlan API Key</label>
                                        <input class="form-control {{ $errors->has('misterplan_api_key') ? 'is-invalid' : '' }}" type="text" name="misterplan_api_key" id="misterplan_api_key" value="{{ old('misterplan_api_key', $establecimiento->misterplan_api_key) }}">
                                        @if ($errors->has('misterplan_api_key'))
                                            <div class="invalid-feedback">{{ $errors->first('misterplan_api_key') }}</div>
                                        @endif
                                        <span class="help-block">Clave del canal para autenticar las llamadas a MisterPlan. Si se deja vacío y existe una clave global en configuración, se usará la global.</span>
                                    </div>
                                    <div class="form-group col-6 pms-misterplan-fields" style="{{ $establecimiento->api_pms == 'misterplan' ? '' : 'display:none' }}">
                                        <label for="misterplan_channel_id">MisterPlan Channel ID</label>
                                        <input class="form-control {{ $errors->has('misterplan_channel_id') ? 'is-invalid' : '' }}" type="text" name="misterplan_channel_id" id="misterplan_channel_id" value="{{ old('misterplan_channel_id', $establecimiento->misterplan_channel_id) }}">
                                        @if ($errors->has('misterplan_channel_id'))
                                            <div class="invalid-feedback">{{ $errors->first('misterplan_channel_id') }}</div>
                                        @endif
                                        <span class="help-block">Identificador de canal (channel_id) que MisterPlan os ha asignado.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="remote_hotel_id">PMS Método Pago Totem</label>
                                            {!! UtilService::drowDownList(
                                                'pms_payment_method_totem',
                                                $metodos_pagos,
                                                $establecimiento->pms_payment_method_totem,
                                                ['id' => 'pms_payment_method_totem', 'class' => 'form-control', 'placeholder' => 'Seleccione...'],
                                            ) !!}
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="remote_hotel_id">PMS Método Pago Manual</label>
                                            {!! UtilService::drowDownList(
                                                'pms_payment_method_manual',
                                                $metodos_pagos,
                                                $establecimiento->pms_payment_method_manual,
                                                ['id' => 'pms_payment_method_manual', 'class' => 'form-control', 'placeholder' => 'Seleccione...'],
                                            ) !!}
                                    </div>
                                </div>


                                <div class="form-group pt-2">
                                    <button class="btn btn-danger px-5" type="submit">
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
                success: function(file, response) {
                    $('form').append('<input type="hidden" name="imagenes[]" value="' + response.name + '">')
                    uploadedImagenesMap[file.name] = response.name
                },
                removedfile: function(file) {
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
                init: function() {
                    @if (isset($establecimiento) && $establecimiento->imagenes)
                        var files = {!! json_encode($establecimiento->imagenes) !!};
                        for (var i in files) {
                            var file = files[i]
                            this.options.addedfile.call(this, file)
                            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                            file.previewElement.classList.add('dz-complete')
                            $('form').append('<input type="hidden" name="imagenes[]" value="' + file.file_name + '">')
                        }
                    @endif
                },
                error: function(file, response) {
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
                success: function(file, response) {
                    $('form').append('<input type="hidden" name="tour_images[]" value="' + response.name + '">')
                    uploadedTourImagesMap[file.name] = response.name
                },
                removedfile: function(file) {
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
                init: function() {
                    @if (isset($establecimiento) && $establecimiento->tour_images)
                        var files = {!! json_encode($establecimiento->tour_images) !!};
                        for (var i in files) {
                            var file = files[i]
                            this.options.addedfile.call(this, file)
                            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                            file.previewElement.classList.add('dz-complete')
                            $('form').append('<input type="hidden" name="tour_images[]" value="' + file.file_name +
                                '">')
                        }
                    @endif
                },
                error: function(file, response) {
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
                success: function(file, response) {
                    $('form').append('<input type="hidden" name="imagenes_pagina_1[]" value="' + response.name + '">')
                    uploadedImagenesPagina1Map[file.name] = response.name
                },
                removedfile: function(file) {
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
                init: function() {
                    @if (isset($establecimiento) && $establecimiento->imagenes_pagina_1)
                        var files = {!! json_encode($establecimiento->imagenes_pagina_1) !!};
                        for (var i in files) {
                            var file = files[i]
                            this.options.addedfile.call(this, file)
                            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                            file.previewElement.classList.add('dz-complete')
                            $('form').append('<input type="hidden" name="imagenes_pagina_1[]" value="' + file
                                .file_name + '">')
                        }
                    @endif
                },
                error: function(file, response) {
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
                success: function(file, response) {
                    $('form').find('input[name="logo_establecimiento"]').remove()
                    $('form').append('<input type="hidden" name="logo_establecimiento" value="' + response.name + '">')
                },
                removedfile: function(file) {
                    file.previewElement.remove()
                    if (file.status !== 'error') {
                        $('form').find('input[name="logo_establecimiento"]').remove()
                        this.options.maxFiles = this.options.maxFiles + 1
                    }
                },
                init: function() {
                    @if (isset($establecimiento) && $establecimiento->logo_establecimiento)
                        var file = {!! json_encode($establecimiento->logo_establecimiento) !!}
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="logo_establecimiento" value="' + file.file_name +
                            '">')
                        this.options.maxFiles = this.options.maxFiles - 1
                    @endif
                },
                error: function(file, response) {
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
                success: function(file, response) {
                    $('form').find('input[name="spinner"]').remove()
                    $('form').append('<input type="hidden" name="spinner" value="' + response.name + '">')
                },
                removedfile: function(file) {
                    file.previewElement.remove()
                    if (file.status !== 'error') {
                        $('form').find('input[name="spinner"]').remove()
                        this.options.maxFiles = this.options.maxFiles + 1
                    }
                },
                init: function() {
                    @if (isset($establecimiento) && $establecimiento->spinner)
                        var file = {!! json_encode($establecimiento->spinner) !!}
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="spinner" value="' + file.file_name + '">')
                        this.options.maxFiles = this.options.maxFiles - 1
                    @endif
                },
                error: function(file, response) {
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
            (function(){ function togglePmsFields(value){var isMister=value==='misterplan';var isRoomdoo=value==='roomdoo';var misterEls=document.querySelectorAll('.pms-misterplan-fields');var roomdooEls=document.querySelectorAll('.pms-roomdoo-fields');misterEls.forEach(function(el){el.style.display=isMister?'':'none';});roomdooEls.forEach(function(el){el.style.display=isRoomdoo?'':'none';});}
                function bindToggle(){var select=document.getElementById('api_pms');if(!select)return;togglePmsFields(select.value);select.addEventListener('change',function(e){togglePmsFields(e.target.value);});if(window.jQuery&&jQuery.fn&&jQuery.fn.select2){jQuery(select).on('select2:select',function(e){var val=(e&&e.params&&e.params.data)?(e.params.data.id||e.params.data.text):select.value;togglePmsFields(val);});}}
                if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',bindToggle);}else{bindToggle();}})();
        </script>
    @endsection
