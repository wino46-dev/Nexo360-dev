@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

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
                <a class="btn btn-warning" href="{{ route('admin.clientes.index') }}">
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
            <a class="nav-link" href="#cliente_reservas" role="tab" data-toggle="tab">
                {{ trans('cruds.reserva.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#cliente_check_ins" role="tab" data-toggle="tab">
                {{ trans('cruds.checkIn.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="cliente_reservas">
            @includeIf('admin.clientes.relationships.clienteReservas', ['reservas' => $cliente->clienteReservas])
        </div>
        <div class="tab-pane" role="tabpanel" id="cliente_check_ins">
            @includeIf('admin.clientes.relationships.clienteCheckIns', ['checkIns' => $cliente->clienteCheckIns])
        </div>
    </div>
</div>

@endsection
