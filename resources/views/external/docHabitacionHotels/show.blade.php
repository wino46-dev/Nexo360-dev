@extends('layouts.external')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docHabitacionHotel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-habitacion-hotels.index') }}">
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
                <a class="btn btn-default" href="{{ route('external.doc-habitacion-hotels.index') }}">
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
            <a class="nav-link" href="#habitacion_doc_tarifa_hotels" role="tab" data-toggle="tab">
                {{ trans('cruds.docTarifaHotel.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="habitacion_doc_tarifa_hotels">
            @includeIf('external.docHabitacionHotels.relationships.habitacionDocTarifaHotels', ['docTarifaHotels' => $docHabitacionHotel->habitacionDocTarifaHotels])
        </div>
    </div>
</div>

@endsection
