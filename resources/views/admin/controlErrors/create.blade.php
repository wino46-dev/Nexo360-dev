@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.controlError.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.control-errors.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="origen">{{ trans('cruds.controlError.fields.origen') }}</label>
                <input class="form-control {{ $errors->has('origen') ? 'is-invalid' : '' }}" type="text" name="origen" id="origen" value="{{ old('origen', '') }}" required>
                @if($errors->has('origen'))
                    <div class="invalid-feedback">
                        {{ $errors->first('origen') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.controlError.fields.origen_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tipo">{{ trans('cruds.controlError.fields.tipo') }}</label>
                <input class="form-control {{ $errors->has('tipo') ? 'is-invalid' : '' }}" type="text" name="tipo" id="tipo" value="{{ old('tipo', '') }}" required>
                @if($errors->has('tipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.controlError.fields.tipo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mensaje">{{ trans('cruds.controlError.fields.mensaje') }}</label>
                <input class="form-control {{ $errors->has('mensaje') ? 'is-invalid' : '' }}" type="text" name="mensaje" id="mensaje" value="{{ old('mensaje', '') }}" required>
                @if($errors->has('mensaje'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mensaje') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.controlError.fields.mensaje_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="descripcion">{{ trans('cruds.controlError.fields.descripcion') }}</label>
                <textarea class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" name="descripcion" id="descripcion">{{ old('descripcion') }}</textarea>
                @if($errors->has('descripcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descripcion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.controlError.fields.descripcion_helper') }}</span>
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