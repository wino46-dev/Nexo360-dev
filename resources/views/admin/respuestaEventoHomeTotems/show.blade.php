@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.respuestaEventoHomeTotem.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.respuesta-evento-home-totems.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.evento') }}
                        </th>
                        <td>
                            {{ $respuestaEventoHomeTotem->evento->objeto ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.respuesta') }}
                        </th>
                        <td>
                            {{ $respuestaEventoHomeTotem->respuesta }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.estado') }}
                        </th>
                        <td>
                            {{ App\Models\RespuestaEventoHomeTotem::ESTADO_SELECT[$respuestaEventoHomeTotem->estado] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.created_at') }}
                        </th>
                        <td>
                            {{ $respuestaEventoHomeTotem->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.respuesta-evento-home-totems.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection