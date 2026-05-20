@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docMetodoPagoHotel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doc-metodo-pago-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.docMetodoPagoHotel.fields.id') }}
                        </th>
                        <td>
                            {{ $docMetodoPagoHotel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docMetodoPagoHotel.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $docMetodoPagoHotel->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docMetodoPagoHotel.fields.nombre') }}
                        </th>
                        <td>
                            {{ $docMetodoPagoHotel->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docMetodoPagoHotel.fields.tipo') }}
                        </th>
                        <td>
                            {{ App\Models\DocMetodoPagoHotel::TIPO_SELECT[$docMetodoPagoHotel->tipo] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docMetodoPagoHotel.fields.activo') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $docMetodoPagoHotel->activo ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doc-metodo-pago-hotels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection