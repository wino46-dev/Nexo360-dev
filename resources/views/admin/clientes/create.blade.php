@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.cliente.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.clientes.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.cliente.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="apellidos">{{ trans('cruds.cliente.fields.apellidos') }}</label>
                <input class="form-control {{ $errors->has('apellidos') ? 'is-invalid' : '' }}" type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', '') }}" required>
                @if($errors->has('apellidos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('apellidos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.apellidos_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nif">{{ trans('cruds.cliente.fields.nif') }}</label>
                <input class="form-control {{ $errors->has('nif') ? 'is-invalid' : '' }}" type="text" name="nif" id="nif" value="{{ old('nif', '') }}" required>
                @if($errors->has('nif'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nif') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.nif_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telefono">{{ trans('cruds.cliente.fields.telefono') }}</label>
                <input class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" type="text" name="telefono" id="telefono" value="{{ old('telefono', '') }}">
                @if($errors->has('telefono'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.telefono_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pais_id">{{ trans('cruds.cliente.fields.pais') }}</label>
                <select class="form-control select2 {{ $errors->has('pais') ? 'is-invalid' : '' }}" name="pais_id" id="pais_id" required>
                    @foreach($pais as $id => $entry)
                        <option value="{{ $id }}" {{ old('pais_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pais'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pais') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.pais_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="provincia_id">{{ trans('cruds.cliente.fields.provincia') }}</label>
                <select class="form-control select2 {{ $errors->has('provincia') ? 'is-invalid' : '' }}" name="provincia_id" id="provincia_id">
                    @foreach($provincias as $id => $entry)
                        <option value="{{ $id }}" {{ old('provincia_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('provincia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('provincia') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.provincia_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ciudad_id">{{ trans('cruds.cliente.fields.ciudad') }}</label>
                <select class="form-control select2 {{ $errors->has('ciudad') ? 'is-invalid' : '' }}" name="ciudad_id" id="ciudad_id">
                    @foreach($ciudads as $id => $entry)
                        <option value="{{ $id }}" {{ old('ciudad_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ciudad'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ciudad') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.ciudad_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cod_postal">{{ trans('cruds.cliente.fields.cod_postal') }}</label>
                <input class="form-control {{ $errors->has('cod_postal') ? 'is-invalid' : '' }}" type="text" name="cod_postal" id="cod_postal" value="{{ old('cod_postal', '') }}">
                @if($errors->has('cod_postal'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cod_postal') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.cod_postal_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="direccion">{{ trans('cruds.cliente.fields.direccion') }}</label>
                <input class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}" type="text" name="direccion" id="direccion" value="{{ old('direccion', '') }}">
                @if($errors->has('direccion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('direccion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.direccion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.cliente.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cliente.fields.email_helper') }}</span>
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