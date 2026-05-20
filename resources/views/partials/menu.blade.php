<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>
    <ul class="c-sidebar-nav">
        <?php
            $isExternal = (auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal());
            $panelPrefix = $isExternal ? 'external' : 'admin';
        ?>
        <li class="c-sidebar-nav-item">
            <a href="{{ route($panelPrefix . ".home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @if(!$isExternal)
        @can('api_doc_access')
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.docs.api') }}" target="_blank"  class="c-sidebar-nav-link">
                        <i class="c-sidebar-nav-icon fas fa-file">

                        </i>
                        API Docs

                    </a>
                </li>
        @endcan
        @endif
        @can('control_sesion_create')
            <li class="c-sidebar-nav-item">
                <a href="{{ route($panelPrefix . ".call-manager.index") }}" class="c-sidebar-nav-link {{ request()->is('admin/call-manager*') || request()->is('external/call-manager*') ? 'c-active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-volume-control-phone">

                    </i>
                    Call Manager

                </a>
            </li>
        @endcan
        @can('wiki_hotel_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route($panelPrefix . ".landing_wiki_hotel") }}" class="c-sidebar-nav-link {{ request()->is("admin/landing_wiki_hotel") || request()->is("external/landing_wiki_hotel") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-layer-group c-sidebar-nav-icon"></i>
                        Wiki Hotel

                </a>
            </li>
        @endcan

        {{-- External: show only limited menu. Add Gestion Reservas here and wrap rest for non-external --}}
        @if($isExternal)
            @can('gestion_reserva_access')
                <li class="c-sidebar-nav-dropdown {{ request()->is("external/firma-check-ins*") ? "c-show" : "" }}
                    {{ request()->is("external/clientes*") ? "c-show" : "" }}
                    {{ request()->is("external/reservas*") ? "c-show" : "" }}
                    {{ request()->is("external/check-ins*") ? "c-show" : "" }} ">
                    <a class="c-sidebar-nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-donate c-sidebar-nav-icon"></i>
                        {{ trans('cruds.gestionReserva.title') }}
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route($panelPrefix . ".reservas.index") }}" class="c-sidebar-nav-link {{ request()->is("external/reservas") || request()->is("external/reservas/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-edit c-sidebar-nav-icon"></i>
                                Reservas
                            </a>
                        </li>
                        @can('check_in_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route($panelPrefix . ".check-ins.index") }}" class="c-sidebar-nav-link {{ request()->is("external/check-ins") || request()->is("external/check-ins/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-allergies c-sidebar-nav-icon"></i>
                                    {{ trans('cruds.checkIn.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
        @endif

        @if(!$isExternal)

        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/teams*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_edit')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.access.select") }}" class="c-sidebar-nav-link {{ request()->is('admin/users/access') || request()->is('admin/users/*/access') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-link c-sidebar-nav-icon"></i>
                                Vinculaciones
                            </a>
                        </li>
                    @endcan
                    @can('team_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.teams.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/teams") || request()->is("admin/teams/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.team.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('auditorium_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/grabacion-tarjeta*") ? "c-show" : "" }} {{ request()->is("admin/respuesta-pagos*") ? "c-show" : "" }} {{ request()->is("admin/sessions*") ? "c-show" : "" }} {{ request()->is("admin/control-errors*") ? "c-show" : "" }} {{ request()->is("admin/control-sesions*") ? "c-show" : "" }} {{ request()->is("admin/evento-home-totems*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }} {{ request()->is("admin/pago-totems*") ? "c-show" : "" }} {{ request()->is("admin/audit-export*") ? "c-show" : "" }} {{ request()->is("admin/ftp-upload-logs*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-sign-in-alt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.auditorium.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('control_error_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ url('/telescope') }}" class="c-sidebar-nav-link {{ request()->is("telescope*") ? "c-active" : "" }}" target="_blank">
                                <i class="fa-fw fas fa-microscope c-sidebar-nav-icon"></i>
                                <span>Monitoring</span>
                            </a>
                        </li>
                    @endcan
                    @can('control_error_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.control-errors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/control-errors") || request()->is("admin/control-errors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-exclamation-triangle c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.controlError.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('control_sesion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.control-sesions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/control-sesions") || request()->is("admin/control-sesions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-phone-volume c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.controlSesion.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('evento_home_totem_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.evento-home-totems.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/evento-home-totems") || request()->is("admin/evento-home-totems/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-accusoft c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.eventoHomeTotem.title') }}
                            </a>
                        </li>
                    @endcan
                        @can('grabacion_tarjetum_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.grabacion-tarjeta.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/grabacion-tarjeta") || request()->is("admin/grabacion-tarjeta/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-id-card-alt c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.grabacionTarjetum.title') }}
                                </a>
                            </li>
                        @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan

                        @can('auditorium_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route('admin.audit-export.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/audit-export') ? 'c-active' : '' }}">
                                    <i class="fa-fw fas fa-file-excel c-sidebar-nav-icon"></i>
                                    Exportar logs
                                </a>
                            </li>
                        @endcan

                        @can('ftp_upload_log_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route('admin.ftp-upload-logs.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/ftp-upload-logs') || request()->is('admin/ftp-upload-logs/*') ? 'c-active' : '' }}">
                                    <i class="fa-fw fas fa-cloud-upload-alt c-sidebar-nav-icon"></i>
                                    Logs FTP partes
                                </a>
                            </li>
                        @endcan

                        @can('respuesta_pago_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.respuesta-pagos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/respuesta-pagos") || request()->is("admin/respuesta-pagos/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-arrows-alt-h c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.respuestaPago.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('pago_totem_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.pago-totems.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pago-totems") || request()->is("admin/pago-totems/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fab fa-adversal c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.pagoTotem.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('session_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.sessions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sessions") || request()->is("admin/sessions/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-sign-out-alt c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.session.title') }}
                                </a>
                            </li>
                        @endcan
                </ul>
            </li>
        @endcan
        @can('configuracion_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/configuracion-videos*") ? "c-show" : "" }} {{ request()->is("admin/configuracion-grabadors*") ? "c-show" : "" }} {{ request()->is("admin/configuracion-tpvs*") ? "c-show" : "" }} {{ request()->is("admin/ciudads*") ? "c-show" : "" }} {{ request()->is("admin/layout-homes*") ? "c-show" : "" }} {{ request()->is("admin/pais*") ? "c-show" : "" }} {{ request()->is("admin/provincia*") ? "c-show" : "" }} {{ request()->is("admin/tipo-eventos*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.configuracion.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('ciudad_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ciudads.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ciudads") || request()->is("admin/ciudads/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hospital-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ciudad.title') }}
                            </a>
                        </li>
                    @endcan
                        @can('configuracion_grabador_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.configuracion-grabadors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/configuracion-grabadors") || request()->is("admin/configuracion-grabadors/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-glasses c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.configuracionGrabador.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('configuracion_tpv_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.configuracion-tpvs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/configuracion-tpvs") || request()->is("admin/configuracion-tpvs/*") ? "c-active" : "" }}">
                                    <i class="fa-fw far fa-credit-card c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.configuracionTpv.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('configuracion_video_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route("admin.configuracion-videos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/configuracion-videos") || request()->is("admin/configuracion-videos/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fab fa-500px c-sidebar-nav-icon">

                                    </i>
                                    {{ trans('cruds.configuracionVideo.title') }}
                                </a>
                            </li>
                        @endcan
                    @can('layout_home_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.layout-homes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/layout-homes") || request()->is("admin/layout-homes/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-edit c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.layoutHome.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('pai_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.pais.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pais") || request()->is("admin/pais/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-globe c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.pai.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('provincium_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.provincia.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/provincia") || request()->is("admin/provincia/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-map-signs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.provincium.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('tipo_evento_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tipo-eventos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tipo-eventos") || request()->is("admin/tipo-eventos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-typo3 c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.tipoEvento.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('gestion_reserva_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/firma-check-ins*") || request()->is("external/firma-check-ins*") ? "c-show" : "" }}

                {{ request()->is("admin/clientes*") || request()->is("external/clientes*") ? "c-show" : "" }}
                {{ request()->is("admin/reservas*") || request()->is("external/reservas*") ? "c-show" : "" }}
                {{ request()->is("admin/check-ins*") || request()->is("external/check-ins*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-donate c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.gestionReserva.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">

                    <li class="c-sidebar-nav-item">
                        <a href="{{ route($panelPrefix . ".reservas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reservas") || request()->is("admin/reservas/*") || request()->is("external/reservas") || request()->is("external/reservas/*") ? "c-active" : "" }}">
                            <i class="fa-fw far fa-edit c-sidebar-nav-icon"></i>
                            Reservas
                        </a>
                    </li>

                        @can('check_in_access')
                            <li class="c-sidebar-nav-item">
                                <a href="{{ route($panelPrefix . ".check-ins.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/check-ins") || request()->is("admin/check-ins/*") || request()->is("external/check-ins") || request()->is("external/check-ins/*") ? "c-active" : "" }}">
                                    <i class="fa-fw fas fa-allergies c-sidebar-nav-icon"></i>
                                    {{ trans('cruds.checkIn.title') }}
                                </a>
                            </li>
                        @endcan

                    @can('reserva_access')
                        <!-- <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.reservas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reservas") || request()->is("admin/reservas/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-check-double c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.reserva.title') }}
                            </a>
                        </li> -->
                    @endcan


                </ul>
            </li>
        @endcan
        @can('organizacion_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/sociedads*") ? "c-show" : "" }} {{ request()->is("admin/establecimientos*") ? "c-show" : "" }} {{ request()->is("admin/habitacions*") ? "c-show" : "" }} {{ request()->is("admin/totems*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-sitemap c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.organizacion.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('sociedad_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.sociedads.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/sociedads") || request()->is("admin/sociedads/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.sociedad.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('establecimiento_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.establecimientos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/establecimientos") || request()->is("admin/establecimientos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hotel c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.establecimiento.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('zona_comun_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.zona-comuns.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/zona-comuns") || request()->is("admin/zona-comuns/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.zonaComun.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('habitacion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.habitacions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/habitacions") || request()->is("admin/habitacions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-chess-rook c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.habitacion.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('totem_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.totems.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/totems") || request()->is("admin/totems/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-tv c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.totem.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('wiki_hotel_access_disabled')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/landing_wiki_hotel") ? "c-show" : "" }} {{ request()->is("admin/doc-hotel-reception-infos*") ? "c-show" : "" }} {{ request()->is("admin/doc-hotel-estado-cajas*") ? "c-show" : "" }} {{ request()->is("admin/doc-info-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-servicio-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-metodo-pago-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-ubicacion-hotels*") ? "c-show" : "" }} {{ request()->is("admin/dock-stock-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-incidencia-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-no-deseado-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-habitacion-hotels*") ? "c-show" : "" }} {{ request()->is("admin/doc-tarifa-hotels*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-wikipedia-w c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.wikiHotel.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.landing_wiki_hotel") }}" class="c-sidebar-nav-link {{ request()->is("admin/landing_wiki_hotel") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-layer-group c-sidebar-nav-icon"></i>
                            Wiki Hotel
                        </a>
                    </li>
                    @can('doc_hotel_reception_info_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-hotel-reception-infos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-hotel-reception-infos") || request()->is("admin/doc-hotel-reception-infos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-book c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docHotelReceptionInfo.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_hotel_estado_caja_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-hotel-estado-cajas.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-hotel-estado-cajas") || request()->is("admin/doc-hotel-estado-cajas/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-box c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docHotelEstadoCaja.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_info_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-info-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-info-hotels") || request()->is("admin/doc-info-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-info c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docInfoHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_servicio_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-servicio-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-servicio-hotels") || request()->is("admin/doc-servicio-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-concierge-bell c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docServicioHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_metodo_pago_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-metodo-pago-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-metodo-pago-hotels") || request()->is("admin/doc-metodo-pago-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-amazon-pay c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docMetodoPagoHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_ubicacion_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-ubicacion-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-ubicacion-hotels") || request()->is("admin/doc-ubicacion-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hospital-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docUbicacionHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('dock_stock_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.dock-stock-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/dock-stock-hotels") || request()->is("admin/dock-stock-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-store c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.dockStockHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_incidencia_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-incidencia-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-incidencia-hotels") || request()->is("admin/doc-incidencia-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-accessible-icon c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docIncidenciaHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_no_deseado_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-no-deseado-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-no-deseado-hotels") || request()->is("admin/doc-no-deseado-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-times-circle c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docNoDeseadoHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_habitacion_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-habitacion-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-habitacion-hotels") || request()->is("admin/doc-habitacion-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-chess-rook c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docHabitacionHotel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('doc_tarifa_hotel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.doc-tarifa-hotels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/doc-tarifa-hotels") || request()->is("admin/doc-tarifa-hotels/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-money-bill-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.docTarifaHotel.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @if(\Illuminate\Support\Facades\Schema::hasColumn('teams', 'owner_id') && \App\Models\Team::where('owner_id', auth()->user()->id)->exists())
            <li class="c-sidebar-nav-item">
                <a class="{{ request()->is("admin/team-members") || request()->is("admin/team-members/*") ? "c-active" : "" }} c-sidebar-nav-link" href="{{ route("admin.team-members.index") }}">
                    <i class="c-sidebar-nav-icon fa-fw fa fa-users">
                    </i>
                    <span>{{ trans("global.team-members") }}</span>
                </a>
            </li>
        @endif
        @endif
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
