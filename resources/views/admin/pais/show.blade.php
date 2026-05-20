@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pai.fields.id') }}
                        </th>
                        <td>
                            {{ $pai->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pai.fields.nombre') }}
                        </th>
                        <td>
                            {{ $pai->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pai.fields.codigo') }}
                        </th>
                        <td>
                            {{ $pai->codigo }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.pais.index') }}">
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
            <a class="nav-link" href="#pais_provincia" role="tab" data-toggle="tab">
                {{ trans('cruds.provincium.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="pais_provincia">
            @includeIf('admin.pais.relationships.paisProvincia', ['provincia' => $pai->paisProvincia])
        </div>
    </div>
</div>

@endsection
