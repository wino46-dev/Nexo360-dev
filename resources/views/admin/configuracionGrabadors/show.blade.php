@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.configuracionGrabador.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.id') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.totem') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->totem->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.software_gestion') }}
                        </th>
                        <td>
                            {{ App\Models\ConfiguracionGrabador::SOFTWARE_GESTION_SELECT[$configuracionGrabador->software_gestion] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.reader_no') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->reader_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.track_2') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->track_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.seq_mode') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->seq_mode }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.show_message') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->show_message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionGrabador.fields.user_host') }}
                        </th>
                        <td>
                            {{ $configuracionGrabador->user_host }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.configuracion-grabadors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
