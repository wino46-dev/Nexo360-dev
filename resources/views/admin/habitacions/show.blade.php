@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.habitacion.fields.id') }}
                        </th>
                        <td>
                            {{ $habitacion->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.habitacion.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $habitacion->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.habitacion.fields.codigo') }}
                        </th>
                        <td>
                            {{ $habitacion->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.habitacion.fields.created_at') }}
                        </th>
                        <td>
                            {{ $habitacion->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.habitacion.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $habitacion->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.habitacions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
