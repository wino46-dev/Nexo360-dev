@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.ayudaStepTotem.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.ayuda-step-totems.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $ayudaStepTotem->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.establecimiento') }}
                                    </th>
                                    <td>
                                        {{ $ayudaStepTotem->establecimiento->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.eventos_push') }}
                                    </th>
                                    <td>
                                        {!! $ayudaStepTotem->eventos_push !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.pase_imagenes') }}
                                    </th>
                                    <td>
                                        {!! $ayudaStepTotem->pase_imagenes !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.captura_documentos') }}
                                    </th>
                                    <td>
                                        {!! $ayudaStepTotem->captura_documentos !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.pago_reserva') }}
                                    </th>
                                    <td>
                                        {!! $ayudaStepTotem->pago_reserva !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.grabacion_tarjeta') }}
                                    </th>
                                    <td>
                                        {!! $ayudaStepTotem->grabacion_tarjeta !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.parte_viajero') }}
                                    </th>
                                    <td>
                                        {!! $ayudaStepTotem->parte_viajero !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $ayudaStepTotem->created_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ayudaStepTotem.fields.updated_at') }}
                                    </th>
                                    <td>
                                        {{ $ayudaStepTotem->updated_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.ayuda-step-totems.index') }}">
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