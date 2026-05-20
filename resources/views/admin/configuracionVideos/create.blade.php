@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.configuracionVideo.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.configuracion-videos.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="totem_id">{{ trans('cruds.configuracionVideo.fields.totem') }}</label>
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
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.totem_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sip_identity">{{ trans('cruds.configuracionVideo.fields.sip_identity') }}</label>
                <input class="form-control {{ $errors->has('sip_identity') ? 'is-invalid' : '' }}" type="text" name="sip_identity" id="sip_identity" value="{{ old('sip_identity', '') }}" required>
                @if($errors->has('sip_identity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sip_identity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_identity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="display_name">{{ trans('cruds.configuracionVideo.fields.display_name') }}</label>
                <input class="form-control {{ $errors->has('display_name') ? 'is-invalid' : '' }}" type="text" name="display_name" id="display_name" value="{{ old('display_name', '') }}" required>
                @if($errors->has('display_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('display_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.display_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sip_registar">{{ trans('cruds.configuracionVideo.fields.sip_registar') }}</label>
                <input class="form-control {{ $errors->has('sip_registar') ? 'is-invalid' : '' }}" type="text" name="sip_registar" id="sip_registar" value="{{ old('sip_registar', '') }}" required>
                @if($errors->has('sip_registar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sip_registar') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_registar_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="username">{{ trans('cruds.configuracionVideo.fields.username') }}</label>
                <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" id="username" value="{{ old('username', '') }}" required>
                @if($errors->has('username'))
                    <div class="invalid-feedback">
                        {{ $errors->first('username') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.username_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.configuracionVideo.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="text" name="password" id="password" value="{{ old('password', '') }}" required>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sip_identity_destino">{{ trans('cruds.configuracionVideo.fields.sip_identity_destino') }}</label>
                <input class="form-control {{ $errors->has('sip_identity_destino') ? 'is-invalid' : '' }}" type="text" name="sip_identity_destino" id="sip_identity_destino" value="{{ old('sip_identity_destino', '') }}">
                @if($errors->has('sip_identity_destino'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sip_identity_destino') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionVideo.fields.sip_identity_destino_helper') }}</span>
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