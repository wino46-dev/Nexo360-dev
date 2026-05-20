<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    @yield('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="display: none">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else

                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">


                                    @can('user_management_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.userManagement.title') }}
                                        </a>
                                    @endcan
                                    @can('permission_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.permissions.index') }}">
                                            {{ trans('cruds.permission.title') }}
                                        </a>
                                    @endcan
                                    @can('role_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.roles.index') }}">
                                            {{ trans('cruds.role.title') }}
                                        </a>
                                    @endcan
                                    @can('user_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.users.index') }}">
                                            {{ trans('cruds.user.title') }}
                                        </a>
                                    @endcan
                                    @can('team_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.teams.index') }}">
                                            {{ trans('cruds.team.title') }}
                                        </a>
                                    @endcan
                                    @can('auditorium_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.auditorium.title') }}
                                        </a>
                                    @endcan
                                    @can('control_error_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.control-errors.index') }}">
                                            {{ trans('cruds.controlError.title') }}
                                        </a>
                                    @endcan
                                    @can('evento_home_totem_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.evento-home-totems.index') }}">
                                            {{ trans('cruds.eventoHomeTotem.title') }}
                                        </a>
                                    @endcan
                                    @can('control_sesion_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.control-sesions.index') }}">
                                            {{ trans('cruds.controlSesion.title') }}
                                        </a>
                                    @endcan
                                    @can('configuracion_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.configuracion.title') }}
                                        </a>
                                    @endcan
                                    @can('ciudad_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.ciudads.index') }}">
                                            {{ trans('cruds.ciudad.title') }}
                                        </a>
                                    @endcan
                                    @can('layout_home_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.layout-homes.index') }}">
                                            {{ trans('cruds.layoutHome.title') }}
                                        </a>
                                    @endcan
                                    @can('pai_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.pais.index') }}">
                                            {{ trans('cruds.pai.title') }}
                                        </a>
                                    @endcan
                                    @can('provincium_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.provincia.index') }}">
                                            {{ trans('cruds.provincium.title') }}
                                        </a>
                                    @endcan
                                    @can('tipo_evento_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.tipo-eventos.index') }}">
                                            {{ trans('cruds.tipoEvento.title') }}
                                        </a>
                                    @endcan
                                    @can('gestion_reserva_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.gestionReserva.title') }}
                                        </a>
                                    @endcan
                                    @can('cliente_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.clientes.index') }}">
                                            {{ trans('cruds.cliente.title') }}
                                        </a>
                                    @endcan
                                    @can('reserva_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.reservas.index') }}">
                                            {{ trans('cruds.reserva.title') }}
                                        </a>
                                    @endcan
                                    @can('check_in_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.check-ins.index') }}">
                                            {{ trans('cruds.checkIn.title') }}
                                        </a>
                                    @endcan
                                    @can('organizacion_access')
                                        <a class="dropdown-item disabled" href="#">
                                            {{ trans('cruds.organizacion.title') }}
                                        </a>
                                    @endcan
                                    @can('sociedad_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.sociedads.index') }}">
                                            {{ trans('cruds.sociedad.title') }}
                                        </a>
                                    @endcan
                                    @can('establecimiento_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.establecimientos.index') }}">
                                            {{ trans('cruds.establecimiento.title') }}
                                        </a>
                                    @endcan
                                    @can('habitacion_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.habitacions.index') }}">
                                            {{ trans('cruds.habitacion.title') }}
                                        </a>
                                    @endcan
                                    @can('totem_access')
                                        <a class="dropdown-item ml-3" href="{{ route('frontend.totems.index') }}">
                                            {{ trans('cruds.totem.title') }}
                                        </a>
                                    @endcan

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @if(session('message'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

<script src="{{ asset('js/main.js') }}"></script>

@yield('scripts')

</html>
