<?php
use App\Models\Establecimiento;
$__all_est = Establecimiento::orderBy('nombre','asc')->get(['id','nombre']);
$establecimientos = $__all_est->pluck('nombre', 'id');
// Limit selector to Control Sesión establecimiento when active
if (!empty($hasActiveControlSesion) && !empty($defaultEstablecimientoId)) {
    $establecimientos = $establecimientos->only([$defaultEstablecimientoId]);
}
?>
<div class="card">
    <div class="card-header py-1 px-2">
        <b>Filtros</b>
    </div>
    <div class="card-body py-1 px-2">
        <form id="filter-form">

            <div class="row">
                <div class="col-md-3">

                    <table style="width:100%">
                        <tr>
                            <td class="text-nowrap">
                                <input type="checkbox" class="ml-2" value="1" id="filtro_fecha_check">
                                Fecha Desde:<br />
                                <input type="date" name="filtro_fecha_inicio" id="filtro_fecha_inicio"
                                    style="width: 95%" class="form-control form-control-sm" value="{{ date('Y-m-d') }}"
                                    disabled />
                            </td>
                            <td>
                                Fecha Hasta:<br />
                                <input type="date" name="filtro_fecha_final" id="filtro_fecha_final"
                                    class="form-control form-control-sm" value="{{ date('Y-m-d') }}" disabled />
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="col-md-4">
                    Establecimiento:
                    {!! UtilService::drowDownList('filtro_establecimiento_id', $establecimientos, '', [
                        'id' => 'filtro_establecimiento_id',
                        'class' => 'form-control form-control-sm select2',
                        'required' => 'true',
                        'placeholder' => 'Seleccione...',
                    ]) !!}
                </div>
                <div class="col-md-2 text-nowrap">
                    Código Reserva:
                    <input type="text" name="filtro_reservation_name" class="form-control form-control-sm" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    No. Documento:
                    <input type="text" name="filtro_documento" class="form-control form-control-sm" />
                </div>
                <div class="col-md-3">
                    Nombres:
                    <input type="text" name="filtro_nombres" class="form-control form-control-sm" />
                </div>
                <div class="col-md-3">
                    Apellidos:
                    <input type="text" name="filtro_apellidos" class="form-control form-control-sm" />
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <button type="button" class="btn btn-sm btn-secondary w-100"
                                onclick="filtrar_limpiar()">Limpiar</button>
                        </div>
                        <div class="col-md-6 pt-3">
                            <button type="button" class="btn btn-sm btn-info w-100" onclick="filtrar()">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<script>
    $(function() {
        // If Control de Sesión is active, lock establecimiento selector
        try{
            if (typeof HAS_ACTIVE_CONTROL !== 'undefined' && HAS_ACTIVE_CONTROL && DEFAULT_EID) {
                $('#filtro_establecimiento_id').val(DEFAULT_EID).trigger('change');
                // Disable visually but still submit value
                $('#filtro_establecimiento_id').prop('disabled', true);
                if($('#filter-form input[type=hidden][name="filtro_establecimiento_id"]').length===0){
                    $('<input type="hidden" name="filtro_establecimiento_id" />').val(DEFAULT_EID).appendTo('#filter-form');
                }
            }
        }catch(e){}

        $('#filtro_fecha_check').change(function() {

            // Verificar si el checkbox está marcado
            if ($(this).is(':checked')) {
                // Habilitar los inputs si el checkbox está marcado
                $('#filtro_fecha_inicio, #filtro_fecha_final').prop('disabled', false);
            } else {

                $('#filtro_fecha_inicio, #filtro_fecha_final').prop('disabled', true);
            }
        });

        $('#filter-form input').on('keypress', function(event) {
            if (event.which === 13) { // Verifica si la tecla presionada es Enter
                event.preventDefault(); // Prevenir el comportamiento por defecto (enviar formulario)


                // Llamar a alguna función personalizada
                filtrar();
            }
        });

    });

    function filtrar() {

        var filters = $('#filter-form').serializeArray();

        const extraParams = filters.reduce((acc, current) => {
            acc[current.name] = current.value; // Añadir como clave-valor
            return acc;
        }, {});

        table.settings()[0].ajax.data = function(d) {
            return $.extend({}, d, extraParams);
        };

        table.ajax.reload(function() {
            console.log('Tabla recargada con filtros:', extraParams);
        }, false);

    }

    function filtrar_limpiar() {
        $('#filter-form')[0].reset(); // Limpiar el formulario
        table.settings()[0].ajax.data = function(d) {
            return d; // Reiniciar datos enviados al backend
        };
        table.ajax.reload();
    }
</script>
