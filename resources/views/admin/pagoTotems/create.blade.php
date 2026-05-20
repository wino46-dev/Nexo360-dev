@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.pagoTotem.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pago-totems.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="emisor_id">{{ trans('cruds.pagoTotem.fields.emisor') }}</label>
                <select class="form-control select2 {{ $errors->has('emisor') ? 'is-invalid' : '' }}" name="emisor_id" id="emisor_id" required>
                    @foreach($emisors as $id => $entry)
                        <option value="{{ $id }}" {{ old('emisor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('emisor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('emisor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pagoTotem.fields.emisor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="receptor_id">{{ trans('cruds.pagoTotem.fields.receptor') }}</label>
                <select class="form-control select2 {{ $errors->has('receptor') ? 'is-invalid' : '' }}" name="receptor_id" id="receptor_id" required>
                    @foreach($receptors as $id => $entry)
                        <option value="{{ $id }}" {{ old('receptor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('receptor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receptor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pagoTotem.fields.receptor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sesion_id">{{ trans('cruds.pagoTotem.fields.sesion') }}</label>
                <select class="form-control select2 {{ $errors->has('sesion') ? 'is-invalid' : '' }}" name="sesion_id" id="sesion_id">
                    @foreach($sesions as $id => $entry)
                        <option value="{{ $id }}" {{ old('sesion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sesion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sesion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pagoTotem.fields.sesion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="importe">{{ trans('cruds.pagoTotem.fields.importe') }}</label>
                <input class="form-control {{ $errors->has('importe') ? 'is-invalid' : '' }}" type="number" name="importe" id="importe" value="{{ old('importe', '') }}" step="0.01" required>
                @if($errors->has('importe'))
                    <div class="invalid-feedback">
                        {{ $errors->first('importe') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pagoTotem.fields.importe_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="factura">{{ trans('cruds.pagoTotem.fields.factura') }}</label>
                <input class="form-control {{ $errors->has('factura') ? 'is-invalid' : '' }}" type="text" name="factura" id="factura" value="{{ old('factura', '') }}" required>
                @if($errors->has('factura'))
                    <div class="invalid-feedback">
                        {{ $errors->first('factura') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pagoTotem.fields.factura_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.pagoTotem.fields.tipo_operacion') }}</label>
                <select class="form-control {{ $errors->has('tipo_operacion') ? 'is-invalid' : '' }}" name="tipo_operacion" id="tipo_operacion">
                    <option value disabled {{ old('tipo_operacion', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\PagoTotem::TIPO_OPERACION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo_operacion', 'PAGO') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo_operacion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_operacion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pagoTotem.fields.tipo_operacion_helper') }}</span>
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