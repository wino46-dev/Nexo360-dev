@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.controlError.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.control-errors.update", [$controlError->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="origen">{{ trans('cruds.controlError.fields.origen') }}</label>
                            <input class="form-control" type="text" name="origen" id="origen" value="{{ old('origen', $controlError->origen) }}" required>
                            @if($errors->has('origen'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('origen') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlError.fields.origen_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tipo">{{ trans('cruds.controlError.fields.tipo') }}</label>
                            <input class="form-control" type="text" name="tipo" id="tipo" value="{{ old('tipo', $controlError->tipo) }}" required>
                            @if($errors->has('tipo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tipo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlError.fields.tipo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="mensaje">{{ trans('cruds.controlError.fields.mensaje') }}</label>
                            <input class="form-control" type="text" name="mensaje" id="mensaje" value="{{ old('mensaje', $controlError->mensaje) }}" required>
                            @if($errors->has('mensaje'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mensaje') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlError.fields.mensaje_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">{{ trans('cruds.controlError.fields.descripcion') }}</label>
                            <textarea class="form-control" name="descripcion" id="descripcion">{{ old('descripcion', $controlError->descripcion) }}</textarea>
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

        </div>
    </div>
</div>
@endsection