@extends('layouts.admin')
@section('content')

<div class="card">

    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.sociedad') }}
                    </th>
                    <td>
                        {{ $establecimiento->sociedad->codigo ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.codigo') }}
                    </th>
                    <td>
                        {{ $establecimiento->codigo }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.nombre') }}
                    </th>
                    <td>
                        {{ $establecimiento->nombre }}
                    </td>
                </tr>
                <tr>
                    <th>
                        NIF:
                    </th>
                    <td>
                        {{ $establecimiento->nif }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Dirección:
                    </th>
                    <td>
                        {{ $establecimiento->direccion }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Ciudad:
                    </th>
                    <td>
                        {{ $establecimiento->ciudad }}
                    </td>
                </tr>
                <tr>
                    <th>
                        ZIP:
                    </th>
                    <td>
                        {{ $establecimiento->zip }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Categoría:
                    </th>
                    <td>
                        {{ $establecimiento->categoria }}
                    </td>
                </tr>
                
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.mensaje_conectado') }}
                    </th>
                    <td>
                        {{ $establecimiento->mensaje_conectado }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.imagenes') }}
                    </th>
                    <td>
                        @foreach($establecimiento->imagenes as $key => $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                <img src="{{ $media->getUrl('thumb') }}">
                            </a>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.tour_images') }}
                    </th>
                    <td>
                        @foreach($establecimiento->tour_images as $key => $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                <img src="{{ $media->getUrl('thumb') }}">
                            </a>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        Api PMS ID
                    </th>
                    <td>
                        {{ $establecimiento->api_pms }}
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        Api PMS Url
                    </th>
                    <td>
                        {{ $establecimiento->api_pms_url }}
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        Api Remote Hotel ID
                    </th>
                    <td>
                        {{ $establecimiento->remote_hotel_id }}
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        Api PMS Username
                    </th>
                    <td>
                        {{ $establecimiento->api_pms_username }}
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        Api Pms Password
                    </th>
                    <td>
                        {{ $establecimiento->api_pms_password }}
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        PMS Método Pago Totem
                    </th>
                    <td>
                        {{ $establecimiento->pms_payment_method_totem }}
                    </td>
                </tr>
                <tr>
                    <th class="text-nowrap">
                        PMS Método Pago Manual
                    </th>
                    <td>
                        {{ $establecimiento->pms_payment_method_manual }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.created_at') }}
                    </th>
                    <td>
                        {{ $establecimiento->created_at }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.establecimiento.fields.updated_at') }}
                    </th>
                    <td>
                        {{ $establecimiento->updated_at }}
                    </td>
                </tr>
                </tbody>
            </table>
            @includeIf('admin.establecimientos.relationships.establecimientoTotems', ['totems' => $establecimiento->establecimientoTotems])
            @includeIf('admin.establecimientos.relationships.establecimientoHabitacions', ['habitacions' => $establecimiento->establecimientoHabitacions])

            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.establecimientos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @if(isset($ayudaStepTotem))
                    @can('ayuda_step_totem_edit')
                        <button style="float: right" type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-edit-modal-lg">Editar configuración de ayuda</button>
                    @endcan
                @else
                    @can('ayuda_step_totem_create')
                        <button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Añadir configuración de ayuda</button>
                    @endcan

                @endcan



            </div>
        </div>
    </div>
</div>



@if(isset($ayudaStepTotem))
    @can('ayuda_step_totem_edit')
        <div class="modal fade bd-edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route("admin.ayuda-step-totems.update", [$ayudaStepTotem->id]) }}" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="establecimiento_id" id="establecimiento_id" value="{{$establecimiento->id}}">
                                </div>
                                <div class="form-group">
                                    <label for="eventos_push">{{ trans('cruds.ayudaStepTotem.fields.eventos_push') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('eventos_push') ? 'is-invalid' : '' }}" name="eventos_push" id="eventos_push">{!! old('eventos_push', $ayudaStepTotem->eventos_push) !!}</textarea>
                                    @if($errors->has('eventos_push'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('eventos_push') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.eventos_push_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="pase_imagenes">{{ trans('cruds.ayudaStepTotem.fields.pase_imagenes') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('pase_imagenes') ? 'is-invalid' : '' }}" name="pase_imagenes" id="pase_imagenes">{!! old('pase_imagenes', $ayudaStepTotem->pase_imagenes) !!}</textarea>
                                    @if($errors->has('pase_imagenes'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pase_imagenes') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.pase_imagenes_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="captura_documentos">{{ trans('cruds.ayudaStepTotem.fields.captura_documentos') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('captura_documentos') ? 'is-invalid' : '' }}" name="captura_documentos" id="captura_documentos">{!! old('captura_documentos', $ayudaStepTotem->captura_documentos) !!}</textarea>
                                    @if($errors->has('captura_documentos'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('captura_documentos') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.captura_documentos_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="pago_reserva">{{ trans('cruds.ayudaStepTotem.fields.pago_reserva') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('pago_reserva') ? 'is-invalid' : '' }}" name="pago_reserva" id="pago_reserva">{!! old('pago_reserva', $ayudaStepTotem->pago_reserva) !!}</textarea>
                                    @if($errors->has('pago_reserva'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pago_reserva') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.pago_reserva_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="grabacion_tarjeta">{{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('grabacion_tarjeta') ? 'is-invalid' : '' }}" name="grabacion_tarjeta" id="grabacion_tarjeta">{!! old('grabacion_tarjeta', $ayudaStepTotem->grabacion_tarjeta) !!}</textarea>
                                    @if($errors->has('grabacion_tarjeta'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('grabacion_tarjeta') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="parte_viajero">{{ trans('cruds.ayudaStepTotem.fields.parte_viajero') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('parte_viajero') ? 'is-invalid' : '' }}" name="parte_viajero" id="parte_viajero">{!! old('parte_viajero', $ayudaStepTotem->parte_viajero) !!}</textarea>
                                    @if($errors->has('parte_viajero'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('parte_viajero') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.parte_viajero_helper') }}</span>
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
    @endcan
@else
    @can('ayuda_step_totem_create')
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route("admin.ayuda-step-totems.store") }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="establecimiento_id" id="establecimiento_id" value="{{$establecimiento->id}}">
                                </div>
                                <div class="form-group">
                                    <label for="eventos_push">{{ trans('cruds.ayudaStepTotem.fields.eventos_push') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('eventos_push') ? 'is-invalid' : '' }}" name="eventos_push" id="eventos_push">{!! old('eventos_push') !!}</textarea>
                                    @if($errors->has('eventos_push'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('eventos_push') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.eventos_push_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="pase_imagenes">{{ trans('cruds.ayudaStepTotem.fields.pase_imagenes') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('pase_imagenes') ? 'is-invalid' : '' }}" name="pase_imagenes" id="pase_imagenes">{!! old('pase_imagenes') !!}</textarea>
                                    @if($errors->has('pase_imagenes'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pase_imagenes') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.pase_imagenes_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="captura_documentos">{{ trans('cruds.ayudaStepTotem.fields.captura_documentos') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('captura_documentos') ? 'is-invalid' : '' }}" name="captura_documentos" id="captura_documentos">{!! old('captura_documentos') !!}</textarea>
                                    @if($errors->has('captura_documentos'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('captura_documentos') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.captura_documentos_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="pago_reserva">{{ trans('cruds.ayudaStepTotem.fields.pago_reserva') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('pago_reserva') ? 'is-invalid' : '' }}" name="pago_reserva" id="pago_reserva">{!! old('pago_reserva') !!}</textarea>
                                    @if($errors->has('pago_reserva'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pago_reserva') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.pago_reserva_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="grabacion_tarjeta">{{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('grabacion_tarjeta') ? 'is-invalid' : '' }}" name="grabacion_tarjeta" id="grabacion_tarjeta">{!! old('grabacion_tarjeta') !!}</textarea>
                                    @if($errors->has('grabacion_tarjeta'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('grabacion_tarjeta') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="parte_viajero">{{ trans('cruds.ayudaStepTotem.fields.parte_viajero') }}</label>
                                    <textarea class="form-control ckeditor {{ $errors->has('parte_viajero') ? 'is-invalid' : '' }}" name="parte_viajero" id="parte_viajero">{!! old('parte_viajero') !!}</textarea>
                                    @if($errors->has('parte_viajero'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('parte_viajero') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.ayudaStepTotem.fields.parte_viajero_helper') }}</span>
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
    @endcan
@endif



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
                                        xhr.open('POST', '{{ route('admin.ayuda-step-totems.storeCKEditorImages') }}', true);
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
                                        data.append('crud_id', '{{ $ayudaStepTotem->id ?? 0 }}');
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
