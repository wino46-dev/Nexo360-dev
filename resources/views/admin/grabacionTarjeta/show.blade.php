@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.grabacionTarjetum.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.emisor') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->emisor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.receptor') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->receptor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.sesion') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->sesion->estado_sesion ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.date_in') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->date_in }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.time_in') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->time_in }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.date_out') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->date_out }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.time_out') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->time_out }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.room_no') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->room_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.room_no_2') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->room_no_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.room_no_3') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->room_no_3 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.safe_box') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->safe_box }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.common_doors') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->common_doors }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.card_qty') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->card_qty }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.uid_card') }}
                        </th>
                        <td>
                            {{ $grabacionTarjetum->uid_card }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.grabacion-tarjeta.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
