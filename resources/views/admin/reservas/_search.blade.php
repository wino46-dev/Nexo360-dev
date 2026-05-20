<?php
$fecha = new DateTime();

$fecha_hoy = $fecha->format('d/m/Y');

$fecha->modify('+1 day');
$fecha_manana = $fecha->format('d/m/Y');
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="card">
    <!-- <div class="card-header py-2">
        <b>Gestión de reservas</b>
    </div> -->
    <div class="card-body">
        <form id="reserva_find">
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
                <div class="col-6 col-sm-3">

                    <label>Fechas</label>
                    <input type="text" class="form-control form-control-sm text-center" id= "search_daterange"
                        name="daterange" value="{{ $fecha_hoy }} - {{ $fecha_manana }}" />
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-6">
                            <label>Adultos</label>
                            <input type="number" name="adults" id="search_adults" class="form-control form-control-sm text-center"
                                value="2" />
                        </div>
                        <div class="col-6">
                            <label>Niños</label>
                            <input type="number" name="children" id="search_children" class="form-control form-control-sm text-center"
                                value="0" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="buscar_btn" type="button"
                        onclick="buscar_disponibilidad()">
                        Buscar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
    // Determine current panel prefix (admin or external)
    @php($panelPrefix = (auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin')

    $('#search_daterange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    function buscar_disponibilidad() {
        if ($('#hotel_id').val() === "" || $('#hotel_id').val() === "0") {
            Toast.fire({
                icon: "error",
                title: 'Por favor seleccione el hotel'
            });
            return false;
        }
        $('#buscar_btn').prop('disabled', true).html(loading_sm);
        let formData = $('#reserva_find').serialize();
        $.ajax({
            url: '{{ url($panelPrefix . "/reservas/buscar-disponibilidad") }}',
            method: 'POST',
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            //dataType: 'json'
            data: formData,
        }).done(function(data) {
            resultApp.show(data.data);

            let formData = getFormData('#reserva_find');

            resultApp.reservation_info = formData;

            if (window.loadARIForReservationPage) {
                window.loadARIForReservationPage();
            }

        }).fail(function(error) {
            console.error('Error al guardar el checkin:', error);
        }).always(function() {
            $('#buscar_btn').prop('disabled', false).html('Buscar');
        });


    }
</script>
