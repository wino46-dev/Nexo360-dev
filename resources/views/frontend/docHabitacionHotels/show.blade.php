@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.docHabitacionHotel.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-habitacion-hotels.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $docHabitacionHotel->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.establecimiento') }}
                                    </th>
                                    <td>
                                        {{ $docHabitacionHotel->establecimiento->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.nombre') }}
                                    </th>
                                    <td>
                                        {{ $docHabitacionHotel->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.tipo') }}
                                    </th>
                                    <td>
                                        {{ App\Models\DocHabitacionHotel::TIPO_SELECT[$docHabitacionHotel->tipo] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.capacidad') }}
                                    </th>
                                    <td>
                                        {{ App\Models\DocHabitacionHotel::CAPACIDAD_SELECT[$docHabitacionHotel->capacidad] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.tipo_cerradura') }}
                                    </th>
                                    <td>
                                        {{ App\Models\DocHabitacionHotel::TIPO_CERRADURA_SELECT[$docHabitacionHotel->tipo_cerradura] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.codigo') }}
                                    </th>
                                    <td>
                                        {{ $docHabitacionHotel->codigo }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.comentarios') }}
                                    </th>
                                    <td>
                                        {!! $docHabitacionHotel->comentarios !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.doc-habitacion-hotels.index') }}">
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