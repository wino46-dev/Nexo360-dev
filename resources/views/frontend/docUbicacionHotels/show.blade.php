@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.docUbicacionHotel.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-ubicacion-hotels.index') }}">
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
                            <a class="btn btn-default" href="{{ route('frontend.doc-ubicacion-hotels.index') }}">
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