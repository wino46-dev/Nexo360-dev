@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.zonaComun.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zona-comuns.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.zonaComun.fields.id') }}
                        </th>
                        <td>
                            {{ $zonaComun->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zonaComun.fields.nombre') }}
                        </th>
                        <td>
                            {{ $zonaComun->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zonaComun.fields.codigo') }}
                        </th>
                        <td>
                            {{ $zonaComun->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zonaComun.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $zonaComun->establecimiento->nombre ?? '' }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.zonaComun.fields.global') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $zonaComun->global ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zonaComun.fields.estado') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $zonaComun->estado ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zona-comuns.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection