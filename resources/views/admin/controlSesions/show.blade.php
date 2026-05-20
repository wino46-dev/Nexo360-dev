@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.controlSesion.title_singular') }} - ID <b>{{$controlSesion->id}}</b>
    </div>

    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.controlSesion.fields.emisor') }}
                        </th>
                        <td>
                            {{ $controlSesion->emisor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlSesion.fields.receptor') }}
                        </th>
                        <td>
                            {{ $controlSesion->receptor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlSesion.fields.estado_sesion') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $controlSesion->estado_sesion ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlSesion.fields.created_at') }}
                        </th>
                        <td>
                            {{ $controlSesion->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlSesion.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $controlSesion->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#sesion_evento_home_totems" role="tab" data-toggle="tab">
                        {{ trans('cruds.eventoHomeTotem.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sesion_pago_totems" role="tab" data-toggle="tab">
                        {{ trans('cruds.pagoTotem.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sesion_grabacion_tarjeta" role="tab" data-toggle="tab">
                        {{ trans('cruds.grabacionTarjetum.title') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#sesion_firma_check_ins" role="tab" data-toggle="tab">
                        {{ trans('cruds.firmaCheckIn.title') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel" id="sesion_evento_home_totems">
                    @includeIf('admin.controlSesions.relationships.sesionEventoHomeTotems', ['eventoHomeTotems' => $controlSesion->sesionEventoHomeTotems])
                </div>

                <div class="tab-pane" role="tabpanel" id="sesion_pago_totems">
                    @includeIf('admin.controlSesions.relationships.sesionPagoTotems', ['pagoTotems' => $controlSesion->sesionPagoTotems])
                </div>
                <div class="tab-pane" role="tabpanel" id="sesion_grabacion_tarjeta">
                    @includeIf('admin.controlSesions.relationships.sesionGrabacionTarjeta', ['grabacionTarjeta' => $controlSesion->sesionGrabacionTarjeta])
                </div>

                <div class="tab-pane" role="tabpanel" id="sesion_firma_check_ins">
                    @includeIf('admin.controlSesions.relationships.sesionFirmaCheckIns', ['firmaCheckIns' => $controlSesion->sesionFirmaCheckIns])
                </div>
            </div>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.control-sesions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>


@endsection
