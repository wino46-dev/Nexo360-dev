@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.eventoHomeTotem.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">

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
            <div class="card">
                <div class="card-header">
                    Respuesta al Evento
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                        @if($eventoHomeTotem->respuesta_texto)
                            <tr>

                                <td>
                                    <img src="{{$eventoHomeTotem->respuesta_texto}}" width="100%">

                                </td>
                            </tr>
                        @endif
                        @if($eventoHomeTotem->respuesta_imagen)
                            <tr>
                                <th>
                                    {{ trans('cruds.eventoHomeTotem.fields.respuesta_imagen') }}
                                </th>
                                <td>
                                    <a href="{{ $eventoHomeTotem->respuesta_imagen->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $eventoHomeTotem->respuesta_imagen->getUrl('thumb') }}">
                                    </a>
                                </td>
                            </tr>

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.evento-home-totems.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
