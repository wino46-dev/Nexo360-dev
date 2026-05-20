@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.configuracionVideo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.totem') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->totem->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.sip_identity') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->sip_identity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.display_name') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->display_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.sip_registar') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->sip_registar }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.username') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->username }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.password') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->password }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.created_at') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.sip_identity_destino') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->sip_identity_destino }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.configuracionVideo.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $configuracionVideo->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.configuracion-videos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
