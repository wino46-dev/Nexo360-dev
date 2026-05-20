@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.provincium.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.provincia.update", [$provincium->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.provincium.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $provincium->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.provincium.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pais_id">{{ trans('cruds.provincium.fields.pais') }}</label>
                <select class="form-control select2 {{ $errors->has('pais') ? 'is-invalid' : '' }}" name="pais_id" id="pais_id" required>
                    @foreach($pais as $id => $entry)
                        <option value="{{ $id }}" {{ (old('pais_id') ? old('pais_id') : $provincium->pais->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pais'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pais') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.provincium.fields.pais_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="iso">{{ trans('cruds.provincium.fields.iso') }}</label>
                <input class="form-control {{ $errors->has('iso') ? 'is-invalid' : '' }}" type="text" name="iso" id="iso" value="{{ old('iso', $provincium->iso) }}">
                @if($errors->has('iso'))
                    <div class="invalid-feedback">
                        {{ $errors->first('iso') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.provincium.fields.iso_helper') }}</span>
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