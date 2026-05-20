@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.docServicioHotel.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-servicio-hotels.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $docServicioHotel->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.hotel') }}
                                    </th>
                                    <td>
                                        {{ $docServicioHotel->hotel->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.nombre') }}
                                    </th>
                                    <td>
                                        {{ $docServicioHotel->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.codigo') }}
                                    </th>
                                    <td>
                                        {{ $docServicioHotel->codigo }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.precio') }}
                                    </th>
                                    <td>
                                        {{ $docServicioHotel->precio }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.por_persona') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $docServicioHotel->por_persona ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.por_dia') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $docServicioHotel->por_dia ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docServicioHotel.fields.tipo') }}
                                    </th>
                                    <td>
                                        {{ App\Models\DocServicioHotel::TIPO_SELECT[$docServicioHotel->tipo] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-servicio-hotels.index') }}">
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