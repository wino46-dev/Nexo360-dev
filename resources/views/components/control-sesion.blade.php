<!-- Button trigger modal -->
<div class="container-fluid">

    @if (!empty($showComponent) && $showComponent)

        @if ($current_user_count >= 1)
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-success" role="alert"><b>Sesión iniciada ({{ $current_user_sesion->id }}) =>
                        </b> {{ $valor_string }}</div>

                </div>
                @if (Route::currentRouteName() === $panelPrefix.'.control-sesions.manager' ||
                        Route::currentRouteName() === $panelPrefix.'.call-manager.index')
                    <div class="col-md-6 text-right">
                        @unless(request()->routeIs('external.*'))
                        <button type="button" id="llamada_inversa_btn" style="width:150px" class="btn  btn-primary mr-3" onclick="llamada_inversa()">
                            <i class="fa fa-phone" aria-hidden="true"></i> Llamada Inversa
                        </button>
                        @endunless
                        @if (Route::currentRouteName() === $panelPrefix.'.call-manager.index')
                        <button type="button" id="btn_ver_incidencias" class="btn btn-info mr-3" data-toggle="modal" data-target="#modalIncidenciasHotel">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Incidencias
                        </button>
                        @endif
                        @unless(request()->routeIs('external.*'))
                        <button type="button" class="btn  btn-secondary mr-3" data-toggle="modal"
                            data-target="#modalGrabacionAutonoma">
                            Grabación Autónoma
                        </button>
                        @endunless
                        <button type="button" class="btn  btn-warning" data-toggle="modal"
                            data-target="#exampleModalCerrar">
                            Cerrar sesión
                        </button>
                    </div>
                @endif

            </div>
        @else
            <div class="row ">
                <div class="col-md-6">
                    <div class="alert alert-sm alert-danger" role="alert" style="text-align: center">No hay sesiones
                        activas en este momento</div>
                </div>
                @if (Route::currentRouteName() === $panelPrefix.'.control-sesions.manager' ||
                        Route::currentRouteName() === $panelPrefix.'.call-manager.index')
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn   btn-success" data-toggle="modal"
                            data-target="#exampleModalIniciar">
                            Iniciar sesión de control
                        </button>
                    </div>
                @endif
            </div>

        @endif
    @endif
</div>





<!-- Modal Iniciar Sesión -->
<div class="modal fade" id="exampleModalIniciar" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.controlSesion.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route($panelPrefix.'.control-sesions.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            // Precompute list of receptor_ids locked by Admin (non-external) sessions.
                            // Ensure the variable always exists to avoid undefined variable notices.
                            if (!isset($adminLockedReceptors) || !is_array($adminLockedReceptors)) {
                                $adminLockedReceptors = [];
                            }
                            if (empty($adminLockedReceptors)) {
                                $adminLockedReceptors = collect($active_sesions ?? [])->filter(function($s){
                                    $em = $s->emisor ?? null;
                                    $isExternal = $em && method_exists($em, 'isExternal') ? $em->isExternal() : false;
                                    return !$isExternal; // locked when owned by Admin
                                })->pluck('receptor_id')->all();
                            }
                        @endphp
                        <div class="form-group" style="display: none">
                            <label class="required"
                                for="emisor_id">{{ trans('cruds.controlSesion.fields.emisor') }}</label>
                            <select class="form-control select2 {{ $errors->has('emisor') ? 'is-invalid' : '' }}"
                                name="emisor_id" id="emisor_id" required>
                                <option value="{{ $emisors }}">{{ $emisors }}</option>
                            </select>
                            @if ($errors->has('emisor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('emisor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlSesion.fields.emisor_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="required"
                                for="receptor_id">{{ trans('cruds.controlSesion.fields.receptor') }}</label>
                            @php
                                // Build list of receptor_ids that are already locked by an Admin (non-external) emisor
                                $adminLockedReceptors = collect($active_sesions ?? [])->filter(function($s){
                                    $em = $s->emisor ?? null;
                                    $isExternal = $em && method_exists($em, 'isExternal') ? $em->isExternal() : false;
                                    return !$isExternal; // lock when owned by Admin
                                })->pluck('receptor_id')->all();
                            @endphp
                            <select class="form-control select2 {{ $errors->has('receptor') ? 'is-invalid' : '' }}"
                                name="receptor_id" id="receptor_id" required>

                                @foreach ($receptors as $id => $entry)
                                    @php $locked = (!request()->routeIs('external.*')) && in_array($entry->user, $adminLockedReceptors, true); @endphp
                                    <option value="{{ $entry->user }}" {{ old('receptor_id') == $id ? 'selected' : '' }} {{ $locked ? 'disabled' : '' }}>
                                        &nbsp;&nbsp;&nbsp;{{ $entry->establecimiento }} - {{ $entry->totem }}@if($locked) — Ocupado (Admin) @endif
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('receptor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('receptor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlSesion.fields.receptor_helper') }}</span>
                        </div>
                        <div class="form-group" style="display: none">
                            <div class="form-check {{ $errors->has('estado_sesion') ? 'is-invalid' : '' }}">
                                <input type="hidden" name="estado_sesion" value="1">
                                <input class="form-check-input" type="checkbox" name="estado_sesion" id="estado_sesion"
                                    value="1" {{ old('estado_sesion', 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="estado_sesion">{{ trans('cruds.controlSesion.fields.estado_sesion') }}</label>
                            </div>
                            @if ($errors->has('estado_sesion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('estado_sesion') }}
                                </div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.controlSesion.fields.estado_sesion_helper') }}</span>
                        </div>
                        <div class="form-group text-right">
                            <button class="btn btn-success px-5" type="submit">
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Cerrar sesión -->
<div class="modal fade" id="exampleModalCerrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    Cerrar {{ trans('cruds.controlSesion.title_singular') }}
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route($panelPrefix.'.control-sesions.cerrar') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($current_user_sesion->receptor->id))
                            <input type="hidden" value="{{ $current_user_sesion->receptor->id }}" name="receptor_id">
                        @endif
                        <div class="form-group" style="display: none">
                            <label class="required"
                                for="emisor_id">{{ trans('cruds.controlSesion.fields.emisor') }}</label>
                            <select class="form-control select2 {{ $errors->has('emisor') ? 'is-invalid' : '' }}"
                                name="emisor_id" id="emisor_id" required>
                                <option value="{{ $emisors }}">{{ $emisors }}</option>
                            </select>
                            @if ($errors->has('emisor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('emisor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlSesion.fields.emisor_helper') }}</span>
                        </div>

                        <div class="form-group">
                            @if (isset($current_user_sesion->receptor->name))
                                <b>{{ $current_user_sesion->receptor->name }}</b> -> <em>desde
                                    {{ $current_user_sesion->created_at }}</em>&nbsp;&nbsp;&nbsp;
                            @endif
                            <button class="btn btn-sm btn-danger" type="submit">
                                Cerrar Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function llamada_inversa() {
        let label_llamada = $('#llamada_inversa_btn').html();
        $('#llamada_inversa_btn').html(loading_sm)
        $.ajax({
            url: "{{ url($panelPrefix . '/call-manager/llamada-inversa') }}",
            method: 'POST',
            success: function(response) {
                if (response['status'] == 'error') {
                    Toast.fire({
                        icon: "error",
                        title: response['message']
                    });
                }
                $('#llamada_inversa_btn').html(label_llamada)
            },
            error: function(response) {
                Toast.fire({
                    icon: "error",
                    title: 'Error al realizar la llamada Inversa'
                });
            },
        });
    }
</script>
