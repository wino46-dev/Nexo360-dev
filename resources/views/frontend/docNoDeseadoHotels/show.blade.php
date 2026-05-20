@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.docNoDeseadoHotel.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-no-deseado-hotels.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $docNoDeseadoHotel->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.establecimiento') }}
                                    </th>
                                    <td>
                                        {{ $docNoDeseadoHotel->establecimiento->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.fecha') }}
                                    </th>
                                    <td>
                                        {{ $docNoDeseadoHotel->fecha }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.datos_cliente') }}
                                    </th>
                                    <td>
                                        {!! $docNoDeseadoHotel->datos_cliente !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.no_deseado') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $docNoDeseadoHotel->no_deseado ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.motivo') }}
                                    </th>
                                    <td>
                                        {{ $docNoDeseadoHotel->motivo }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.comentarios') }}
                                    </th>
                                    <td>
                                        {!! $docNoDeseadoHotel->comentarios !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $docNoDeseadoHotel->created_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.updated_at') }}
                                    </th>
                                    <td>
                                        {{ $docNoDeseadoHotel->updated_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-no-deseado-hotels.index') }}">
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