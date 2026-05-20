@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.parteViajero.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.parte-viajeros.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.parteViajero.fields.id') }}
                        </th>
                        <td>
                            {{ $parteViajero->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.parteViajero.fields.reserva') }}
                        </th>
                        <td>
                            {{ $parteViajero->reserva->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.parteViajero.fields.cliente') }}
                        </th>
                        <td>
                            {{ $parteViajero->cliente->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.parteViajero.fields.texto_inferior') }}
                        </th>
                        <td>
                            {{ $parteViajero->texto_inferior }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.parteViajero.fields.firma') }}
                        </th>
                        <td>
                            {{ $parteViajero->firma }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.parteViajero.fields.envio_mail') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $parteViajero->envio_mail ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.parte-viajeros.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection