@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.respuestaPago.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.respuesta-pagos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="pago_origen_id">{{ trans('cruds.respuestaPago.fields.pago_origen') }}</label>
                <select class="form-control select2 {{ $errors->has('pago_origen') ? 'is-invalid' : '' }}" name="pago_origen_id" id="pago_origen_id" required>
                    @foreach($pago_origens as $id => $entry)
                        <option value="{{ $id }}" {{ old('pago_origen_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pago_origen'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pago_origen') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.pago_origen_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tipo_pago">{{ trans('cruds.respuestaPago.fields.tipo_pago') }}</label>
                <input class="form-control {{ $errors->has('tipo_pago') ? 'is-invalid' : '' }}" type="text" name="tipo_pago" id="tipo_pago" value="{{ old('tipo_pago', '') }}">
                @if($errors->has('tipo_pago'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_pago') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.tipo_pago_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tipo_oper">{{ trans('cruds.respuestaPago.fields.tipo_oper') }}</label>
                <input class="form-control {{ $errors->has('tipo_oper') ? 'is-invalid' : '' }}" type="text" name="tipo_oper" id="tipo_oper" value="{{ old('tipo_oper', '') }}">
                @if($errors->has('tipo_oper'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_oper') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.tipo_oper_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="importe">{{ trans('cruds.respuestaPago.fields.importe') }}</label>
                <input class="form-control {{ $errors->has('importe') ? 'is-invalid' : '' }}" type="number" name="importe" id="importe" value="{{ old('importe', '') }}" step="0.01">
                @if($errors->has('importe'))
                    <div class="invalid-feedback">
                        {{ $errors->first('importe') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.importe_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="moneda">{{ trans('cruds.respuestaPago.fields.moneda') }}</label>
                <input class="form-control {{ $errors->has('moneda') ? 'is-invalid' : '' }}" type="text" name="moneda" id="moneda" value="{{ old('moneda', '') }}">
                @if($errors->has('moneda'))
                    <div class="invalid-feedback">
                        {{ $errors->first('moneda') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.moneda_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tarjeta_comercio_recibo">{{ trans('cruds.respuestaPago.fields.tarjeta_comercio_recibo') }}</label>
                <input class="form-control {{ $errors->has('tarjeta_comercio_recibo') ? 'is-invalid' : '' }}" type="text" name="tarjeta_comercio_recibo" id="tarjeta_comercio_recibo" value="{{ old('tarjeta_comercio_recibo', '') }}">
                @if($errors->has('tarjeta_comercio_recibo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tarjeta_comercio_recibo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.tarjeta_comercio_recibo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tarjeta_cliente_recibo">{{ trans('cruds.respuestaPago.fields.tarjeta_cliente_recibo') }}</label>
                <input class="form-control {{ $errors->has('tarjeta_cliente_recibo') ? 'is-invalid' : '' }}" type="text" name="tarjeta_cliente_recibo" id="tarjeta_cliente_recibo" value="{{ old('tarjeta_cliente_recibo', '') }}">
                @if($errors->has('tarjeta_cliente_recibo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tarjeta_cliente_recibo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.tarjeta_cliente_recibo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="marca_tarjeta">{{ trans('cruds.respuestaPago.fields.marca_tarjeta') }}</label>
                <input class="form-control {{ $errors->has('marca_tarjeta') ? 'is-invalid' : '' }}" type="email" name="marca_tarjeta" id="marca_tarjeta" value="{{ old('marca_tarjeta') }}">
                @if($errors->has('marca_tarjeta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('marca_tarjeta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.marca_tarjeta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="caducidad">{{ trans('cruds.respuestaPago.fields.caducidad') }}</label>
                <input class="form-control {{ $errors->has('caducidad') ? 'is-invalid' : '' }}" type="text" name="caducidad" id="caducidad" value="{{ old('caducidad', '') }}">
                @if($errors->has('caducidad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('caducidad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.caducidad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comercio">{{ trans('cruds.respuestaPago.fields.comercio') }}</label>
                <input class="form-control {{ $errors->has('comercio') ? 'is-invalid' : '' }}" type="text" name="comercio" id="comercio" value="{{ old('comercio', '') }}">
                @if($errors->has('comercio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comercio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.comercio_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="terminal">{{ trans('cruds.respuestaPago.fields.terminal') }}</label>
                <input class="form-control {{ $errors->has('terminal') ? 'is-invalid' : '' }}" type="text" name="terminal" id="terminal" value="{{ old('terminal', '') }}">
                @if($errors->has('terminal'))
                    <div class="invalid-feedback">
                        {{ $errors->first('terminal') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.terminal_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tarjeta">{{ trans('cruds.respuestaPago.fields.tarjeta') }}</label>
                <input class="form-control {{ $errors->has('tarjeta') ? 'is-invalid' : '' }}" type="text" name="tarjeta" id="tarjeta" value="{{ old('tarjeta', '') }}">
                @if($errors->has('tarjeta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tarjeta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.tarjeta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="identificador_rts_base">{{ trans('cruds.respuestaPago.fields.identificador_rts_base') }}</label>
                <input class="form-control {{ $errors->has('identificador_rts_base') ? 'is-invalid' : '' }}" type="text" name="identificador_rts_base" id="identificador_rts_base" value="{{ old('identificador_rts_base', '') }}">
                @if($errors->has('identificador_rts_base'))
                    <div class="invalid-feedback">
                        {{ $errors->first('identificador_rts_base') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.identificador_rts_base_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pedido">{{ trans('cruds.respuestaPago.fields.pedido') }}</label>
                <input class="form-control {{ $errors->has('pedido') ? 'is-invalid' : '' }}" type="text" name="pedido" id="pedido" value="{{ old('pedido', '') }}">
                @if($errors->has('pedido'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pedido') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.pedido_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tipo_tasa_aplicada">{{ trans('cruds.respuestaPago.fields.tipo_tasa_aplicada') }}</label>
                <input class="form-control {{ $errors->has('tipo_tasa_aplicada') ? 'is-invalid' : '' }}" type="text" name="tipo_tasa_aplicada" id="tipo_tasa_aplicada" value="{{ old('tipo_tasa_aplicada', '') }}">
                @if($errors->has('tipo_tasa_aplicada'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_tasa_aplicada') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.tipo_tasa_aplicada_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="identificador_rts">{{ trans('cruds.respuestaPago.fields.identificador_rts') }}</label>
                <input class="form-control {{ $errors->has('identificador_rts') ? 'is-invalid' : '' }}" type="text" name="identificador_rts" id="identificador_rts" value="{{ old('identificador_rts', '') }}">
                @if($errors->has('identificador_rts'))
                    <div class="invalid-feedback">
                        {{ $errors->first('identificador_rts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.identificador_rts_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="factura">{{ trans('cruds.respuestaPago.fields.factura') }}</label>
                <input class="form-control {{ $errors->has('factura') ? 'is-invalid' : '' }}" type="text" name="factura" id="factura" value="{{ old('factura', '') }}">
                @if($errors->has('factura'))
                    <div class="invalid-feedback">
                        {{ $errors->first('factura') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.factura_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fecha_operacion">{{ trans('cruds.respuestaPago.fields.fecha_operacion') }}</label>
                <input class="form-control {{ $errors->has('fecha_operacion') ? 'is-invalid' : '' }}" type="text" name="fecha_operacion" id="fecha_operacion" value="{{ old('fecha_operacion', '') }}">
                @if($errors->has('fecha_operacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_operacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.fecha_operacion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="estado">{{ trans('cruds.respuestaPago.fields.estado') }}</label>
                <input class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}" type="text" name="estado" id="estado" value="{{ old('estado', '') }}">
                @if($errors->has('estado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('estado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.estado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resultado">{{ trans('cruds.respuestaPago.fields.resultado') }}</label>
                <input class="form-control {{ $errors->has('resultado') ? 'is-invalid' : '' }}" type="text" name="resultado" id="resultado" value="{{ old('resultado', '') }}">
                @if($errors->has('resultado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resultado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.resultado_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codigo_respuesta">{{ trans('cruds.respuestaPago.fields.codigo_respuesta') }}</label>
                <input class="form-control {{ $errors->has('codigo_respuesta') ? 'is-invalid' : '' }}" type="text" name="codigo_respuesta" id="codigo_respuesta" value="{{ old('codigo_respuesta', '') }}">
                @if($errors->has('codigo_respuesta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo_respuesta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.codigo_respuesta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="literales">{{ trans('cruds.respuestaPago.fields.literales') }}</label>
                <textarea class="form-control {{ $errors->has('literales') ? 'is-invalid' : '' }}" name="literales" id="literales">{{ old('literales') }}</textarea>
                @if($errors->has('literales'))
                    <div class="invalid-feedback">
                        {{ $errors->first('literales') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.literales_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="firma">{{ trans('cruds.respuestaPago.fields.firma') }}</label>
                <textarea class="form-control {{ $errors->has('firma') ? 'is-invalid' : '' }}" name="firma" id="firma">{{ old('firma') }}</textarea>
                @if($errors->has('firma'))
                    <div class="invalid-feedback">
                        {{ $errors->first('firma') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.firma_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="operacionemv">{{ trans('cruds.respuestaPago.fields.operacionemv') }}</label>
                <input class="form-control {{ $errors->has('operacionemv') ? 'is-invalid' : '' }}" type="text" name="operacionemv" id="operacionemv" value="{{ old('operacionemv', '') }}">
                @if($errors->has('operacionemv'))
                    <div class="invalid-feedback">
                        {{ $errors->first('operacionemv') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.operacionemv_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="conttrans">{{ trans('cruds.respuestaPago.fields.conttrans') }}</label>
                <input class="form-control {{ $errors->has('conttrans') ? 'is-invalid' : '' }}" type="text" name="conttrans" id="conttrans" value="{{ old('conttrans', '') }}">
                @if($errors->has('conttrans'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conttrans') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.conttrans_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sectarjeta">{{ trans('cruds.respuestaPago.fields.sectarjeta') }}</label>
                <input class="form-control {{ $errors->has('sectarjeta') ? 'is-invalid' : '' }}" type="text" name="sectarjeta" id="sectarjeta" value="{{ old('sectarjeta', '') }}">
                @if($errors->has('sectarjeta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sectarjeta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.sectarjeta_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="idapp">{{ trans('cruds.respuestaPago.fields.idapp') }}</label>
                <input class="form-control {{ $errors->has('idapp') ? 'is-invalid' : '' }}" type="text" name="idapp" id="idapp" value="{{ old('idapp', '') }}">
                @if($errors->has('idapp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('idapp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.idapp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codrespauto">{{ trans('cruds.respuestaPago.fields.codrespauto') }}</label>
                <input class="form-control {{ $errors->has('codrespauto') ? 'is-invalid' : '' }}" type="text" name="codrespauto" id="codrespauto" value="{{ old('codrespauto', '') }}">
                @if($errors->has('codrespauto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codrespauto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.codrespauto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resverificacion">{{ trans('cruds.respuestaPago.fields.resverificacion') }}</label>
                <input class="form-control {{ $errors->has('resverificacion') ? 'is-invalid' : '' }}" type="text" name="resverificacion" id="resverificacion" value="{{ old('resverificacion', '') }}">
                @if($errors->has('resverificacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resverificacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.resverificacion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="version">{{ trans('cruds.respuestaPago.fields.version') }}</label>
                <input class="form-control {{ $errors->has('version') ? 'is-invalid' : '' }}" type="text" name="version" id="version" value="{{ old('version', '') }}">
                @if($errors->has('version'))
                    <div class="invalid-feedback">
                        {{ $errors->first('version') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaPago.fields.version_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection