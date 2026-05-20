@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.parteViajero.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.parte-viajeros.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="reserva_id">{{ trans('cruds.parteViajero.fields.reserva') }}</label>
                <select class="form-control select2 {{ $errors->has('reserva') ? 'is-invalid' : '' }}" name="reserva_id" id="reserva_id" required>
                    @foreach($reservas as $id => $entry)
                        <option value="{{ $id }}" {{ old('reserva_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('reserva'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reserva') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.parteViajero.fields.reserva_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cliente_id">{{ trans('cruds.parteViajero.fields.cliente') }}</label>
                <select class="form-control select2 {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente_id" id="cliente_id" required>
                    @foreach($clientes as $id => $entry)
                        <option value="{{ $id }}" {{ old('cliente_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('cliente'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cliente') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.parteViajero.fields.cliente_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="texto_inferior">{{ trans('cruds.parteViajero.fields.texto_inferior') }}</label>
                <textarea class="form-control {{ $errors->has('texto_inferior') ? 'is-invalid' : '' }}" name="texto_inferior" id="texto_inferior">{{ old('texto_inferior') }}</textarea>
                @if($errors->has('texto_inferior'))
                    <div class="invalid-feedback">
                        {{ $errors->first('texto_inferior') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.parteViajero.fields.texto_inferior_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="firma">{{ trans('cruds.parteViajero.fields.firma') }}</label>
                <textarea class="form-control {{ $errors->has('firma') ? 'is-invalid' : '' }}" name="firma" id="firma">{{ old('firma') }}</textarea>
                @if($errors->has('firma'))
                    <div class="invalid-feedback">
                        {{ $errors->first('firma') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.parteViajero.fields.firma_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('envio_mail') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="envio_mail" value="0">
                    <input class="form-check-input" type="checkbox" name="envio_mail" id="envio_mail" value="1" {{ old('envio_mail', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="envio_mail">{{ trans('cruds.parteViajero.fields.envio_mail') }}</label>
                </div>
                @if($errors->has('envio_mail'))
                    <div class="invalid-feedback">
                        {{ $errors->first('envio_mail') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.parteViajero.fields.envio_mail_helper') }}</span>
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