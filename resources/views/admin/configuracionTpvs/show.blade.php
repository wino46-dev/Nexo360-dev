@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.configuracionTpv.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.configuracion-tpvs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionTpv.fields.totem') }}
                        </th>
                        <td>
                            {{ $configuracionTpv->totem->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionTpv.fields.comercio') }}
                        </th>
                        <td>
                            {{ $configuracionTpv->comercio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionTpv.fields.terminal') }}
                        </th>
                        <td>
                            {{ $configuracionTpv->terminal }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionTpv.fields.clave_firma') }}
                        </th>
                        <td>
                            {{ $configuracionTpv->clave_firma }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionTpv.fields.conf_puerto') }}
                        </th>
                        <td>
                            {{ $configuracionTpv->conf_puerto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionTpv.fields.version') }}
                        </th>
                        <td>
                            {{ $configuracionTpv->version }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.configuracion-tpvs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection