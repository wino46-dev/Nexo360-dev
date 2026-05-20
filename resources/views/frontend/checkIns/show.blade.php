@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.checkIn.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.check-ins.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.cliente') }}
                                    </th>
                                    <td>
                                        {{ $checkIn->cliente->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.reserva') }}
                                    </th>
                                    <td>
                                        {{ $checkIn->reserva->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.habitacion') }}
                                    </th>
                                    <td>
                                        {{ $checkIn->habitacion->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.totem') }}
                                    </th>
                                    <td>
                                        {{ $checkIn->totem->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.llave') }}
                                    </th>
                                    <td>
                                        {{ $checkIn->llave }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.dni_anverso') }}
                                    </th>
                                    <td>
                                        @if($checkIn->dni_anverso)
                                            <a href="{{ $checkIn->dni_anverso->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $checkIn->dni_anverso->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.dni_reverso') }}
                                    </th>
                                    <td>
                                        @if($checkIn->dni_reverso)
                                            <a href="{{ $checkIn->dni_reverso->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $checkIn->dni_reverso->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.firma_checkin') }}
                                    </th>
                                    <td>
                                        @if($checkIn->firma_checkin)
                                            <a href="{{ $checkIn->firma_checkin->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $checkIn->firma_checkin->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.firma_verificada') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $checkIn->firma_verificada ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.comentarios') }}
                                    </th>
                                    <td>
                                        {!! $checkIn->comentarios !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $checkIn->created_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.check-ins.index') }}">
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