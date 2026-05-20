@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.cliente.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.clientes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $cliente->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.nombre') }}
                                    </th>
                                    <td>
                                        {{ $cliente->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.apellidos') }}
                                    </th>
                                    <td>
                                        {{ $cliente->apellidos }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.nif') }}
                                    </th>
                                    <td>
                                        {{ $cliente->nif }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.telefono') }}
                                    </th>
                                    <td>
                                        {{ $cliente->telefono }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.pais') }}
                                    </th>
                                    <td>
                                        {{ $cliente->pais->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.provincia') }}
                                    </th>
                                    <td>
                                        {{ $cliente->provincia->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.ciudad') }}
                                    </th>
                                    <td>
                                        {{ $cliente->ciudad->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.cod_postal') }}
                                    </th>
                                    <td>
                                        {{ $cliente->cod_postal }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.direccion') }}
                                    </th>
                                    <td>
                                        {{ $cliente->direccion }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $cliente->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $cliente->created_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.cliente.fields.updated_at') }}
                                    </th>
                                    <td>
                                        {{ $cliente->updated_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.clientes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection