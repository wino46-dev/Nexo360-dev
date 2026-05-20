@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.zonaComun.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.zona-comuns.update", [$zonaComun->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.zonaComun.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $zonaComun->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zonaComun.fields.nombre_helper') }}</span>
            </div>
            <div class="row">
            
                <div class="form-group col-6">
                    <label for="codigo">{{ trans('cruds.zonaComun.fields.codigo') }}</label>
                    <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', $zonaComun->codigo) }}">
                    @if($errors->has('codigo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('codigo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.zonaComun.fields.codigo_helper') }}</span>
                </div>
                <div class="form-group col-6">
                    <label for="orden">{{ trans('cruds.zonaComun.fields.orden') }}</label>
                    <input class="form-control {{ $errors->has('orden') ? 'is-invalid' : '' }}" type="number" name="orden" id="orden" value="{{ old('orden', $zonaComun->orden) }}">
                    @if($errors->has('orden'))
                        <div class="invalid-feedback">
                            {{ $errors->first('orden') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.zonaComun.fields.orden_helper') }}</span>
                </div>
            </div>
            <div class="form-group">
                <label class="required" for="establecimiento_id">{{ trans('cruds.zonaComun.fields.establecimiento') }}</label>
                <select class="form-control select2 {{ $errors->has('establecimiento') ? 'is-invalid' : '' }}" name="establecimiento_id" id="establecimiento_id" required>
                    @foreach($establecimientos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('establecimiento_id') ? old('establecimiento_id') : $zonaComun->establecimiento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('establecimiento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('establecimiento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zonaComun.fields.establecimiento_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('global') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="global" value="0">
                    <input class="form-check-input" type="checkbox" name="global" id="global" value="1" {{ $zonaComun->global || old('global', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="global">{{ trans('cruds.zonaComun.fields.global') }}</label>
                </div>
                @if($errors->has('global'))
                    <div class="invalid-feedback">
                        {{ $errors->first('global') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zonaComun.fields.global_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('estado') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="estado" value="0">
                    <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1" {{ $zonaComun->estado || old('estado', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="estado">{{ trans('cruds.zonaComun.fields.estado') }}</label>
                </div>
                @if($errors->has('estado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('estado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zonaComun.fields.estado_helper') }}</span>
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