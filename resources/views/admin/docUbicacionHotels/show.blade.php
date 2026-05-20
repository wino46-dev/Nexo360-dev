@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docUbicacionHotel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doc-ubicacion-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.id') }}
                        </th>
                        <td>
                            {{ $docUbicacionHotel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $docUbicacionHotel->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.nombre') }}
                        </th>
                        <td>
                            {{ $docUbicacionHotel->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.tipo') }}
                        </th>
                        <td>
                            {{ App\Models\DocUbicacionHotel::TIPO_SELECT[$docUbicacionHotel->tipo] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.piso') }}
                        </th>
                        <td>
                            {{ $docUbicacionHotel->piso }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.zona_comun') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $docUbicacionHotel->zona_comun ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docUbicacionHotel.fields.comentarios') }}
                        </th>
                        <td>
                            {!! $docUbicacionHotel->comentarios !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doc-ubicacion-hotels.index') }}">
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
            <a class="nav-link" href="#ubicacion_dock_stock_hotels" role="tab" data-toggle="tab">
                {{ trans('cruds.dockStockHotel.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="ubicacion_dock_stock_hotels">
            @includeIf('admin.docUbicacionHotels.relationships.ubicacionDockStockHotels', ['dockStockHotels' => $docUbicacionHotel->ubicacionDockStockHotels])
        </div>
    </div>
</div>

@endsection