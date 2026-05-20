@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.configuracionTpv.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.configuracion-tpvs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="totem_id">{{ trans('cruds.configuracionTpv.fields.totem') }}</label>
                <select class="form-control select2 {{ $errors->has('totem') ? 'is-invalid' : '' }}" name="totem_id" id="totem_id" required>
                    @foreach($totems as $id => $entry)
                        <option value="{{ $id }}" {{ old('totem_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('totem'))
                    <div class="invalid-feedback">
                        {{ $errors->first('totem') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionTpv.fields.totem_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="comercio">{{ trans('cruds.configuracionTpv.fields.comercio') }}</label>
                <input class="form-control {{ $errors->has('comercio') ? 'is-invalid' : '' }}" type="text" name="comercio" id="comercio" value="{{ old('comercio', '') }}" required>
                @if($errors->has('comercio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comercio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionTpv.fields.comercio_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="terminal">{{ trans('cruds.configuracionTpv.fields.terminal') }}</label>
                <input class="form-control {{ $errors->has('terminal') ? 'is-invalid' : '' }}" type="text" name="terminal" id="terminal" value="{{ old('terminal', '') }}" required>
                @if($errors->has('terminal'))
                    <div class="invalid-feedback">
                        {{ $errors->first('terminal') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionTpv.fields.terminal_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="clave_firma">{{ trans('cruds.configuracionTpv.fields.clave_firma') }}</label>
                <input class="form-control {{ $errors->has('clave_firma') ? 'is-invalid' : '' }}" type="text" name="clave_firma" id="clave_firma" value="{{ old('clave_firma', '') }}" required>
                @if($errors->has('clave_firma'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clave_firma') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionTpv.fields.clave_firma_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="conf_puerto">{{ trans('cruds.configuracionTpv.fields.conf_puerto') }}</label>
                <input class="form-control {{ $errors->has('conf_puerto') ? 'is-invalid' : '' }}" type="text" name="conf_puerto" id="conf_puerto" value="{{ old('conf_puerto', '') }}" required>
                @if($errors->has('conf_puerto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conf_puerto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionTpv.fields.conf_puerto_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="version">{{ trans('cruds.configuracionTpv.fields.version') }}</label>
                <input class="form-control {{ $errors->has('version') ? 'is-invalid' : '' }}" type="text" name="version" id="version" value="{{ old('version', '') }}" required>
                @if($errors->has('version'))
                    <div class="invalid-feedback">
                        {{ $errors->first('version') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionTpv.fields.version_helper') }}</span>
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