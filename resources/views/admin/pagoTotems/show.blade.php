@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.emisor') }}
                        </th>
                        <td>
                            {{ $pagoTotem->emisor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.receptor') }}
                        </th>
                        <td>
                            {{ $pagoTotem->receptor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.importe') }}
                        </th>
                        <td>
                            {{ $pagoTotem->importe }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.factura') }}
                        </th>
                        <td>
                            {{ $pagoTotem->factura }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.tipo_operacion') }}
                        </th>
                        <td>
                            {{ App\Models\PagoTotem::TIPO_OPERACION_SELECT[$pagoTotem->tipo_operacion] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Origen
                        </th>
                        <td>
                            {{ $pagoTotem->origen }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Notas
                        </th>
                        <td>
                            {{ $pagoTotem->notas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Folio
                        </th>
                        <td>
                            {{ $pagoTotem->folio_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            PMS
                        </th>
                        <td>
                            {{ $pagoTotem->pms }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.created_at') }}
                        </th>
                        <td>
                            {{ $pagoTotem->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pagoTotem.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $pagoTotem->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            @includeIf('admin.pagoTotems.relationships.pagoOrigenRespuestaPagos', ['respuestaPagos' => $pagoTotem->pagoOrigenRespuestaPagos])

            <div class="form-group">
                <a class="btn btn-warning" href="{{ URL::previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
