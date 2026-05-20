<div class="card">
    <div class="card-body">
        <div class="row ">
            <div class="col-3">
                <div id="folio_status_container">

                </div>


            </div>
            <div class="col-3 d-flex align-items-center">

                <fieldset class="fieldset1" style="width:100%; padding:3px">
                    <legend>
                        <label>
                            <b> Filtro Fecha:</b> <input type="checkbox" class="ml-2" value="1"
                                id="filtro_fecha_check" checked>
                        </label>
                    </legend>
                    <table style="width:100%">
                        <tr>
                            <td>
                                Desde:<br />
                                <input type="date" name="filtro_fecha_inicio" id="filtro_fecha_inicio"
                                    class="form-control form-control-sm" value="{{ date('Y-m-d') }}" />
                            </td>
                            <td>
                                Hasta:<br />
                                <input type="date" name="filtro_fecha_final" id="filtro_fecha_final"
                                    class="form-control form-control-sm" value="{{ date('Y-m-d') }}" />
                            </td>
                        </tr>
                    </table>

                </fieldset>
            </div>

            <div class="col-4 d-flex align-items-center text-nowrap">
                <div class="mr-3"><b>Buscar: </b></div>
                <input value="" class="form-control  " id="reservation_search_input"
                    placeholder="No. Reserva, Nombre y/o Email..." />
            </div>
            <div class="col-2 d-flex align-items-center ">
                <button class="btn btn-success  w-100" onclick="folio_search('search')"> Buscar </button>

                <button type="button" class="btn btn-info ml-1" data-toggle="modal" data-target="#modalPush">Ayuda</button>

            </div>
            <div id="modalPush" class="modal fade bd-push-modal-lg" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body" style="padding: 20px">
                            {!! $ayudaStepTotem->eventos_push ?? '' !!}
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="row mt-2">
            <div class="col-12">
                <table id="folio_table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:100px" class="text-center">Reserva </th>
                            <th>Cliente</th>
                            <th class="text-center text-nowrap"> Cant. Hab</th>
                            <th class="text-center text-nowrap"> CheckIn Status</th>
                            <th class="text-nowrap">Check In</th>
                            <th class="text-nowrap">Check Out</th>
                            <th style="width:50px" class="text-nowrap">Total</th>
                            <th style="width:50px" class="text-nowrap">Pendiente</th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
    var reservation_input = $('#reservation_search_input');
    var folio_table = $('#folio_table');


    $(document).ready(function() {
        reservation_input.keypress(function(e) {
            if (e.which == 13) {
                folio_search('search');
            }
        });

        $('#filtro_fecha_check').change(function() {
            // Verificar si el checkbox está marcado
            if ($(this).is(':checked')) {
                // Habilitar los inputs si el checkbox está marcado
                $('#filtro_fecha_inicio, #filtro_fecha_final').prop('disabled', false);
            } else {

                $('#filtro_fecha_inicio, #filtro_fecha_final').prop('disabled', true);
            }
        });
        folioStatus();

    });

    function folioStatus() {
        $('#folio_status_container').html('<div class="pt-4">' + loading1 + '</div>');
        $.ajax({
            url: "{{ url((request()->is('external*')?'external':'admin').'/reservation-api/folio-status') }}?embed=1",
            method: 'GET',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                var $html = $('<div>').html(response);
                var $error = $html.find('#pms_error_block');
                var $cards = $html.find('#folio_status_cards');
                if ($error.length) {
                    $('#pms_error_global').html($error.html());
                } else {
                    $('#pms_error_global').empty();
                }
                if ($cards.length) {
                    $('#folio_status_container').html($cards.html());
                } else {
                    $('#folio_status_container').html(response);
                }
            },
            error: function(response) {
                Toast.fire({
                    icon: "error",
                    title: 'Error al cargar el estado de las reservas'
                });
            },
        });
    }

    function folio_search(type) {
        let url = '';
        folio_table.find('tbody').html(`
            <tr>
                <td colspan="8">
                    <div class="spinner-border mr-2" style="width:1rem; height:1rem" role="status"><span
                            class="sr-only">Loading...</span>
                    </div> Buscando..
                </td>
            </tr>`);
        if (type == 'search') {
            let input_data = reservation_input.val();


            if (!$.trim(input_data)) {
                folio_table.find('tbody').empty();
                return false;
            }
            let url_filter_date = '';
            if ($('#filtro_fecha_check').is(':checked')) {
                url_filter_date = '&date_start=' + $('#filtro_fecha_inicio').val() + '&date_end=' + $(
                        '#filtro_fecha_final')
                    .val();
            }
            url = '{{ url((request()->is('external*')?'external':'admin').'/reservation-api/folio-search') }}?q=' + input_data + url_filter_date + '&embed=1';
        } else {
            url = '{{ url((request()->is('external*')?'external':'admin').'/reservation-api/folio-search') }}?type=' + type + '&embed=1';
        }



        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json'
        }).done(function(data) {

            folio_table.find('tbody').empty();

            if (!data.data.length) {
                folio_table.find('tbody').html('<tr><td colspan="8">No hay reservas con este filtro</td></tr>');
                return false;
            }

            let tbody = '';
            data.data.forEach(function(item) {

                reserva = `<div>${item.name}</div>`;
                if (item.state == 'cancel') {
                    reserva += `<span class="badge badge-danger">${item.state}</span>`;
                } else {
                    reserva +=
                        `<div style="margin-top:-5px"><span class="badge badge-success">${item.state}</span></div>`;
                }
                btn_actions =
                    `<button class="btn btn-info btn-sm" onclick="folio_detail(${item.id})" title="Detalles"><i class="fa fa-list-alt" aria-hidden="true"></i></button>`;
                if (item.state != 'cancel') {
                    btn_actions +=
                        ` <button class="btn btn-success btn-sm" onclick="folio_select(${item.id}, \'${item.firstCheckin}', '${item.lastCheckout}', '${item.pendingAmount}')" title="Iniciar Check In"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>`;
                }
                tbody += `<tr>
                    <td class="text-center text-nowrap">${reserva}</td>
                    <td>
                        <b>${item.partnerName} </b>
                        <div class="font-weight-light" style=" font-size:85%">
                            <i class="fa fa-envelope-o mr-1" aria-hidden="true"></i> ${item.partnerEmail}
                        </div>
                        <div class="font-weight-light" style=" font-size:90%">
                            <i class="fa fa-phone mr-1" aria-hidden="true"></i> ${item.partnerPhone}
                        </div>
                    </td>
                    <td class="text-center">${item.reservations_count}</td>
                    <td class="text-center">${item.partners_onboard} / ${item.partners_count}</td>
                    <td class="text-center">${item.firstCheckin2}</td>
                    <td class="text-center">${item.lastCheckout2}</td>
                    <td class="text-right text-nowrap">${item.amountTotal} €</td>
                    <td class="text-right text-nowrap">${item.pendingAmount} €</td>
                    <td class="text-nowrap"> ${btn_actions} </td>
                </tr>`;
            });
            folio_table.find('tbody').append(tbody);

        }).fail(function(error) {
            error = JSON.parse(error.responseText);
            console.error('Error al obtener datos de la API:', error);

            folio_table.find('tbody').empty();
            Toast.fire({
                icon: "error",
                title: error.error
            });
        });

    }

    function folio_select(id, entrada, salida, pendiente) {

        Toast.fire({
            icon: "success",
            title: "Reserva seleccionada"
        });

        $('#reservation_detail_container').html(loading1);
        $('#checkin_partners').empty();

        $('#photoView').hide();
        $('#pagoFormContainer').hide();
        $('#grabarTarjetaContainer').hide();

        $('#pagoForm').find('#folio_id').val(id);

        if (entrada) {
            entrada = entrada.split(' ');
            if (entrada[0]) {
                $('#grabarTarjetaForm').find('#date_in').val(entrada[0]);
            }
            if (entrada[1]) {
                $('#grabarTarjetaForm').find('#time_in').val(entrada[1]);
            }
        }
        if (salida) {
            salida = salida.split(' ');
            if (salida[0]) {
                $('#grabarTarjetaForm').find('#date_out').val(salida[0]);
            }
            if (salida[1]) {
                $('#grabarTarjetaForm').find('#time_out').val(salida[1]);
            }
        }
        if (pendiente) {
            $('#pagoForm').find('#importe').val(pendiente);
            $('#pagoForm').find('#importe_manual').val(pendiente);
        }

        folio_current = id;
        showFolioDetail(id)
        payment_table_load();


        folio_table.find('tbody').empty();
        reservation_input.val('');

    }

    function showFolioDetail(id) {
        $.ajax({
            url: "{{ url((request()->is('external*')?'external':'admin').'/reservation-api/folio-detail-select') }}?embed=1",
            method: 'POST',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: {
                receptor_id: '{{ $control_actual->receptor_id }}',
                emisor_id: '{{ $control_actual->emisor_id }}',
                sesion_id: '{{ $control_actual->id }}',
                id: id
            },
            success: function(response) {
                $('#reservation_detail_container').html(response);
            },
            error: function(response) {

            },
        });
    }
</script>

@include('admin.callManager.reservation.folioDetailModal')
