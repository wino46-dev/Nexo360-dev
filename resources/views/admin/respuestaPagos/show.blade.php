@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.respuestaPago.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">

                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.pago_origen') }}
                        </th>
                        <td>
                            {{ $respuestaPago->pago_origen->factura ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tipo_pago') }}
                        </th>
                        <td>
                            {{ $respuestaPago->tipo_pago }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tipo_oper') }}
                        </th>
                        <td>
                            {{ $respuestaPago->tipo_oper }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.importe') }}
                        </th>
                        <td>
                            {{ $respuestaPago->importe }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.moneda') }}
                        </th>
                        <td>
                            {{ $respuestaPago->moneda }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tarjeta_comercio_recibo') }}
                        </th>
                        <td>
                            {{ $respuestaPago->tarjeta_comercio_recibo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tarjeta_cliente_recibo') }}
                        </th>
                        <td>
                            {{ $respuestaPago->tarjeta_cliente_recibo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.marca_tarjeta') }}
                        </th>
                        <td>
                            {{ $respuestaPago->marca_tarjeta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.caducidad') }}
                        </th>
                        <td>
                            {{ $respuestaPago->caducidad }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.comercio') }}
                        </th>
                        <td>
                            {{ $respuestaPago->comercio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.terminal') }}
                        </th>
                        <td>
                            {{ $respuestaPago->terminal }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tarjeta') }}
                        </th>
                        <td>
                            {{ $respuestaPago->tarjeta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.identificador_rts_base') }}
                        </th>
                        <td>
                            {{ $respuestaPago->identificador_rts_base }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.pedido') }}
                        </th>
                        <td>
                            {{ $respuestaPago->pedido }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tipo_tasa_aplicada') }}
                        </th>
                        <td>
                            {{ $respuestaPago->tipo_tasa_aplicada }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.identificador_rts') }}
                        </th>
                        <td>
                            {{ $respuestaPago->identificador_rts }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.factura') }}
                        </th>
                        <td>
                            {{ $respuestaPago->factura }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.fecha_operacion') }}
                        </th>
                        <td>
                            {{ $respuestaPago->fecha_operacion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.estado') }}
                        </th>
                        <td>
                            {{ $respuestaPago->estado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.resultado') }}
                        </th>
                        <td>
                            {{ $respuestaPago->resultado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.codigo_respuesta') }}
                        </th>
                        <td>
                            {{ $respuestaPago->codigo_respuesta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.literales') }}
                        </th>
                        <td>
                            {{ $respuestaPago->literales }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.firma') }}
                        </th>
                        <td>
                            {{ $respuestaPago->firma }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.operacionemv') }}
                        </th>
                        <td>
                            {{ $respuestaPago->operacionemv }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.conttrans') }}
                        </th>
                        <td>
                            {{ $respuestaPago->conttrans }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.sectarjeta') }}
                        </th>
                        <td>
                            {{ $respuestaPago->sectarjeta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.idapp') }}
                        </th>
                        <td>
                            {{ $respuestaPago->idapp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.codrespauto') }}
                        </th>
                        <td>
                            {{ $respuestaPago->codrespauto }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.resverificacion') }}
                        </th>
                        <td>
                            {{ $respuestaPago->resverificacion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.version') }}
                        </th>
                        <td>
                            {{ $respuestaPago->version }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Respuesta XML
                        </th>
                        <td>
                            @php
                                $xmlObject = simplexml_load_string($respuestaPago->respuesta_xml);
                                $json = json_encode($xmlObject);
                                $phpArray = json_decode($json, true);
                                print "<pre>";
                                print_r($phpArray);
                                print "</pre>";

                            @endphp

                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-warning" href="{{ route('admin.respuesta-pagos.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>



@endsection
