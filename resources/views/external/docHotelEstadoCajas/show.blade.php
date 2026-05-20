@extends('layouts.external')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docHotelEstadoCaja.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-hotel-estado-cajas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.id') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.caja') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->caja }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.code') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.room') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->room->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.room_status') }}
                        </th>
                        <td>
                            {{ App\Models\DocHotelEstadoCaja::ROOM_STATUS_SELECT[$docHotelEstadoCaja->room_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.cliente') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->cliente }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.documento_cliente') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->documento_cliente }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.comments') }}
                        </th>
                        <td>
                            {!! $docHotelEstadoCaja->comments !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.pay') }}
                        </th>
                        <td>
                            {{ App\Models\DocHotelEstadoCaja::PAY_SELECT[$docHotelEstadoCaja->pay] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelEstadoCaja.fields.fecha') }}
                        </th>
                        <td>
                            {{ $docHotelEstadoCaja->fecha }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-hotel-estado-cajas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
