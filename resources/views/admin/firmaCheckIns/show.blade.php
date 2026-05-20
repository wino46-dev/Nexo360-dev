@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.firmaCheckIn.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.firma-check-ins.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.firmaCheckIn.fields.sesion') }}
                        </th>
                        <td>
                            {{ $firmaCheckIn->sesion->estado_sesion ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.firmaCheckIn.fields.documento') }}
                        </th>
                        <td>
                            @if($firmaCheckIn->documento)
                                <a href="{{ $firmaCheckIn->documento->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.firmaCheckIn.fields.firma_texto') }}
                        </th>
                        <td>
                            {{ $firmaCheckIn->firma_texto }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.firma-check-ins.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection