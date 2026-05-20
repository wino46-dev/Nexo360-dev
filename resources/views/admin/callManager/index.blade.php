@extends('layouts.admin')
@section('content')
<script src="/vendor/jquery-validate/jquery.validate.min.js"></script>
<script src="/vendor/jquery-validate/localization/messages_es.min.js"></script>

@if (!empty($error_api))
@foreach ($error_api as $error_row)
<div class="alert alert-danger" role="alert">
    {{ $error_row }}
</div>
@endforeach
@endif
<script>
var folio_current = null;
</script>

@if (isset($control_actual))
@include('admin.callManager.styles')

@if ($pms_active)

{{-- Placeholder para mostrar mensajes globales fuera del container del Call-Manager --}}
<div id="pms_error_global" class="mb-3"></div>

@include('admin.ari._styles')
@include('admin.ari._info_card')
@include('admin.ari._chat_widget')

@include('admin.callManager.reservation.folioSearch')

<div class="card">
    <div class="card-body p-3">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">
                    <b>GESTION DE RESERVA</b>
                </a>
            </li>
            @unless(request()->is('external*'))
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">
                    <b>PASE DE IMAGENES</b>
                </a>
            </li>
            @endunless
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active bg-white" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div id="reservation_detail_container" class="border border-success rounded p-2 mt-2">

                    <i class="fa fa-info-circle" aria-hidden="true"></i> Seleccione una reserva


                </div>
                <div class="row">
                    <div class="col-md-5 pt-2" id="checkingContainerLeft">
                        @include('admin.callManager.payment')

                        @unless(request()->is('external*'))
                        @include('admin.callManager.writeCard', ['id' => 1])
                        @endunless

                        @include('admin.callManager.photoView')

                    </div>
                    <div class="col-md-7 pt-2">
                        <div id="checkin_partners">
                        </div>
                    </div>

                </div>


            </div>
            @unless(request()->is('external*'))
            <div class="tab-pane fade bg-white " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                @include('admin.callManager.imagesIndex')
            </div>
            @endunless
        </div>
    </div>
</div>
@endif


@include('admin.manager.scripts')

@unless(request()->is('external*'))
@include('admin.callManager.writeCardModal')
@endunless

{{-- Modal Incidencias abiertas del hotel --}}
<div class="modal fade" id="modalIncidenciasHotel" tabindex="-1" role="dialog" aria-labelledby="incidenciasLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incidenciasLabel">Incidencias abiertas del establecimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="incidenciasContent">
                    <div class="text-muted">Cargando...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif
<style>
.nav-item a {
    color: #768192;
}

.nav-tabs .nav-link.active {
    color: black
}

.fieldset1 {
    border: 1px solid #ccc;
}

.fieldset1 legend {
    font-size: 14px !important;
    margin-left: 5px;
    padding-left: 5px;
    margin-bottom: 0px
}

.select2-results__option {
    padding-left: 10px;
}
</style>
@endsection
@section('scripts')
<script>
// Datos de incidencias abiertas inyectados desde el servidor
window.incidenciasAbiertas = @json(isset($incidencias_abiertas) ? $incidencias_abiertas : []);
window.nombreEstablecimiento = @json(isset($establecimiento) && isset($establecimiento - > nombre) ? $establecimiento -
    > nombre : null);
document.addEventListener('DOMContentLoaded', function() {
    try {
        if (Array.isArray(window.incidenciasAbiertas) && window.incidenciasAbiertas.length > 0) {
            // Render contenido del modal
            renderIncidenciasContent(window.incidenciasAbiertas);
            // Sweet Alert informativo
            Swal.fire({
                icon: 'info',
                title: 'Incidencias abiertas',
                html: (window.nombreEstablecimiento ? '<p><b>Hotel:</b> ' + window
                        .nombreEstablecimiento + '</p>' : '') +
                    '<p>Hemos detectado ' + window.incidenciasAbiertas.length +
                    ' incidencia(s) abierta(s) en este establecimiento.</p>',
                showCancelButton: true,
                confirmButtonText: 'Ver incidencias',
                cancelButtonText: 'Cerrar'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $('#modalIncidenciasHotel').modal('show');
                }
            });
        } else {
            // No hay incidencias: ocultar el botón del header
            $('#btn_ver_incidencias').remove();
        }
    } catch (e) {
        // Si hay algún error, por seguridad, ocultamos el botón
        $('#btn_ver_incidencias').remove();
    }
});
// Cargar DOMPurify para sanear HTML de descripciones
(function() {
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/dompurify@3.0.8/dist/purify.min.js';
    s.onload = function() {
        // Re-render with HTML formatting once DOMPurify is available
        try {
            if (Array.isArray(window.incidenciasAbiertas) && window.incidenciasAbiertas.length > 0) {
                renderIncidenciasContent(window.incidenciasAbiertas);
            }
        } catch (e) {}
    };
    document.head.appendChild(s);
})();

function sanitizeHtml(html) {
    if (window.DOMPurify && typeof window.DOMPurify.sanitize === 'function') {
        return window.DOMPurify.sanitize(html, {
            ALLOWED_TAGS: ['b', 'strong', 'i', 'em', 'u', 'p', 'br', 'ul', 'ol', 'li', 'span', 'a'],
            ALLOWED_ATTR: ['href', 'target', 'rel']
        });
    }
    // Fallback: texto plano si DOMPurify no está listo
    return $('<div>').html(html).text();
}

function renderIncidenciasContent(items) {
    var html = '';
    if (!Array.isArray(items) || items.length === 0) {
        html = '<div class="alert alert-secondary">No hay incidencias abiertas.</div>';
    } else {
        html += '<div class="table-responsive">';
        html += '<table class="table table-sm table-striped">';
        html += '<thead><tr><th>Fecha</th><th>Título</th><th>Estado</th><th>Descripción</th></tr></thead><tbody>';
        for (var i = 0; i < items.length; i++) {
            var it = items[i] || {};
            html += '<tr>' +
                '<td>' + (it.fecha || '') + '</td>' +
                '<td>' + (it.titulo || '') + '</td>' +
                '<td>' + (it.estado || '') + '</td>' +
                // Descripción: renderizar HTML saneado permitiendo negritas, listas, etc.
                '<td style="max-width:420px">' + (it.descripcion ? sanitizeHtml(it.descripcion) : '') + '</td>' +
                '</tr>';
        }
        html += '</tbody></table></div>';
    }
    $('#incidenciasContent').html(html);
}

function reservation_select(id, checkin, checkout) {
    // Permitir que se invoque como reservation_select(this)
    if (id && (id.nodeType === 1 || id.tagName)) {
        var el = id;
        var dataId = el.getAttribute('data-reservation-id') || el.getAttribute('data-id') || '';
        var dataFolio = el.getAttribute('data-folio') || '';
        var dataReservation = el.getAttribute('data-reservation') || '';
        var dataCheckin = el.getAttribute('data-checkin') || '';
        var dataCheckout = el.getAttribute('data-checkout') || '';
        id = dataId || '';
        checkin = checkin || dataCheckin;
        checkout = checkout || dataCheckout;

        // Disparar recomendaciones de IA para la reserva seleccionada (en paralelo)
        try {
            var folioObj = null;
            var reservationObj = null;
            if (dataFolio) folioObj = JSON.parse(dataFolio);
            if (dataReservation) reservationObj = JSON.parse(dataReservation);
            if (window.ariChat && typeof window.ariChat.requestReservationRecommendations === 'function') {
                window.ariChat.requestReservationRecommendations(folioObj, reservationObj);
            }
        } catch (e) {
            // No bloquear el flujo principal del checkin
            console.warn('[ARI_CHAT] No se pudo parsear folio/reservation', e);
        }
    }

    // Normalizar y validar ID numérico
    id = (id || '').toString().match(/\d+/) ? (id.toString().match(/\d+/)[0]) : '';
    if (!id) {
        Toast && Toast.fire ? Toast.fire({
            icon: 'error',
            title: 'ID de reserva inválido'
        }) : alert('ID de reserva inválido');
        return;
    }

    $('#capturasli').trigger("click");
    $('#checkin_partners').html('<br />' + loading1);

    $('#photoView').hide();
    $('#pagoFormContainer').show();
    $('#grabarTarjetaContainer1').show();

    if (checkin) {
        $('#grabarTarjetaContainer1').find('#date_in').val(checkin);
    }
    if (checkout) {
        $('#grabarTarjetaContainer1').find('#date_out').val(checkout);
    }

    $.ajax({
        url: "{{ url((request()->is('external*')?'external':'admin').'/reservation-api/reservation-select') }}/" +
            id + '?embed=1',
        method: 'GET',
        cache: false,
        success: function(response) {
            $('#checkin_partners').html(response);
        },
        error: function(response) {
            Toast && Toast.fire ? Toast.fire({
                icon: 'error',
                title: 'Error al cargar datos de huéspedes'
            }) : console.error('Error al cargar datos de huéspedes', response);
        },
    });
}
$('#grabarTarjetaContainer1').hide();
</script>

@include('admin.ari._scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if ($('#ari_recommendations_container').length) {
        // Por defecto: arrancar minimizado y cargar datos en segundo plano.
        if (window.hideARIInfo) window.hideARIInfo();
        if (window.callManagerLoadRecommendations) window.callManagerLoadRecommendations(false);
    }
});
</script>
@endsection