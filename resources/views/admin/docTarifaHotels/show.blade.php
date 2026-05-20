@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docTarifaHotel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doc-tarifa-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.id') }}
                        </th>
                        <td>
                            {{ $docTarifaHotel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $docTarifaHotel->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.tarifa') }}
                        </th>
                        <td>
                            {{ $docTarifaHotel->tarifa }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.regimen') }}
                        </th>
                        <td>
                            {{ App\Models\DocTarifaHotel::REGIMEN_SELECT[$docTarifaHotel->regimen] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.habitacion') }}
                        </th>
                        <td>
                            {{ $docTarifaHotel->habitacion->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.importe') }}
                        </th>
                        <td>
                            {{ $docTarifaHotel->importe }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docTarifaHotel.fields.fecha') }}
                        </th>
                        <td>
                            {{ $docTarifaHotel->fecha }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doc-tarifa-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection