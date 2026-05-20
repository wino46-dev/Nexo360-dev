<?php
$fecha = new DateTime();

$fecha_hoy = $fecha->format('m/d/Y');

$fecha->modify('+1 day');
$fecha_manana = $fecha->format('m/d/Y');

?>


<div class="card">
    <!-- <div class="card-header py-2">
        <b>Gestión de reservas</b>
    </div> -->
    <div class="card-body">
        <form id="reserva_edit_find">
            <div class="row">
                <div class="col-sm-4">
                    <label>Hotel</label>
                    {!! UtilService::drowDownList('hotel_id', $establecimientos, '3', [
                        'id' => 'hotel_id',
                        'class' => 'form-control form-control-sm select2',
                        'required' => 'true',
                        'placeholder' => 'Seleccione...',
                    ]) !!}

                </div>
                <div class="col-6">
                    <label>Buscar (Nombre / Reserva / Email)</label>
                    <input type="text" name="search" id="edit_search" class="form-control form-control-sm"
                        value="" required />
                </div>

                <div class="col-sm-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="buscar_btn" type="submit">
                        Buscar Reserva
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#reserva_edit_find').validate({
            rules: {},
            messages: {},
            submitHandler: function(form) {
                buscar_editar(form);
            }
        });

    });


    function buscar_editar(form) {

        $(form).find('#buscar_btn').prop('disabled', true).html(loading_sm);

        // Determine current panel prefix (admin or external)
        @php($panelPrefix = (auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin')

        $.ajax({
            url: '{{ url($panelPrefix . "/reservas/reservation-search") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: $(form).serialize(),
        }).done(function(data) {

            editApp.folios_load(data.data);
        }).fail(function(error) {
            console.error('Error al buscar las reservas:', error);
        }).always(function() {
            $(form).find('#buscar_btn').prop('disabled', false).html(' Buscar Reserva');
        });;


    }
</script>
