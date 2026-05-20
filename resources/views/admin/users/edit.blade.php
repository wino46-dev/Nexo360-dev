@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="totem_id">{{ trans('cruds.user.fields.totem') }}</label>
                <select class="form-control select2 {{ $errors->has('totem') ? 'is-invalid' : '' }}" name="totem_id" id="totem_id">
                    @foreach($totems as $id => $entry)
                        <option value="{{ $id }}" {{ (old('totem_id') ? old('totem_id') : $user->totem->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('totem'))
                    <div class="invalid-feedback">
                        {{ $errors->first('totem') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.totem_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="type">Tipo de usuario</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value="internal" {{ old('type', $user->type ?? 'internal') == 'internal' ? 'selected' : '' }}>Interno</option>
                    <option value="external" {{ old('type', $user->type ?? '') == 'external' ? 'selected' : '' }}>Externo</option>
                    <option value="totem" {{ old('type', $user->type ?? '') == 'totem' ? 'selected' : '' }}>Totem</option>
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                @endif
            </div>

            <div class="row">
                <div class="form-group col-4">
                    <label for="totem_id">Sip Identity</label>
                    <input class="form-control {{ $errors->has('sip_identity') ? 'is-invalid' : '' }}" type="text" name="sip_identity" id="sip_identity" value="{{ old('sip_identity', $user->sip_identity) }}">
                </div>
                <div class="form-group col-4">
                    <label for="pms_password">Password PMS</label>
                    <input class="form-control {{ $errors->has('pms_password') ? 'is-invalid' : '' }}" type="text" name="pms_password" id="pms_password"  value="{{ old('pms_password', $user->pms_password) }}">
                </div>
                <div class="form-group col-4">
                    <div class="form-check {{ $errors->has('login_pms_establecimiento') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="login_pms_establecimiento" value="0">
                        <input class="form-check-input" type="checkbox" name="login_pms_establecimiento" id="login_pms_establecimiento" value="1" {{ $user->login_pms_establecimiento || old('login_pms_establecimiento', 0) === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="login_pms_establecimiento">Login con PMS del Establecimiento</label>
                    </div>

                </div>

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
