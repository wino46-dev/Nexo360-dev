@extends('layouts.admin')
@section('content')
    @php($isExternal = auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal())
    <div class="row">
        {{-- Profile editing disabled: only password change is allowed --}}
        @if(false)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.my_profile') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.updateProfile') }}">
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required" for="title">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text"
                                name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
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
        @endif
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.change_password') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        <div class="form-group">
                            <label class="required" for="title">New {{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                                name="password" id="password" required>
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required" for="title">Repeat New
                                {{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control" type="password" name="password_confirmation"
                                id="password_confirmation" required>
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
        @if(!$isExternal)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Configuración PMS
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.updatePmsPassword') }}" class="row">
                        @csrf
                        <div class="form-group col-md-8 ">
                            <div><label class="required" for="title">Password PMS</label></div>
                            <div class="d-flex bd-highlight">
                                <div class="flex-grow-1 bd-highlight">
                                    <input class="form-control" type="password" name="pms_password" id="pms_password"
                                        value="{{auth()->user()->pms_password}}"
                                        required>
                                </div>
                                <div class="bd-highlight ">
                                    <button class="btn btn-outline-secondary ml-2" type="button" id="togglePassword">
                                        Mostrar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-7">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- Delete account disabled --}}
        @if(false)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.delete_account') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.destroyProfile') }}"
                        onsubmit="return prompt('{{ __('global.delete_account_warning') }}') == '{{ auth()->user()->email }}'">
                        @csrf
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.delete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @if (Route::has('profile.password.toggleTwoFactor'))
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ trans('global.two_factor.title') }}
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password.toggleTwoFactor') }}">
                            @csrf
                            <div class="form-group">
                                <button class="btn btn-danger" type="submit">
                                    {{ auth()->user()->two_factor ? trans('global.two_factor.disable') : trans('global.two_factor.enable') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#togglePassword').on('click', function() {
                const passwordInput = $('#pms_password');
                const isPassword = passwordInput.attr('type') === 'password';
                passwordInput.attr('type', isPassword ? 'text' : 'password');
                $(this).text(isPassword ? 'Ocultar' : 'Mostrar');
            });
        });
    </script>
@endsection
