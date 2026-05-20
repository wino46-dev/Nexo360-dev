@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.eventoHomeTotem.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.evento-home-totems.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.emisor') }}
                                    </th>
                                    <td>
                                        {{ $eventoHomeTotem->emisor->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.receptor') }}
                                    </th>
                                    <td>
                                        {{ $eventoHomeTotem->receptor->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.tipo_evento') }}
                                    </th>
                                    <td>
                                        {{ $eventoHomeTotem->tipo_evento->nombre ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.objeto') }}
                                    </th>
                                    <td>
                                        {{ $eventoHomeTotem->objeto }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.canal_transmision') }}
                                    </th>
                                    <td>
                                        {{ App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT[$eventoHomeTotem->canal_transmision] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $eventoHomeTotem->created_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.evento-home-totems.index') }}">
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