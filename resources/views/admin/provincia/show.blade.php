@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.provincium.fields.id') }}
                        </th>
                        <td>
                            {{ $provincium->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.provincium.fields.nombre') }}
                        </th>
                        <td>
                            {{ $provincium->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.provincium.fields.pais') }}
                        </th>
                        <td>
                            {{ $provincium->pais->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.provincium.fields.iso') }}
                        </th>
                        <td>
                            {{ $provincium->iso }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.provincia.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#provincia_ciudads" role="tab" data-toggle="tab">
                {{ trans('cruds.ciudad.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="provincia_ciudads">
            @includeIf('admin.provincia.relationships.provinciaCiudads', ['ciudads' => $provincium->provinciaCiudads])
        </div>
    </div>
</div>

@endsection
