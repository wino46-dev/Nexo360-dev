@extends('layouts.admin')
@section('content')
<style>
    /* Landing Wiki Hotel: asegurar que todas las tablas ocupen el ancho completo */
    .tab-content,
    .tab-content .tab-pane,
    .card,
    .card-body,
    .dataTables_wrapper,
    .dataTables_scroll,
    .dataTables_scrollHead,
    .dataTables_scrollHeadInner,
    .dataTables_scrollBody {
        width: 100% !important;
    }

    table.table,
    table.dataTable {
        width: 100% !important;
        table-layout: auto !important;
    }

    /* Evitar márgenes que reduzcan el ancho útil */
    .dataTables_wrapper .row {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    /* Forzar columnas internas de DataTables a ocupar todo el ancho disponible */
    .dataTables_wrapper .row > [class*="col-"] {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }

    /* Asegurar que los contenedores responsivos no limiten el ancho de la tabla */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto;
    }

    /* Fuerza DataTables a calcular y mantener anchos consistentes */
    table.dataTable,
    .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    table.dataTable thead th,
    table.dataTable tbody td {
        box-sizing: border-box;
        white-space: nowrap;
    }
    /* Ignorar anchos inline heredados (p.ej. width="10") para evitar desfases */
    table.dataTable thead th[style],
    table.dataTable tbody td[style] {
        width: auto !important;
    }
</style>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
        <div>{{ __('Wiki Hotel – Gestión unificada') }}</div>
        @if(empty($hasActiveControlSesion))
        <div class="d-flex align-items-center">
            <label class="mb-0 mr-2">{{ __('Selecciona hotel') }}:</label>
            <select id="selectorHotel" class="form-control" style="min-width:260px">
                <option value="">— {{ __('Seleccione un establecimiento') }} —</option>
                @foreach($establecimientos as $h)
                    <option value="{{ $h->id }}" data-codigo="{{ $h->codigo }}">{{ $h->nombre }} ({{ $h->codigo }})</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    <div class="card-body">
        <div id="placeholderSeleccion" class="alert alert-info" style="display:none;">
            {{ __('Seleccione un hotel para gestionar su información.') }}
        </div>
        <ul class="nav nav-tabs" id="wikiHotelTabs" role="tablist" style="display:none;">
            @can('doc_info_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-info-hotel" data-toggle="tab" href="#pane-info-hotel" role="tab" aria-controls="pane-info-hotel" aria-selected="true">
                    {{ trans('cruds.docInfoHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_hotel_reception_info_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link @cannot('doc_info_hotel_access') active @endcannot" id="tab-recepcion" data-toggle="tab" href="#pane-recepcion" role="tab" aria-controls="pane-recepcion" aria-selected="false">
                    {{ trans('cruds.docHotelReceptionInfo.title') }}
                </a>
            </li>
            @endcan
            @can('doc_hotel_estado_caja_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(!Gate::allows('doc_info_hotel_access') && !Gate::allows('doc_hotel_reception_info_access')) active @endif" id="tab-estado-caja" data-toggle="tab" href="#pane-estado-caja" role="tab" aria-controls="pane-estado-caja" aria-selected="false">
                    {{ trans('cruds.docHotelEstadoCaja.title') }}
                </a>
            </li>
            @endcan
            @can('doc_servicio_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-servicio" data-toggle="tab" href="#pane-servicio" role="tab" aria-controls="pane-servicio" aria-selected="false">
                    {{ trans('cruds.docServicioHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_metodo_pago_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-metodo-pago" data-toggle="tab" href="#pane-metodo-pago" role="tab" aria-controls="pane-metodo-pago" aria-selected="false">
                    {{ trans('cruds.docMetodoPagoHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_ubicacion_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-ubicacion" data-toggle="tab" href="#pane-ubicacion" role="tab" aria-controls="pane-ubicacion" aria-selected="false">
                    {{ trans('cruds.docUbicacionHotel.title') }}
                </a>
            </li>
            @endcan
            @can('dock_stock_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-stock" data-toggle="tab" href="#pane-stock" role="tab" aria-controls="pane-stock" aria-selected="false">
                    {{ trans('cruds.dockStockHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_incidencia_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-incidencias" data-toggle="tab" href="#pane-incidencias" role="tab" aria-controls="pane-incidencias" aria-selected="false">
                    {{ trans('cruds.docIncidenciaHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_no_deseado_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-no-deseado" data-toggle="tab" href="#pane-no-deseado" role="tab" aria-controls="pane-no-deseado" aria-selected="false">
                    {{ trans('cruds.docNoDeseadoHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_habitacion_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-habitacion" data-toggle="tab" href="#pane-habitacion" role="tab" aria-controls="pane-habitacion" aria-selected="false">
                    {{ trans('cruds.docHabitacionHotel.title') }}
                </a>
            </li>
            @endcan
            @can('doc_tarifa_hotel_access')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-tarifa" data-toggle="tab" href="#pane-tarifa" role="tab" aria-controls="pane-tarifa" aria-selected="false">
                    {{ trans('cruds.docTarifaHotel.title') }}
                </a>
            </li>
            @endcan
        </ul>
        <div class="tab-content mt-3" id="wikiHotelTabContent" style="display:none;">
            @can('doc_hotel_reception_info_access')
            <div class="tab-pane fade @cannot('doc_info_hotel_access') show active @endcannot" id="pane-recepcion" role="tabpanel" aria-labelledby="tab-recepcion">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_hotel_reception_info_create')
                        <button id="btn-create-recepcion" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docHotelReceptionInfo.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <div id="recepcionShowPanel" class="mb-3"></div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-Recepcion" id="tablaRecepcion" style="display:none;">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.id') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.hour_open') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.hour_close') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.open_holiday') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.close_holiday') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.acces_type_after_hour') }}</th>
                            <th>{{ trans('cruds.docHotelReceptionInfo.fields.box_photo') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_hotel_estado_caja_access')
            <div class="tab-pane fade @if(!Gate::allows('doc_info_hotel_access') && !Gate::allows('doc_hotel_reception_info_access')) show active @endif" id="pane-estado-caja" role="tabpanel" aria-labelledby="tab-estado-caja">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_hotel_estado_caja_create')
                        <button id="btn-create-estado-caja" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docHotelEstadoCaja.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-EstadoCaja" id="tablaEstadoCaja">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.caja') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.code') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.room') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.room_status') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.cliente') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.documento_cliente') }}</th>
                            <th>{{ trans('cruds.docHotelEstadoCaja.fields.pay') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_info_hotel_access')
            <div class="tab-pane fade show active" id="pane-info-hotel" role="tabpanel" aria-labelledby="tab-info-hotel">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_info_hotel_create')
                        <button id="btn-create-info-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docInfoHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <div id="infoHotelShowPanel" class="mb-3"></div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-InfoHotel" id="tablaInfoHotel" style="display:none;">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docInfoHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.hotel') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.categoria') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.pais') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.provincia') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.ciudad') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.direccion') }}</th>
                            <th>{{ trans('cruds.docInfoHotel.fields.codigo_postal') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_servicio_hotel_access')
            <div class="tab-pane fade" id="pane-servicio" role="tabpanel" aria-labelledby="tab-servicio">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_servicio_hotel_create')
                        <button id="btn-create-servicio-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docServicioHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-ServicioHotel" id="tablaServicioHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docServicioHotel.fields.hotel') }}</th>
                            <th>{{ trans('cruds.docServicioHotel.fields.nombre') }}</th>
                            <th>{{ trans('cruds.docServicioHotel.fields.codigo') }}</th>
                            <th>{{ trans('cruds.docServicioHotel.fields.precio') }}</th>
                            <th>{{ trans('cruds.docServicioHotel.fields.por_persona') }}</th>
                            <th>{{ trans('cruds.docServicioHotel.fields.por_dia') }}</th>
                            <th>{{ trans('cruds.docServicioHotel.fields.tipo') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_metodo_pago_hotel_access')
            <div class="tab-pane fade" id="pane-metodo-pago" role="tabpanel" aria-labelledby="tab-metodo-pago">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_metodo_pago_hotel_create')
                        <button id="btn-create-metodo-pago-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docMetodoPagoHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-MetodoPagoHotel" id="tablaMetodoPagoHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docMetodoPagoHotel.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docMetodoPagoHotel.fields.nombre') }}</th>
                            <th>{{ trans('cruds.docMetodoPagoHotel.fields.tipo') }}</th>
                            <th>{{ trans('cruds.docMetodoPagoHotel.fields.activo') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_ubicacion_hotel_access')
            <div class="tab-pane fade" id="pane-ubicacion" role="tabpanel" aria-labelledby="tab-ubicacion">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_ubicacion_hotel_create')
                        <button id="btn-create-ubicacion-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docUbicacionHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-UbicacionHotel" id="tablaUbicacionHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.nombre') }}</th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.tipo') }}</th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.piso') }}</th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.zona_comun') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('dock_stock_hotel_access')
            <div class="tab-pane fade" id="pane-stock" role="tabpanel" aria-labelledby="tab-stock">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('dock_stock_hotel_create')
                        <button id="btn-create-stock-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.dockStockHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-StockHotel" id="tablaStockHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.dockStockHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docUbicacionHotel.fields.nombre') }}</th>
                            <th>{{ trans('cruds.dockStockHotel.fields.nombre') }}</th>
                            <th>{{ trans('cruds.dockStockHotel.fields.cantidad') }}</th>
                            <th>{{ trans('cruds.dockStockHotel.fields.unidad') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_incidencia_hotel_access')
            <div class="tab-pane fade" id="pane-incidencias" role="tabpanel" aria-labelledby="tab-incidencias">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_incidencia_hotel_create')
                        <button id="btn-create-incidencia-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docIncidenciaHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-IncidenciaHotel" id="tablaIncidenciaHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docIncidenciaHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docIncidenciaHotel.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docIncidenciaHotel.fields.fecha') }}</th>
                            <th>{{ trans('cruds.docIncidenciaHotel.fields.titulo') }}</th>
                            <th>{{ trans('cruds.docIncidenciaHotel.fields.estado') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_no_deseado_hotel_access')
            <div class="tab-pane fade" id="pane-no-deseado" role="tabpanel" aria-labelledby="tab-no-deseado">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_no_deseado_hotel_create')
                        <button id="btn-create-no-deseado-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docNoDeseadoHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-NoDeseadoHotel" id="tablaNoDeseadoHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docNoDeseadoHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docNoDeseadoHotel.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docNoDeseadoHotel.fields.no_deseado') }}</th>
                            <th>{{ trans('cruds.docNoDeseadoHotel.fields.motivo') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_habitacion_hotel_access')
            <div class="tab-pane fade" id="pane-habitacion" role="tabpanel" aria-labelledby="tab-habitacion">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_habitacion_hotel_create')
                        <button id="btn-create-habitacion-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docHabitacionHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-HabitacionHotel" id="tablaHabitacionHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.nombre') }}</th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.tipo') }}</th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.capacidad') }}</th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.tipo_cerradura') }}</th>
                            <th>{{ trans('cruds.docHabitacionHotel.fields.codigo') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
            @can('doc_tarifa_hotel_access')
            <div class="tab-pane fade" id="pane-tarifa" role="tabpanel" aria-labelledby="tab-tarifa">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        @can('doc_tarifa_hotel_create')
                        <button id="btn-create-tarifa-hotel" class="btn btn-success btn-sm">{{ trans('global.add') }} {{ trans('cruds.docTarifaHotel.title_singular') }}</button>
                        @endcan
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-TarifaHotel" id="tablaTarifaHotel">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.docTarifaHotel.fields.id') }}</th>
                            <th>{{ trans('cruds.docTarifaHotel.fields.establecimiento') }}</th>
                            <th>{{ trans('cruds.docTarifaHotel.fields.tarifa') }}</th>
                            <th>{{ trans('cruds.docTarifaHotel.fields.regimen') }}</th>
                            <th>{{ trans('cruds.docTarifaHotel.fields.habitacion') }}</th>
                            <th>{{ trans('cruds.docTarifaHotel.fields.importe') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    // Server-provided flags for session control and defaults
    var HAS_ACTIVE_CONTROL = {{ !empty($hasActiveControlSesion) && !empty($defaultEstablecimientoId) ? 'true' : 'false' }};
    var DEFAULT_EID = {!! json_encode($defaultEstablecimientoId ?? '') !!};
    var DEFAULT_ECODIGO = {!! json_encode($defaultEstablecimientoCodigo ?? '') !!};
    var PANEL_PREFIX = {!! json_encode($panelPrefix ?? (request()->is('external*') ? 'external' : 'admin')) !!};
    function getSelectedHotel(){
        var id = localStorage.getItem('landingWikiHotelId') || '';
        var codigo = localStorage.getItem('landingWikiHotelCodigo') || '';
        return {id:id, codigo:codigo};
    }
    function persistSelectedHotel(id, codigo){
        localStorage.setItem('landingWikiHotelId', id||'');
        localStorage.setItem('landingWikiHotelCodigo', codigo||'');
        const url = new URL(window.location.href);
        if(id){ url.searchParams.set('establecimiento_id', id); } else { url.searchParams.delete('establecimiento_id'); }
        if(codigo){ url.searchParams.set('establecimiento_codigo', codigo); } else { url.searchParams.delete('establecimiento_codigo'); }
        window.history.replaceState({}, '', url.toString());
    }

    // Generic AJAX modal helpers
    function ensureHotelLockedInForm($context){
        var sel = getSelectedHotel();
        if(!sel.id) return;
        // Try select, input, or any control holding establecimiento_id or hotel_id
        var $field = $context.find('[name="establecimiento_id"], [name="hotel_id"]').first();
        var fieldName = $field.length ? $field.attr('name') : 'establecimiento_id';
        if($field.length){
            $field.val(sel.id).trigger('change');
            // Disable user changes visually
            $field.prop('disabled', true);
            // Ensure value is submitted
            if($context.find('input[type=hidden][name="'+fieldName+'"]').length===0){
                $('<input type="hidden" name="'+fieldName+'" />').val(sel.id).appendTo($context);
            } else {
                $context.find('input[type=hidden][name="'+fieldName+'"]').val(sel.id);
            }
        } else {
            // If field not present, add hidden (for store requests that accept it)
            $('<input type="hidden" name="'+fieldName+'" />').val(sel.id).appendTo($context);
        }
    }

    function bindAjaxForm($modal, tableReloadFn){
        var $form = $modal.find('form').first();
        if($form.length===0) return;

        ensureHotelLockedInForm($form);

        function normalizeActionUrl(url){
            try {
                var u = new URL(url, window.location.origin);
                if (PANEL_PREFIX === 'external' && u.pathname.indexOf('/admin/') === 0) {
                    u.pathname = u.pathname.replace('/admin/', '/external/');
                } else if (PANEL_PREFIX === 'admin' && u.pathname.indexOf('/external/') === 0) {
                    u.pathname = u.pathname.replace('/external/', '/admin/');
                }
                return u.toString();
            } catch(e) {
                if (PANEL_PREFIX === 'external') return (url || '').replace('/admin/', '/external/');
                return (url || '').replace('/external/', '/admin/');
            }
        }

        $form.on('submit', function(e){
            e.preventDefault();
            // Always POST and rely on Laravel method spoofing via hidden _method field
            var action = normalizeActionUrl($form.attr('action'));
            var hasFiles = $form.find('input[type=file]').length>0;
            var data;
            var csrf = ($('meta[name="csrf-token"]').attr('content') || (window._token || ''));
            var ajaxOptions = { url: action, type: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, success: function(data, status, xhr){
                // If server returns redirect for non-AJAX, some servers still respond 200 HTML. Keep generic success.
                $modal.modal('hide');
                if(typeof tableReloadFn === 'function'){ tableReloadFn(); }
            }, error: function(xhr){
                // Prefer JSON validation errors (422)
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors || {};
                    var list = $('<ul class="alert alert-danger"></ul>');
                    Object.keys(errors).forEach(function(k){
                        (errors[k] || []).forEach(function(msg){ list.append('<li>'+msg+'</li>'); });
                    });
                    // Keep current form and prepend errors
                    var $body = $modal.find('.modal-body');
                    if ($body.find('.alert-danger').length) { $body.find('.alert-danger').first().replaceWith(list); } else { $body.prepend(list); }
                    return;
                }
                // Fallback: load returned HTML (e.g., redirected form with errors)
                var html = xhr.responseText || '';
                if ((xhr.status === 301 || xhr.status === 302) && xhr.getResponseHeader('Location')) {
                    // Follow redirect and load into modal
                    $.get(xhr.getResponseHeader('Location'), function(h){
                        $modal.find('.modal-body').html(h);
                        bindAjaxForm($modal, tableReloadFn);
                    });
                    return;
                }
                $modal.find('.modal-body').html(html);
                bindAjaxForm($modal, tableReloadFn);
            }};
            if(hasFiles){
                data = new FormData($form[0]);
                ajaxOptions.data = data;
                ajaxOptions.processData = false;
                ajaxOptions.contentType = false;
            } else {
                data = $form.serialize();
                ajaxOptions.data = data;
            }
            $.ajax(ajaxOptions);
        });
    }

    var wikiCrudModalOpen = false;
    // Prevent other modals from opening while our CRUD modal is open
    $(document).on('show.bs.modal', '.modal', function(e){
        if(wikiCrudModalOpen && this.id !== 'ajaxCrudModal'){
            e.preventDefault();
            return false;
        }
    });

    function openAjaxModal(url, tableReloadFn, isShow){
        var $m = $('#ajaxCrudModal');
        $m.find('.modal-title').text('');
        $m.find('.modal-body').html('<div class="p-3 text-center"><span class="spinner-border"></span></div>');
        wikiCrudModalOpen = true;
        $m.off('hidden.bs.modal.__wiki').on('hidden.bs.modal.__wiki', function(){ wikiCrudModalOpen = false; });
        // Use options to avoid focus/aria conflicts
        $m.modal({ backdrop: 'static', keyboard: false, focus: true, show: true });
        (function(){ var __u = (function(u){ try { var tmp = new URL(u, window.location.origin); tmp.searchParams.set('embed','1'); return tmp.toString(); } catch(e){ return u; } })(url); $.get(__u, function(html){
            // Parse the returned HTML and extract ONLY the main card/form content.
            var $parsed = $('<div>').append($.parseHTML(html, document, true));

            // Detect if backend redirected to Control de Sesión create
            var isControlSesion = $parsed.find('form[action*="/control-sesions"]').length > 0
                                  || /Control\s+de\s+Sesión/i.test($parsed.text());
            if (isControlSesion) {
                var managerUrl = "{{ route(($panelPrefix ?? (request()->is('external*') ? 'external' : 'admin')) . '.control-sesions.manager') }}";
                var msg = '<div class="p-3">' +
                          '<div class="alert alert-warning mb-3">' +
                          '{{ __('Para continuar, es necesario iniciar una Sesión de Control.')}}' +
                          '</div>' +
                          '<div class="text-right">' +
                          '<a href="'+managerUrl+'" target="_blank" class="btn btn-primary">{{ __('Abrir gestor de sesión') }}</a>' +
                          '</div>' +
                          '</div>';
                $m.find('.modal-title').text('{{ __('Acción no disponible') }}');
                $m.find('.modal-body').html(msg);
                return;
            }

            var $content = $parsed.find('.card').first();
            if(!$content.length){
                var $form = $parsed.find('form').first();
                $content = ($form.length ? $form.closest('.card, form').first() : $parsed);
            }
            // Prevent executing any inline scripts/styles from the loaded page to avoid duplicates
            $content.find('script').remove();

            // If this is a SHOW modal, remove any "Back to list" buttons/links in header or footer
            if (isShow) {
                try {
                    // Common patterns generated in show templates
                    $content.find('.card-header a.btn, .card-footer a.btn').filter(function(){
                        var $a = $(this);
                        var txt = ($a.text() || '').toLowerCase();
                        return $a.hasClass('btn-default') || txt.indexOf('volver') >= 0 || txt.indexOf('lista') >= 0 || txt.indexOf('back') >= 0;
                    }).remove();
                    // Also remove any standalone default buttons
                    $content.find('a.btn-default').remove();
                } catch(e) { /* ignore */ }
            }

            $m.find('.modal-body').html($content);

            // Try to read a title from the loaded content
            var title = $content.find('.card-header, h1, h2').first().text().trim();
            if(title){ $m.find('.modal-title').text(title); }

            // Initialize common widgets that might be present in forms (without reloading libraries)
            try {
                // CKEditor 5
                if (window.ClassicEditor) {
                    $m.find('textarea.ckeditor, textarea[class*="ckeditor"]').each(function(){
                        var el = this;
                        if (el._ckeditorInstance) { return; }
                        ClassicEditor.create(el).then(function(editor){ el._ckeditorInstance = editor; }).catch(function(){});
                    });
                }
            } catch(e) { /* ignore */ }
            try {
                // Select2
                if ($.fn.select2) {
                    $m.find('select.select2').each(function(){
                        if ($(this).data('select2')) return;
                        $(this).select2({ width: '100%' });
                    });
                }
            } catch(e) { /* ignore */ }

            bindAjaxForm($m, tableReloadFn);
            // Lock establecimiento in any loaded form
            ensureHotelLockedInForm($m);
        }); })();
    }

    var recepcionTable = null;
    var estadoCajaTable = null;
    var infoHotelTable = null;
    var servicioHotelTable = null;
    var metodoPagoHotelTable = null;
    var ubicacionHotelTable = null;
    var stockHotelTable = null;
    var incidenciaHotelTable = null;
    var noDeseadoHotelTable = null;
    var habitacionHotelTable = null;
    var tarifaHotelTable = null;

    // Permissions (server-evaluated)
    var CAN_EDIT_RECEPCION = {!! json_encode(Gate::allows('doc_hotel_reception_info_edit')) !!};
    var CAN_CREATE_RECEPCION = {!! json_encode(Gate::allows('doc_hotel_reception_info_create')) !!};
    var CAN_EDIT_INFO = {!! json_encode(Gate::allows('doc_info_hotel_edit')) !!};
    var CAN_CREATE_INFO = {!! json_encode(Gate::allows('doc_info_hotel_create')) !!};

    var IS_EXTERNAL = {!! json_encode(auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) !!};
    var EDIT_RECEPCION_URL_TMPL = "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-hotel-reception-infos.edit', ['doc_hotel_reception_info' => '__ID__']) }}";
    var EDIT_INFO_URL_TMPL = "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-info-hotels.edit', ['doc_info_hotel' => '__ID__']) }}";

    // DataTables global tweaks for this page: better alignment and hide utility columns
    if ($.fn.dataTable) {
        // Ensure header/body widths match and allow horizontal scroll when needed
        $.extend(true, $.fn.dataTable.defaults, {
            autoWidth: false,
            scrollX: true
        });

        // Helper to force the last header to read "Acciones" in both original and cloned headers
        function fixActionsHeader($table){
            try {
                var label = "{{ trans('global.actions') }}";
                // Original thead
                var $theadLast = $table.find('thead th').last();

                // Cloned header when scrollX is enabled
                var $wrapper = $table.closest('.dataTables_wrapper');
                var $cloneLast = $wrapper.find('.dataTables_scrollHead thead th').last();
                if ($cloneLast.length) { $cloneLast.text(label); }
            } catch(err) { /* ignore */ }
        }

        // On every DataTable init, hide the placeholder (first) and ID columns, then fix header and adjust
        $(document).on('init.dt', function (e, settings) {
            try {
                var api = new $.fn.dataTable.Api(settings);
                var $table = $(api.table().node());

                // Hide placeholder first column by index 0, and also hide any column whose dataSrc is 'id'
                api.columns().every(function (idx) {
                    var src = this.dataSrc();
                    if (idx === 0 || src === 'id') {
                        this.visible(false, false); // hide without recalculating until end
                    }
                });

                // Force actions header label
                fixActionsHeader($table);

                // Adjust columns after a tiny delay to ensure DOM is settled (especially on hidden tabs)
                setTimeout(function(){ api.columns.adjust(); fixActionsHeader($table); }, 0);
            } catch (err) {
                // ignore
            }
        });

        // Also adjust columns and fix header on every draw to keep headers aligned
        $(document).on('draw.dt', function (e, settings) {
            try {
                var api = new $.fn.dataTable.Api(settings);
                var $table = $(api.table().node());
                api.columns.adjust();
                fixActionsHeader($table);
            } catch (err) {}
        });



        // Keep adjusting on window resize
        $(window).on('resize.dt', function(){
            try {
                if (recepcionTable) { recepcionTable.columns.adjust(); fixActionsHeader($(recepcionTable.table().node())); }
                if (estadoCajaTable) { estadoCajaTable.columns.adjust(); fixActionsHeader($(estadoCajaTable.table().node())); }
                if (infoHotelTable) { infoHotelTable.columns.adjust(); fixActionsHeader($(infoHotelTable.table().node())); }
                if (servicioHotelTable) { servicioHotelTable.columns.adjust(); fixActionsHeader($(servicioHotelTable.table().node())); }
                if (metodoPagoHotelTable) { metodoPagoHotelTable.columns.adjust(); fixActionsHeader($(metodoPagoHotelTable.table().node())); }
                if (ubicacionHotelTable) { ubicacionHotelTable.columns.adjust(); fixActionsHeader($(ubicacionHotelTable.table().node())); }
                if (stockHotelTable) { stockHotelTable.columns.adjust(); fixActionsHeader($(stockHotelTable.table().node())); }
                if (incidenciaHotelTable) { incidenciaHotelTable.columns.adjust(); fixActionsHeader($(incidenciaHotelTable.table().node())); }
                if (noDeseadoHotelTable) { noDeseadoHotelTable.columns.adjust(); fixActionsHeader($(noDeseadoHotelTable.table().node())); }
                if (habitacionHotelTable) { habitacionHotelTable.columns.adjust(); fixActionsHeader($(habitacionHotelTable.table().node())); }
                if (tarifaHotelTable) { tarifaHotelTable.columns.adjust(); fixActionsHeader($(tarifaHotelTable.table().node())); }
            } catch (err) {}
        });
    }

    function loadRecepcionPanel(hotelId){
        var $panel = $('#recepcionShowPanel');
        $panel.html('<div class="text-muted">Cargando...</div>');
        $.getJSON("{{ url('/api/v1/hotel-rag') }}", { hotel_id: hotelId })
            .done(function(resp){
                var items = (((resp||{}).data||{}).wiki||{}).recepcion || [];
                var item = items.length ? items[0] : null;
                if(item){
                    // Hide create when exists
                    $('#btn-create-recepcion').hide();
                    var editBtn = CAN_EDIT_RECEPCION ? ('<a class="btn btn-info btn-sm mb-2" href="'+ EDIT_RECEPCION_URL_TMPL.replace('__ID__', item.id) +'">Editar</a>') : '';
                    var fotos = '';
                    if (Array.isArray(item.box_photo)) {
                        fotos = item.box_photo.map(function(m){ return '<a href="'+m.url+'" target="_blank"><img src="'+m.thumbnail+'" style="width:50px;height:50px;margin-right:4px;"></a>'; }).join('');
                    }
                    var html = ''+
                        '<div class="card">'+
                        ' <div class="card-header">Información de Recepción</div>'+
                        ' <div class="card-body">'+ editBtn +
                        '  <table class="table table-bordered table-striped">'+
                        '   <tbody>'+
                        '    <tr><th>Apertura</th><td>'+(item.hour_open||'')+'</td></tr>'+
                        '    <tr><th>Cierre</th><td>'+(item.hour_close||'')+'</td></tr>'+
                        '    <tr><th>Apertura festivos</th><td>'+(item.open_holiday||'')+'</td></tr>'+
                        '    <tr><th>Cierre festivos</th><td>'+(item.close_holiday||'')+'</td></tr>'+
                        '    <tr><th>Acceso fuera de horario</th><td>'+(item.acces_type_after_hour||'')+'</td></tr>'+
                        '    <tr><th>Ubicación caja</th><td>'+(item.box_locate||'')+'</td></tr>'+
                        '    <tr><th>Fotos caja</th><td>'+fotos+'</td></tr>'+
                        '    <tr><th>Videoportero</th><td>'+(item.acces_videoportero||'')+'</td></tr>'+
                        '   </tbody>'+
                        '  </table>'+
                        ' </div>'+
                        '</div>';
                    $panel.html(html);
                } else {
                    // No record: show message and enable create button if allowed
                    $panel.html('<div class="alert alert-info">No hay información de recepción para este hotel.</div>');
                    if(CAN_CREATE_RECEPCION){ $('#btn-create-recepcion').show(); }
                }
            })
            .fail(function(){
                $panel.html('<div class="text-danger">Error cargando datos.</div>');
            });
    }

    function loadInfoHotelPanel(hotelId){
        var $panel = $('#infoHotelShowPanel');
        $panel.html('<div class="text-muted">Cargando...</div>');
        $.getJSON("{{ url('/api/v1/hotel-rag') }}", { hotel_id: hotelId })
            .done(function(resp){
                var items = (((resp||{}).data||{}).wiki||{}).info_hotel || [];
                var item = items.length ? items[0] : null;
                if(item){
                    $('#btn-create-info-hotel').hide();
                    var editBtn = CAN_EDIT_INFO ? ('<a class="btn btn-info btn-sm mb-2" href="'+ EDIT_INFO_URL_TMPL.replace('__ID__', item.id) +'">Editar</a>') : '';
                    var html = ''+
                        '<div class="card">'+
                        ' <div class="card-header">Información del Hotel</div>'+
                        ' <div class="card-body">'+ editBtn +
                        '  <table class="table table-bordered table-striped">'+
                        '   <tbody>'+
                        '    <tr><th>Descripción</th><td>'+(item.descripcion||'')+'</td></tr>'+
                        '    <tr><th>Categoría</th><td>'+(item.categoria||'')+'</td></tr>'+
                        '    <tr><th>País</th><td>'+(item.pais_nombre||'')+'</td></tr>'+
                        '    <tr><th>Provincia</th><td>'+(item.provincia_nombre||'')+'</td></tr>'+
                        '    <tr><th>Ciudad</th><td>'+(item.ciudad_nombre||'')+'</td></tr>'+
                        '    <tr><th>Dirección</th><td>'+(item.direccion||'')+'</td></tr>'+
                        '    <tr><th>Código Postal</th><td>'+(item.codigo_postal||'')+'</td></tr>'+
                        '    <tr><th>Teléfono</th><td>'+(item.telefono||'')+'</td></tr>'+
                        '    <tr><th>Emergencias</th><td>'+(item.emergencias||'')+'</td></tr>'+
                        '    <tr><th>Web</th><td>'+(item.web||'')+'</td></tr>'+
                        '    <tr><th>Enlace Fotos</th><td>'+(item.enlace_fotos||'')+'</td></tr>'+
                        '    <tr><th>Cuenta Bancaria</th><td>'+(item.cuenta_bancaria||'')+'</td></tr>'+
                        '    <tr><th>Modos de cobro</th><td>'+(item.modos_cobro||'')+'</td></tr>'+
                        '   </tbody>'+
                        '  </table>'+
                        ' </div>'+
                        '</div>';
                    $panel.html(html);
                } else {
                    $panel.html('<div class="alert alert-info">No hay información del hotel para este hotel.</div>');
                    if(CAN_CREATE_INFO){ $('#btn-create-info-hotel').show(); }
                }
            })
            .fail(function(){
                $panel.html('<div class="text-danger">Error cargando datos.</div>');
            });
    }

    function initRecepcionTable(hotelId){
        if(!document.getElementById('tablaRecepcion')) return;
        if(recepcionTable){ recepcionTable.ajax.reload(null, true); return; }
        let dtButtons = [];// no mass delete here
        recepcionTable = $('#tablaRecepcion').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-hotel-reception-infos.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'hour_open', name: 'hour_open' },
                { data: 'hour_close', name: 'hour_close' },
                { data: 'open_holiday', name: 'open_holiday' },
                { data: 'close_holiday', name: 'close_holiday' },
                { data: 'acces_type_after_hour', name: 'acces_type_after_hour' },
                { data: 'box_photo', name: 'box_photo', sortable: false, searchable: false },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });

        // Handle actions: view/edit open modal, delete via ajax
        $('#pane-recepcion').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault();
            e.stopPropagation();
            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel();
            if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ recepcionTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-recepcion').on('submit', 'form', function(e){
            var $f = $(this);
            // Only intercept delete forms in action column
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault();
            if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({
                url: $f.attr('action'),
                type: 'POST',
                data: $f.serialize(),
                success: function(){ recepcionTable.ajax.reload(null, false); },
                error: function(){ alert('Error al eliminar'); }
            });
        });
    }

    function initEstadoCajaTable(hotelId){
        if(!document.getElementById('tablaEstadoCaja')) return;
        if(estadoCajaTable){ estadoCajaTable.ajax.reload(null, true); return; }
        let dtButtons = [];// no mass delete here
        estadoCajaTable = $('#tablaEstadoCaja').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-hotel-estado-cajas.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'caja', name: 'caja' },
                { data: 'code', name: 'code' },
                { data: 'room_codigo', name: 'room.codigo' },
                { data: 'room_status', name: 'room_status' },
                { data: 'cliente', name: 'cliente' },
                { data: 'documento_cliente', name: 'documento_cliente' },
                { data: 'pay', name: 'pay' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 2, 'desc' ]],
            pageLength: 25,
        });

        // Actions
        $('#pane-estado-caja').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault();
            e.stopPropagation();
            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel();
            if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ estadoCajaTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-estado-caja').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault();
            if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({
                url: $f.attr('action'),
                type: 'POST',
                data: $f.serialize(),
                success: function(){ estadoCajaTable.ajax.reload(null, false); },
                error: function(){ alert('Error al eliminar'); }
            });
        });
    }

    function initInfoHotelTable(hotelId){
        if(!document.getElementById('tablaInfoHotel')) return;
        if(infoHotelTable){ infoHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        infoHotelTable = $('#tablaInfoHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-info-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'hotel_codigo', name: 'hotel.codigo' },
                { data: 'categoria', name: 'categoria' },
                { data: 'pais_nombre', name: 'pais.nombre' },
                { data: 'provincia_nombre', name: 'provincia.nombre' },
                { data: 'ciudad_nombre', name: 'ciudad.nombre' },
                { data: 'direccion', name: 'direccion' },
                { data: 'codigo_postal', name: 'codigo_postal' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-info-hotel').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ infoHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-info-hotel').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ infoHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initServicioHotelTable(hotelId){
        if(!document.getElementById('tablaServicioHotel')) return;
        if(servicioHotelTable){ servicioHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        servicioHotelTable = $('#tablaServicioHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-servicio-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'hotel_codigo', name: 'hotel.codigo' },
                { data: 'nombre', name: 'nombre' },
                { data: 'codigo', name: 'codigo' },
                { data: 'precio', name: 'precio' },
                { data: 'por_persona', name: 'por_persona', sortable: false, searchable: false },
                { data: 'por_dia', name: 'por_dia', sortable: false, searchable: false },
                { data: 'tipo', name: 'tipo' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 2, 'asc' ]],
            pageLength: 25,
        });
        $('#pane-servicio').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ servicioHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-servicio').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ servicioHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initMetodoPagoHotelTable(hotelId){
        if(!document.getElementById('tablaMetodoPagoHotel')) return;
        if(metodoPagoHotelTable){ metodoPagoHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        metodoPagoHotelTable = $('#tablaMetodoPagoHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-metodo-pago-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'nombre', name: 'nombre' },
                { data: 'tipo', name: 'tipo' },
                { data: 'activo', name: 'activo', sortable: false, searchable: false },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 2, 'asc' ]],
            pageLength: 25,
        });
        $('#pane-metodo-pago').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ metodoPagoHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-metodo-pago').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ metodoPagoHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initUbicacionHotelTable(hotelId){
        if(!document.getElementById('tablaUbicacionHotel')) return;
        if(ubicacionHotelTable){ ubicacionHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        ubicacionHotelTable = $('#tablaUbicacionHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-ubicacion-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'nombre', name: 'nombre' },
                { data: 'tipo', name: 'tipo' },
                { data: 'piso', name: 'piso' },
                { data: 'zona_comun', name: 'zona_comun', sortable: false, searchable: false },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-ubicacion').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ ubicacionHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-ubicacion').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ ubicacionHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initStockHotelTable(hotelId){
        if(!document.getElementById('tablaStockHotel')) return;
        if(stockHotelTable){ stockHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        stockHotelTable = $('#tablaStockHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.dock-stock-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ubicacion_nombre', name: 'ubicacion.nombre' },
                { data: 'nombre', name: 'nombre' },
                { data: 'cantidad', name: 'cantidad' },
                { data: 'unidad', name: 'unidad' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-stock').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ stockHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-stock').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ stockHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initIncidenciaHotelTable(hotelId){
        if(!document.getElementById('tablaIncidenciaHotel')) return;
        if(incidenciaHotelTable){ incidenciaHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        incidenciaHotelTable = $('#tablaIncidenciaHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-incidencia-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'fecha', name: 'fecha' },
                { data: 'titulo', name: 'titulo' },
                { data: 'estado', name: 'estado' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-incidencias').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ incidenciaHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-incidencias').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ incidenciaHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    // New initializers for No Deseado, Habitacion, Tarifa
    function initNoDeseadoHotelTable(hotelId){
        if(!document.getElementById('tablaNoDeseadoHotel')) return;
        if(noDeseadoHotelTable){ noDeseadoHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        noDeseadoHotelTable = $('#tablaNoDeseadoHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-no-deseado-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'no_deseado', name: 'no_deseado', sortable: false, searchable: false },
                { data: 'motivo', name: 'motivo' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-no-deseado').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ noDeseadoHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-no-deseado').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ noDeseadoHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initHabitacionHotelTable(hotelId){
        if(!document.getElementById('tablaHabitacionHotel')) return;
        if(habitacionHotelTable){ habitacionHotelTable.ajax.reload(null, true); return; }
        let dtButtons = [];
        habitacionHotelTable = $('#tablaHabitacionHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-habitacion-hotels.index') }}",
                data: function(d){ d.establecimiento_id = window.WikiHotelSelectedId || $('#selectorHotel').val(); }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'nombre', name: 'nombre' },
                { data: 'tipo', name: 'tipo' },
                { data: 'capacidad', name: 'capacidad' },
                { data: 'tipo_cerradura', name: 'tipo_cerradura' },
                { data: 'codigo', name: 'codigo' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-habitacion').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ habitacionHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-habitacion').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ habitacionHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    function initTarifaHotelTable(hotelId){
        if(!document.getElementById('tablaTarifaHotel')) return;
        if(tarifaHotelTable){ tarifaHotelTable.ajax.reload(); return; }
        let dtButtons = [];
        tarifaHotelTable = $('#tablaTarifaHotel').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route(((auth()->check() && method_exists(auth()->user(),'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin') . '.doc-tarifa-hotels.index') }}",
                data: function(d){ d.establecimiento_id = hotelId; }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
                { data: 'tarifa', name: 'tarifa' },
                { data: 'regimen', name: 'regimen' },
                { data: 'habitacion_nombre', name: 'habitacion.nombre' },
                { data: 'importe', name: 'importe' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 25,
        });
        // Actions
        $('#pane-tarifa').on('click', 'a.btn-primary, a.btn-info', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL($(this).attr('href'), window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            var isShow = $(this).hasClass('btn-primary');
            openAjaxModal(url.toString(), function(){ tarifaHotelTable.ajax.reload(null, false); }, isShow);
            return false;
        });
        $('#pane-tarifa').on('submit', 'form', function(e){
            var $f = $(this);
            if(!$f.find('input[name=_method][value="DELETE"]').length) return;
            e.preventDefault(); if(!confirm('{{ trans('global.areYouSure') }}')) return;
            $.ajax({ url: $f.attr('action'), type: 'POST', data: $f.serialize(), success: function(){ tarifaHotelTable.ajax.reload(null, false); }, error: function(){ alert('Error al eliminar'); } });
        });
    }

    $(function(){
        var $sel = $('#selectorHotel');
        var selected = getSelectedHotel();

        // If Control de Sesión is active, override any stored selection
        if (HAS_ACTIVE_CONTROL && DEFAULT_EID) {
            selected.id = (DEFAULT_EID + '');
            selected.codigo = (DEFAULT_ECODIGO + '');
            persistSelectedHotel(selected.id, selected.codigo || '');
        } else {
            // If no persisted selection, try default from active session
            if (!selected.id && DEFAULT_EID) {
                selected.id = (DEFAULT_EID + '');
                selected.codigo = (DEFAULT_ECODIGO + '');
                persistSelectedHotel(selected.id, selected.codigo || '');
            }
        }

        // Make the selected ID accessible to all DataTables ajax.data functions
        window.WikiHotelSelectedId = selected.id || '';

        if(selected.id){
            if($sel.length){ $sel.val(selected.id); }
            $('#wikiHotelTabs, #wikiHotelTabContent').show();
            loadRecepcionPanel(selected.id);
            initEstadoCajaTable(selected.id);
            loadInfoHotelPanel(selected.id);
            initServicioHotelTable(selected.id);
            initMetodoPagoHotelTable(selected.id);
            initUbicacionHotelTable(selected.id);
            initStockHotelTable(selected.id);
            initIncidenciaHotelTable(selected.id);
            initNoDeseadoHotelTable(selected.id);
            initHabitacionHotelTable(selected.id);
            initTarifaHotelTable(selected.id);
        } else {
            $('#placeholderSeleccion').show();
        }

        if($sel.length){
            $sel.on('change', function(){
                var id = this.value || '';
                var codigo = id ? $('#selectorHotel option:selected').data('codigo')+'' : '';
                // Update global selected id for AJAX data providers
                window.WikiHotelSelectedId = id;
                persistSelectedHotel(id, codigo);
                if(id){
                    $('#placeholderSeleccion').hide();
                    $('#wikiHotelTabs, #wikiHotelTabContent').show();
                    loadRecepcionPanel(id);
                    if(estadoCajaTable){ estadoCajaTable.ajax.reload(); } else { initEstadoCajaTable(id); }
                    loadInfoHotelPanel(id);
                    if(servicioHotelTable){ servicioHotelTable.ajax.reload(); } else { initServicioHotelTable(id); }
                    if(metodoPagoHotelTable){ metodoPagoHotelTable.ajax.reload(); } else { initMetodoPagoHotelTable(id); }
                    if(ubicacionHotelTable){ ubicacionHotelTable.ajax.reload(); } else { initUbicacionHotelTable(id); }
                    if(stockHotelTable){ stockHotelTable.ajax.reload(); } else { initStockHotelTable(id); }
                    if(incidenciaHotelTable){ incidenciaHotelTable.ajax.reload(); } else { initIncidenciaHotelTable(id); }
                    if(noDeseadoHotelTable){ noDeseadoHotelTable.ajax.reload(); } else { initNoDeseadoHotelTable(id); }
                    if(habitacionHotelTable){ habitacionHotelTable.ajax.reload(); } else { initHabitacionHotelTable(id); }
                    if(tarifaHotelTable){ tarifaHotelTable.ajax.reload(); } else { initTarifaHotelTable(id); }
                } else {
                    $('#wikiHotelTabs, #wikiHotelTabContent').hide();
                    $('#placeholderSeleccion').show();
                }
            });
        }
    });

        // Re-init on tab shown (ensures columns resize)
        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            if(recepcionTable){ recepcionTable.columns.adjust(); }
            if(estadoCajaTable){ estadoCajaTable.columns.adjust(); }
            if(infoHotelTable){ infoHotelTable.columns.adjust(); }
            if(servicioHotelTable){ servicioHotelTable.columns.adjust(); }
            if(metodoPagoHotelTable){ metodoPagoHotelTable.columns.adjust(); }
            if(ubicacionHotelTable){ ubicacionHotelTable.columns.adjust(); }
            if(stockHotelTable){ stockHotelTable.columns.adjust(); }
            if(incidenciaHotelTable){ incidenciaHotelTable.columns.adjust(); }
            if(noDeseadoHotelTable){ noDeseadoHotelTable.columns.adjust(); }
            if(habitacionHotelTable){ habitacionHotelTable.columns.adjust(); }
            if(tarifaHotelTable){ tarifaHotelTable.columns.adjust(); }
        });

        // Create buttons -> open AJAX modal with create form
        $('#btn-create-recepcion').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel();
            if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-hotel-reception-infos.create') : route('admin.doc-hotel-reception-infos.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ var sel2 = getSelectedHotel(); if(sel2.id){ loadRecepcionPanel(sel2.id); } });
            return false;
        });
        $('#btn-create-estado-caja').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel();
            if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-hotel-estado-cajas.create') : route('admin.doc-hotel-estado-cajas.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(estadoCajaTable) estadoCajaTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-info-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-info-hotels.create') : route('admin.doc-info-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ var sel2 = getSelectedHotel(); if(sel2.id){ loadInfoHotelPanel(sel2.id); } });
            return false;
        });
        $('#btn-create-servicio-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-servicio-hotels.create') : route('admin.doc-servicio-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(servicioHotelTable) servicioHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-metodo-pago-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-metodo-pago-hotels.create') : route('admin.doc-metodo-pago-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(metodoPagoHotelTable) metodoPagoHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-ubicacion-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-ubicacion-hotels.create') : route('admin.doc-ubicacion-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(ubicacionHotelTable) ubicacionHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-stock-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.dock-stock-hotels.create') : route('admin.dock-stock-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(stockHotelTable) stockHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-incidencia-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-incidencia-hotels.create') : route('admin.doc-incidencia-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(incidenciaHotelTable) incidenciaHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-no-deseado-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-no-deseado-hotels.create') : route('admin.doc-no-deseado-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(noDeseadoHotelTable) noDeseadoHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-habitacion-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-habitacion-hotels.create') : route('admin.doc-habitacion-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(habitacionHotelTable) habitacionHotelTable.ajax.reload(null, true); });
            return false;
        });
        $('#btn-create-tarifa-hotel').on('click', function(e){
            e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
            var sel = getSelectedHotel(); if(!sel.id) return false;
            var url = new URL("{{ request()->is('external*') ? route('external.doc-tarifa-hotels.create') : route('admin.doc-tarifa-hotels.create') }}", window.location.origin);
            url.searchParams.set('establecimiento_id', sel.id);
            openAjaxModal(url.toString(), function(){ if(tarifaHotelTable) tarifaHotelTable.ajax.reload(null, true); });
            return false;
        });

        // Append establecimiento_id to other links if needed (fallback)
        $('#pane-recepcion, #pane-estado-caja, #pane-info-hotel, #pane-servicio, #pane-metodo-pago, #pane-ubicacion, #pane-stock, #pane-incidencias, #pane-no-deseado, #pane-habitacion, #pane-tarifa').on('click', 'a[href]', function(e){
            var $a = $(this);
            if($a.is('.btn-primary, .btn-info')) return; // those are handled above
            var href = $a.attr('href');
            if(!href || href.indexOf('javascript:') === 0 || href.indexOf('#') === 0) return;
            var sel = getSelectedHotel();
            if(!sel.id) return;
            try{
                var url = new URL(href, window.location.origin);
                url.searchParams.set('establecimiento_id', sel.id);
                $a.attr('href', url.toString());
            }catch(err){}
        });
</script>

<!-- AJAX CRUD Modal -->
<div class="modal fade" id="ajaxCrudModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="p-3 text-center"><span class="spinner-border"></span></div>
      </div>
    </div>
  </div>
</div>
@endsection
