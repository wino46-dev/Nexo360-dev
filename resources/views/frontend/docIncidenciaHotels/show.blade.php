@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.docIncidenciaHotel.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-incidencia-hotels.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.establecimiento') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->establecimiento->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.fecha') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->fecha }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.titulo') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->titulo }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.estado') }}
                                    </th>
                                    <td>
                                        {{ App\Models\DocIncidenciaHotel::ESTADO_SELECT[$docIncidenciaHotel->estado] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.dni') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->dni }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.nombre') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.descripcion') }}
                                    </th>
                                    <td>
                                        {!! $docIncidenciaHotel->descripcion !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->created_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docIncidenciaHotel.fields.updated_at') }}
                                    </th>
                                    <td>
                                        {{ $docIncidenciaHotel->updated_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-incidencia-hotels.index') }}">
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