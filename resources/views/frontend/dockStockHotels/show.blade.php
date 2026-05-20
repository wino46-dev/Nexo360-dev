@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.dockStockHotel.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.dock-stock-hotels.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dockStockHotel.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $dockStockHotel->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dockStockHotel.fields.nombre') }}
                                    </th>
                                    <td>
                                        {{ $dockStockHotel->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dockStockHotel.fields.ubicacion') }}
                                    </th>
                                    <td>
                                        {{ $dockStockHotel->ubicacion->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dockStockHotel.fields.cantidad') }}
                                    </th>
                                    <td>
                                        {{ $dockStockHotel->cantidad }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dockStockHotel.fields.comentarios') }}
                                    </th>
                                    <td>
                                        {!! $dockStockHotel->comentarios !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dockStockHotel.fields.unidad') }}
                                    </th>
                                    <td>
                                        {{ App\Models\DockStockHotel::UNIDAD_SELECT[$dockStockHotel->unidad] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.dock-stock-hotels.index') }}">
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