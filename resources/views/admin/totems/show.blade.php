@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.id') }}
                        </th>
                        <td>
                            {{ $totem->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $totem->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.codigo') }}
                        </th>
                        <td>
                            {{ $totem->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.fuente_imagenes') }}
                        </th>
                        <td>
                            {{ App\Models\Totem::FUENTE_IMAGENES_SELECT[$totem->fuente_imagenes] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.imagenes') }}
                        </th>
                        <td>
                            @foreach($totem->imagenes as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl() }}" width="300px" height="200px">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.created_at') }}
                        </th>
                        <td>
                            {{ $totem->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.comentarios') }}
                        </th>
                        <td>
                            {{ $totem->comentarios }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.totem.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $totem->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>

            @includeIf('admin.totems.relationships.totemUsers', ['users' => $totem->totemUsers])
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.totems.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                @can('configuracion_tpv_create')
                        <!-- Button trigger modal Crear -->
                        @if(!isset($configuracionTpv->id))
                            <button type="button" style="float: right;margin-left: 20px;" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCreate">
                                Configuración TPV
                            </button>
                        @endif
                @endcan

                @can('configuracion_tpv_edit')
                    <!-- Button trigger modal Editar -->
                    @if(isset($configuracionTpv->id))
                        <button type="button" style="float: right;margin-left: 20px;" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalEdit">
                            Configuración TPV
                        </button>
                    @endif
                @endcan

                @can('configuracion_video_create')
                    <!-- Button trigger modal Crear -->
                    @if(!isset($configuracionVideo->id))
                        <button type="button" style="float: right;margin-left: 20px;" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCreateVideo">
                            Configuración VIDEO
                        </button>
                    @endif
                @endcan

                @can('configuracion_video_edit')
                    <!-- Button trigger modal Editar -->
                    @if(isset($configuracionVideo->id))
                        <button type="button" style="float: right;margin-left: 20px;" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalEditVideo">
                            Configuración VIDEO
                        </button>
                    @endif
                @endcan

                @can('configuracion_grabador_create')
                    <!-- Button trigger modal Crear -->
                    @if(!isset($configuracionGrabador->id))
                        <!-- Button trigger modal -->
                        <button type="button" style="float: right" class="btn btn-success" data-toggle="modal" data-target="#exampleModal1">
                            Configuración Grabador
                        </button>
                    @endif
                @endcan

                @can('configuracion_grabador_edit')
                    <!-- Button trigger modal Editar -->
                    @if(isset($configuracionGrabador->id))
                        <button type="button" style="float: right" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2">
                            Configuración Grabador
                        </button>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</div>



<!-- Modal Crear Configuracion TPV -->
@if(!isset($configuracionTpv->id))
<div class="modal fade" id="exampleModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Configuración TPV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route("admin.configuracion-tpvs.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="required" for="totem_id">{{ trans('cruds.configuracionTpv.fields.totem') }}</label>
                        <label class="form-control"><b>Totem ID: </b>{{$totem->id}}</label>
                        <input type="hidden" name="totem_id" id="totem_id" value="{{ $totem->id }}">

                        @if($errors->has('totem'))
                            <div class="invalid-feedback">
                                {{ $errors->first('totem') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.totem_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="comercio">{{ trans('cruds.configuracionTpv.fields.comercio') }}</label>
                        <input class="form-control {{ $errors->has('comercio') ? 'is-invalid' : '' }}" type="text" name="comercio" id="comercio" value="{{ old('comercio', '') }}" required>
                        @if($errors->has('comercio'))
                            <div class="invalid-feedback">
                                {{ $errors->first('comercio') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.comercio_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="terminal">{{ trans('cruds.configuracionTpv.fields.terminal') }}</label>
                        <input class="form-control {{ $errors->has('terminal') ? 'is-invalid' : '' }}" type="text" name="terminal" id="terminal" value="{{ old('terminal', '') }}" required>
                        @if($errors->has('terminal'))
                            <div class="invalid-feedback">
                                {{ $errors->first('terminal') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.terminal_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="clave_firma">{{ trans('cruds.configuracionTpv.fields.clave_firma') }}</label>
                        <input class="form-control {{ $errors->has('clave_firma') ? 'is-invalid' : '' }}" type="text" name="clave_firma" id="clave_firma" value="{{ old('clave_firma', '') }}" required>
                        @if($errors->has('clave_firma'))
                            <div class="invalid-feedback">
                                {{ $errors->first('clave_firma') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.clave_firma_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="conf_puerto">{{ trans('cruds.configuracionTpv.fields.conf_puerto') }}</label>
                        <input class="form-control {{ $errors->has('conf_puerto') ? 'is-invalid' : '' }}" type="text" name="conf_puerto" id="conf_puerto" value="{{ old('conf_puerto', '') }}" required>
                        @if($errors->has('conf_puerto'))
                            <div class="invalid-feedback">
                                {{ $errors->first('conf_puerto') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.conf_puerto_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="version">{{ trans('cruds.configuracionTpv.fields.version') }}</label>
                        <input class="form-control {{ $errors->has('version') ? 'is-invalid' : '' }}" type="text" name="version" id="version" value="{{ old('version', '') }}" required>
                        @if($errors->has('version'))
                            <div class="invalid-feedback">
                                {{ $errors->first('version') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.version_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif


<!-- Modal Editar Configuracion  TPV -->
@if(isset($configuracionTpv->id))
<div class="modal fade" id="exampleModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Configuración TPV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route("admin.configuracion-tpvs.update", [$configuracionTpv->id]) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label class="required" for="comercio">{{ trans('cruds.configuracionTpv.fields.comercio') }}</label>
                        <input class="form-control {{ $errors->has('comercio') ? 'is-invalid' : '' }}" type="text" name="comercio" id="comercio" value="{{ old('comercio', $configuracionTpv->comercio) }}" required>
                        @if($errors->has('comercio'))
                            <div class="invalid-feedback">
                                {{ $errors->first('comercio') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.comercio_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="terminal">{{ trans('cruds.configuracionTpv.fields.terminal') }}</label>
                        <input class="form-control {{ $errors->has('terminal') ? 'is-invalid' : '' }}" type="text" name="terminal" id="terminal" value="{{ old('terminal', $configuracionTpv->terminal) }}" required>
                        @if($errors->has('terminal'))
                            <div class="invalid-feedback">
                                {{ $errors->first('terminal') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.terminal_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="clave_firma">{{ trans('cruds.configuracionTpv.fields.clave_firma') }}</label>
                        <input class="form-control {{ $errors->has('clave_firma') ? 'is-invalid' : '' }}" type="text" name="clave_firma" id="clave_firma" value="{{ old('clave_firma', $configuracionTpv->clave_firma) }}" required>
                        @if($errors->has('clave_firma'))
                            <div class="invalid-feedback">
                                {{ $errors->first('clave_firma') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.clave_firma_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="conf_puerto">{{ trans('cruds.configuracionTpv.fields.conf_puerto') }}</label>
                        <input class="form-control {{ $errors->has('conf_puerto') ? 'is-invalid' : '' }}" type="text" name="conf_puerto" id="conf_puerto" value="{{ old('conf_puerto', $configuracionTpv->conf_puerto) }}" required>
                        @if($errors->has('conf_puerto'))
                            <div class="invalid-feedback">
                                {{ $errors->first('conf_puerto') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.conf_puerto_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="version">{{ trans('cruds.configuracionTpv.fields.version') }}</label>
                        <input class="form-control {{ $errors->has('version') ? 'is-invalid' : '' }}" type="text" name="version" id="version" value="{{ old('version', $configuracionTpv->version) }}" required>
                        @if($errors->has('version'))
                            <div class="invalid-feedback">
                                {{ $errors->first('version') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.configuracionTpv.fields.version_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif





<!-- Modal Crear Configuracion Video -->
@if(!isset($configuracionVideo->id))
    <div class="modal fade" id="exampleModalCreateVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Configuración VIDEO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route("admin.configuracion-videos.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="totem_id" id="totem_id" value="{{ $totem->id }}">
                            @if($errors->has('totem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('totem') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.totem_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sip_identity">{{ trans('cruds.configuracionVideo.fields.sip_identity') }}</label>
                            <input class="form-control {{ $errors->has('sip_identity') ? 'is-invalid' : '' }}" type="text" name="sip_identity" id="sip_identity" value="{{ old('sip_identity', '') }}" required>
                            @if($errors->has('sip_identity'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sip_identity') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_identity_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="display_name">{{ trans('cruds.configuracionVideo.fields.display_name') }}</label>
                            <input class="form-control {{ $errors->has('display_name') ? 'is-invalid' : '' }}" type="text" name="display_name" id="display_name" value="{{ old('display_name', '') }}" required>
                            @if($errors->has('display_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('display_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.display_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sip_registar">{{ trans('cruds.configuracionVideo.fields.sip_registar') }}</label>
                            <input class="form-control {{ $errors->has('sip_registar') ? 'is-invalid' : '' }}" type="text" name="sip_registar" id="sip_registar" value="{{ old('sip_registar', '') }}" required>
                            @if($errors->has('sip_registar'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sip_registar') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_registar_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="username">{{ trans('cruds.configuracionVideo.fields.username') }}</label>
                            <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" id="username" value="{{ old('username', '') }}" required>
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.username_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="password">{{ trans('cruds.configuracionVideo.fields.password') }}</label>
                            <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="text" name="password" id="password" value="{{ old('password', '') }}" required>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.password_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sip_identity_destino">{{ trans('cruds.configuracionVideo.fields.sip_identity_destino') }}</label>
                            <input class="form-control {{ $errors->has('sip_identity_destino') ? 'is-invalid' : '' }}" type="text" name="sip_identity_destino" id="sip_identity_destino" value="{{ old('sip_identity_destino', '') }}">
                            @if($errors->has('sip_identity_destino'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sip_identity_destino') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_identity_destino_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endif


<!-- Modal Editar Configuracion  Video -->
@if(isset($configuracionVideo->id))
    <div class="modal fade" id="exampleModalEditVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Configuración VIDEO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route("admin.configuracion-videos.update", [$configuracionVideo->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                        <input type="hidden" name="totem_id" id="totem_id" value="{{ $totem->id }}">

                        @if($errors->has('totem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('totem') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.totem_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sip_identity">{{ trans('cruds.configuracionVideo.fields.sip_identity') }}</label>
                            <input class="form-control {{ $errors->has('sip_identity') ? 'is-invalid' : '' }}" type="text" name="sip_identity" id="sip_identity" value="{{ old('sip_identity', $configuracionVideo->sip_identity) }}" required>
                            @if($errors->has('sip_identity'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sip_identity') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_identity_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="display_name">{{ trans('cruds.configuracionVideo.fields.display_name') }}</label>
                            <input class="form-control {{ $errors->has('display_name') ? 'is-invalid' : '' }}" type="text" name="display_name" id="display_name" value="{{ old('display_name', $configuracionVideo->display_name) }}" required>
                            @if($errors->has('display_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('display_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.display_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sip_registar">{{ trans('cruds.configuracionVideo.fields.sip_registar') }}</label>
                            <input class="form-control {{ $errors->has('sip_registar') ? 'is-invalid' : '' }}" type="text" name="sip_registar" id="sip_registar" value="{{ old('sip_registar', $configuracionVideo->sip_registar) }}" required>
                            @if($errors->has('sip_registar'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sip_registar') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_registar_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="username">{{ trans('cruds.configuracionVideo.fields.username') }}</label>
                            <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" id="username" value="{{ old('username', $configuracionVideo->username) }}" required>
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.username_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="password">{{ trans('cruds.configuracionVideo.fields.password') }}</label>
                            <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="text" name="password" id="password" value="{{ old('password', $configuracionVideo->password) }}" required>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.password_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sip_identity_destino">{{ trans('cruds.configuracionVideo.fields.sip_identity_destino') }}</label>
                            <input class="form-control {{ $errors->has('sip_identity_destino') ? 'is-invalid' : '' }}" type="text" name="sip_identity_destino" id="sip_identity_destino" value="{{ old('sip_identity_destino', $configuracionVideo->sip_identity_destino) }}">
                            @if($errors->has('sip_identity_destino'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sip_identity_destino') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_identity_destino_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endif







<!-- Modal Crear Configuracion Grabador -->
@if(!isset($configuracionGrabador->id))
    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir Configuración Grabador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route("admin.configuracion-grabadors.store") }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="totem_id" id="totem_id" value="{{ $totem->id }}">
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.configuracionGrabador.fields.software_gestion') }}</label>
                            <select class="form-control {{ $errors->has('software_gestion') ? 'is-invalid' : '' }}" name="software_gestion" id="software_gestion" required>
                                <option value disabled {{ old('software_gestion', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ConfiguracionGrabador::SOFTWARE_GESTION_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('software_gestion', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('software_gestion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('software_gestion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.software_gestion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="reader_no">{{ trans('cruds.configuracionGrabador.fields.reader_no') }}</label>
                            <input class="form-control {{ $errors->has('reader_no') ? 'is-invalid' : '' }}" type="number" name="reader_no" id="reader_no" value="{{ old('reader_no', '') }}" step="1">
                            @if($errors->has('reader_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reader_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.reader_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="track_2">{{ trans('cruds.configuracionGrabador.fields.track_2') }}</label>
                            <input class="form-control {{ $errors->has('track_2') ? 'is-invalid' : '' }}" type="text" name="track_2" id="track_2" value="{{ old('track_2', '') }}">
                            @if($errors->has('track_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('track_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.track_2_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="seq_mode">{{ trans('cruds.configuracionGrabador.fields.seq_mode') }}</label>
                            <input class="form-control {{ $errors->has('seq_mode') ? 'is-invalid' : '' }}" type="text" name="seq_mode" id="seq_mode" value="{{ old('seq_mode', '0') }}" required>
                            @if($errors->has('seq_mode'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('seq_mode') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.seq_mode_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="show_message">{{ trans('cruds.configuracionGrabador.fields.show_message') }}</label>
                            <input class="form-control {{ $errors->has('show_message') ? 'is-invalid' : '' }}" type="text" name="show_message" id="show_message" value="{{ old('show_message', '1') }}" required>
                            @if($errors->has('show_message'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('show_message') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.show_message_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_host">{{ trans('cruds.configuracionGrabador.fields.user_host') }}</label>
                            <input class="form-control {{ $errors->has('user_host') ? 'is-invalid' : '' }}" type="text" name="user_host" id="user_host" value="{{ old('user_host', '') }}" required>
                            @if($errors->has('user_host'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_host') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.user_host_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_port">{{ trans('cruds.configuracionGrabador.fields.user_port') }}</label>
                            <input class="form-control {{ $errors->has('user_port') ? 'is-invalid' : '' }}" type="number" name="user_port" id="user_port" value="{{ old('user_port', '') }}" step="1" required>
                            @if($errors->has('user_port'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_port') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.user_port_helper') }}</span>
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
@endif


<!-- Modal Editar Configuracion  Grabador -->
@if(isset($configuracionGrabador->id))
    <!-- Modal -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Configuración Grabador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route("admin.configuracion-grabadors.update", [$configuracionGrabador->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="totem_id" id="totem_id" value="{{ $totem->id }}">
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.configuracionGrabador.fields.software_gestion') }}</label>
                            <select class="form-control {{ $errors->has('software_gestion') ? 'is-invalid' : '' }}" name="software_gestion" id="software_gestion" required>
                                <option value disabled {{ old('software_gestion', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ConfiguracionGrabador::SOFTWARE_GESTION_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('software_gestion', $configuracionGrabador->software_gestion) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('software_gestion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('software_gestion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.software_gestion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="reader_no">{{ trans('cruds.configuracionGrabador.fields.reader_no') }}</label>
                            <input class="form-control {{ $errors->has('reader_no') ? 'is-invalid' : '' }}" type="number" name="reader_no" id="reader_no" value="{{ old('reader_no', $configuracionGrabador->reader_no) }}" step="1">
                            @if($errors->has('reader_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reader_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.reader_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="track_2">{{ trans('cruds.configuracionGrabador.fields.track_2') }}</label>
                            <input class="form-control {{ $errors->has('track_2') ? 'is-invalid' : '' }}" type="text" name="track_2" id="track_2" value="{{ old('track_2', $configuracionGrabador->track_2) }}">
                            @if($errors->has('track_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('track_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.track_2_helper') }}</span>
                        </div>
                        <!-- <div class="form-group">
                            <label class="required" for="seq_mode">{{ trans('cruds.configuracionGrabador.fields.seq_mode') }}</label>
                            <input class="form-control {{ $errors->has('seq_mode') ? 'is-invalid' : '' }}" type="text" name="seq_mode" id="seq_mode" value="{{ old('seq_mode', $configuracionGrabador->seq_mode) }}" required>
                            @if($errors->has('seq_mode'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('seq_mode') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.seq_mode_helper') }}</span>
                        </div>-->
                        <div class="form-group">
                            <label class="required" for="show_message">{{ trans('cruds.configuracionGrabador.fields.show_message') }}</label>
                            <input class="form-control {{ $errors->has('show_message') ? 'is-invalid' : '' }}" type="text" name="show_message" id="show_message" value="{{ old('show_message', $configuracionGrabador->show_message) }}" required>
                            @if($errors->has('show_message'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('show_message') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.show_message_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_host">{{ trans('cruds.configuracionGrabador.fields.user_host') }}</label>
                            <input class="form-control {{ $errors->has('user_host') ? 'is-invalid' : '' }}" type="text" name="user_host" id="user_host" value="{{ old('user_host', $configuracionGrabador->user_host) }}" required>
                            @if($errors->has('user_host'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_host') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.user_host_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_port">{{ trans('cruds.configuracionGrabador.fields.user_port') }}</label>
                            <input class="form-control {{ $errors->has('user_port') ? 'is-invalid' : '' }}" type="number" name="user_port" id="user_port" value="{{ old('user_port', $configuracionGrabador->user_port) }}" step="1" required>
                            @if($errors->has('user_port'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_port') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.user_port_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label >JSON Grabador </label>                            
                            <textarea class="form-control" name="json_grabador" rows="4">{{ old('json_grabador', $configuracionGrabador->json_grabador) }}</textarea>    
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
@endif






@endsection














