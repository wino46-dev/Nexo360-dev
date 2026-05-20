@extends('layouts.external')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docInfoHotel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-info-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.id') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.hotel') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->hotel->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.descripcion') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->descripcion !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.exteriores') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->exteriores !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.categoria') }}
                        </th>
                        <td>
                            {{ App\Models\DocInfoHotel::CATEGORIA_SELECT[$docInfoHotel->categoria] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.pais') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->pais->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.provincia') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->provincia->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.ciudad') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->ciudad->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.direccion') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->direccion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.codigo_postal') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->codigo_postal }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.latitud') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->latitud }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.longitud') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->longitud }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.telefono') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->telefono }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.emergencias') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->emergencias }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.email') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.web') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->web }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.enlace_fotos') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->enlace_fotos }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.aparcamiento') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->aparcamiento !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.recomendaciones') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->recomendaciones !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.horas_chekin') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->horas_chekin !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.observaciones') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->observaciones !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.cuenta_bancaria') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->cuenta_bancaria }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.modos_cobro') }}
                        </th>
                        <td>
                            {{ $docInfoHotel->modos_cobro }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.datos_responsable') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->datos_responsable !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.regional_manager') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->regional_manager !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.revenue_manager') }}
                        </th>
                        <td>
                            {!! $docInfoHotel->revenue_manager !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docInfoHotel.fields.pet_friendly') }}
                        </th>
                        <td>
                            {{ App\Models\DocInfoHotel::PET_FRIENDLY_SELECT[$docInfoHotel->pet_friendly] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-info-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
