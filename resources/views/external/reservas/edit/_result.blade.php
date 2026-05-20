<?php
$fecha = new DateTime();

$fecha_hoy = $fecha->format('Y-m-d');

$fecha->modify('+1 day');
$fecha_manana = $fecha->format('Y-m-d');

?>
<div class="card mt-2" id="result_edit_app">
    <div class="card-header py-2">
        <b>Reservas encontradas</b>
    </div>
    <div class="card-body">
        <div class="row ">
            <div class="col-sm-12">
                <table class="w-100 table table-bordered">
                    <tr>
                        <td><b>Reserva</b></td>
                        <td><b>Cliente</b></td>
                        <td><b> Habitaciones</b></td>
                        <td><b>Check In</b></td>
                        <td><b>Check Out</b></td>
                        <td><b>Total</b></td>
                        <td><b>Pendiente</b></td>
                        <td style="width:80px"> </td>

                    </tr>
                    <template v-for="(folio, folio_index) in folios_list" :key="folio_index">
                        <tr>
                            <td class="text-center">@{{ folio.name }}
                                <br />
                                <span class="badge badge-secondary">@{{ folio.state }}</span>
                            </td>
                            <td>
                                @{{ folio.partnerName }}<br />
                                <div class="font-weight-light" style=" font-size:85%">
                                    <i class="fa fa-envelope-o mr-1" aria-hidden="true"></i> @{{ folio.partnerEmail }}
                                </div>
                                <div class="font-weight-light" style=" font-size:90%">
                                    <i class="fa fa-phone mr-1" aria-hidden="true"></i> @{{ folio.partnerPhone }}
                                </div>
                            </td>
                            <td class="text-center">@{{ folio.reservations_count }}</td>
                            <td class="text-center">@{{ folio.firstCheckin2 }}</td>
                            <td class="text-center">@{{ folio.lastCheckout2 }}</td>
                            <td class="text-right text-nowrap">@{{ folio.amountTotal }} €</td>
                            <td class="text-right text-nowrap">@{{ folio.pendingAmount }} €</td>

                            <td class="text-center text-nowrap">
                                <button type="button" class="btn btn-sm btn-info" style="width:35px"
                                    @click="folio_info(folio)">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-success" style="width:35px"
                                    @click="folio_edit(folio)">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </table>
            </div>

        </div>
    </div>
    @include('external.reservas.edit._folio_modal')
</div>

<script>
    $(document).ready(function() {


    });

    var editApp = new Vue({
        el: '#result_edit_app',
        data: {
            hotel_id: null,
            folios_list: [],
            folio: {

            },
            folio_edit_loading: false,
            folio_client_edit: false,
            folio_cliente_save_loading: false,
            reservation: null,
            reservation_save_loading: false,

            reservation_new_show: false,
            reservation_new_data: null,
            reservation_new_save_loading: false,

            reservation_new_find_data: null,
            reservation_new_find_loading: false,

            hotels: [],
            days: [],
            type_room_select_list: [],
            rooms_select: []
        },
        mounted() {

        },
        methods: {
            reset() {
                // this.folios_list = [];
                this.folio = null;
                this.reservation_new_show = false;
                this.hotels = [];
                this.days = [];
                this.type_room_select_list = [];
                this.rooms_select = [];

            },
            folio_form_validate() {
                setTimeout(function() {
                    $('#folio_client_form').validate({
                        rules: {},
                        messages: {},
                        submitHandler: function(form) {
                            editApp.folio_client_save(form);
                        }
                    });
                }, 500);
            },
            reservation_form_validate() {
                setTimeout(function() {
                    $('#reservation_form').validate({
                        rules: {},
                        messages: {},
                        submitHandler: function(form) {
                            editApp.reservation_save(form);
                        }
                    });
                }, 500);
            },
            reservation_new_find_validate() {
                setTimeout(function() {
                    $('#reservation_find_form').validate({
                        rules: {},
                        messages: {},
                        submitHandler: function(form) {
                            editApp.reservation_new_find_action(form);
                        }
                    });
                }, 500);
            },
            reservation_new_form_validate() {
                setTimeout(function() {
                    $('#reservation_new_form').validate({
                        rules: {},
                        messages: {},
                        submitHandler: function(form) {
                            editApp.reservation_new_save(form);
                        }
                    });
                }, 500);
            },


            folios_load(data) {

                this.folios_list = data;
            },
            folio_info(folio) {
                console.log('folio_info');
                console.log(folio);

                $('#reservation_info').modal('show');
                $('#reservation_info').find('.modal-title').html('Reserva');

                reservationInfoApp.data_load(folio.hotel_id, folio.id);
            },
            folio_edit(folio) {
                console.log('folio_edit');
                console.log(folio);

                let $this = this;
                $this.reset();

                $('#folio_modal').modal('show');
                $this.folio_edit_loading = true;
                $this.folio_cliente_save_loading = false;
                $this.reservation_save_loading = false;



                $this.reservation = null;

                let formData = {
                    hotel_id: folio.hotel_id,
                    folio_id: folio.id
                }

                $.ajax({
                    url: '{{ url('external/reservas/folio-edit') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {
                    $this.folio = data.data;
                    $this.folio_form_validate();

                }).fail(function(error) {
                    console.error('Error al consultar el folio:', error);
                }).always(function() {
                    $this.folio_edit_loading = false;
                });
            },
            folio_client_save(form) {
                let $this = this;

                let formData = this.folio
                formData.action = 'client_update';
                $this.folio_cliente_save_loading = true;

                $.ajax({
                    url: '{{ url('external/reservas/folio-update') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {
                    Toast.fire({
                        icon: "success",
                        title: "Se actualizó correctamente"
                    });
                    $this.folio_client_edit = false;


                }).fail(function(error) {
                    console.error('Error al consultar el folio:', error);
                    $this.folio_cliente_save_loading = false;
                }).always(function() {
                    $this.folio_cliente_save_loading = false;
                });

            },
            reservation_edit(reservation) {

                this.reservation = reservation;
                this.reservation_form_validate();
            },
            reservation_save(form) {
                let $this = this;

                let formData = $this.reservation;
                formData.action = 'update';
                $this.reservation_save_loading = true;

                $.ajax({
                    url: '{{ url('external/reservas/reservation-update') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {
                    if (data?.data?.error) {
                        Toast.fire({
                            icon: "error",
                            title: data.data.message
                        });

                        return false;
                    }
                    $this.reservation = null;
                    Toast.fire({
                        icon: "success",
                        title: "Se actualizó correctamente"
                    });
                    $this.folio_edit({
                        hotel_id: $this.folio.hotel_id,
                        id: $this.folio.id
                    });


                }).fail(function(error) {
                    console.error('Error al actualizar la reserva:', error);
                    $this.reservation_save_loading = false;
                }).always(function() {
                    $this.reservation_save_loading = false;
                });

            },
            reservation_status_change(reservation, status) {
                let $this = this;

                let formData = reservation;
                formData.action = 'update-status';
                formData.status = status;
                $this.reservation_save_loading = true;

                $.ajax({
                    url: '{{ url('external/reservas/reservation-update') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {
                    if (data?.data?.error) {
                        Toast.fire({
                            icon: "error",
                            title: data.data.message
                        });
                        return false;
                    }
                    Toast.fire({
                        icon: "success",
                        title: "Actualizando estado de la Reserva"
                    });
                    $this.folio_edit({
                        hotel_id: $this.folio.hotel_id,
                        id: $this.folio.id
                    });

                }).fail(function(error) {
                    console.error('Error al actualizar estado de la reserva:', error);
                    //$this.reservation_save_loading = false;
                }).always(function() {
                    //$this.reservation_save_loading = false;
                });
            },
            reservation_new_btn() {
                this.reservation_new_find_validate();

                this.reservation_new_find_data = {
                    'checkin': '{{ $fecha_hoy }}',
                    'checkout': '{{ $fecha_manana }}',
                    'adults': 1,
                    'children': 0,
                    'hotel_id': this.folio.hotel_id
                }
                this.reservation_new_show = true;
            },
            reservation_new_find_action() {
                let $this = this;
                this.reservation_new_find_loading = true;

                $.ajax({
                    url: '{{ url('external/reservas/buscar-disponibilidad') }}',
                    method: 'POST',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: $this.reservation_new_find_data,
                }).done(function(data) {
                    console.log(data);
                    $this.reservation_new_avails(data.data);
                    //resultApp.show(data);

                }).fail(function(error) {
                    console.error('Error al buscar disponibilidad:', error);
                }).always(function() {
                    $this.reservation_new_find_loading = false;
                });


            },
            reservation_new_avails(data) {
                let $this = this;
                this.hotels = data;
                this.days = [];
                this.hotels[0]['days'].forEach((item, index) => {
                    $this.days.push(item.date);
                });
                this.type_room_select_list = [];
                this.rooms_select = [];
                console.log($this.hotels);
                console.log($this.days);
            },
            type_room_select(hotel_id, type, action) {


                if (action == '+') {
                    if (type.quantity <= 2) {
                        type.quantity++;
                        this.rooms_action(type, action, hotel_id);
                    }
                } else {
                    if (type.quantity >= 1) {
                        type.quantity = parseInt(type.quantity) - 1;
                        this.rooms_action(type, action, hotel_id);
                    }
                }
            },
            room_delete(index) {
                if (confirm('Seguro desea eliminar esta habitación?')) {
                    this.rooms_select.splice(index, 1);
                }
            },
            rooms_action(type, action, hotel_id) {
                let $this = this;
                if (action == '+') {

                    let children = 0;
                    if (this.rooms_select.length == 0) {
                        children = $this.reservation_new_find_data.children;
                    }


                    this.rooms_select.push({
                        type: type,
                        type_id: type.id,
                        checkin: $this.reservation_new_find_data.checkin,
                        checkout: $this.reservation_new_find_data.checkout,
                        adults: $this.reservation_new_find_data.adults,
                        children: children,
                        notes: '',
                        hotel_id: hotel_id
                    })
                } else {
                    for (let i = this.rooms_select.length - 1; i >= 0; i--) {
                        if (this.rooms_select[i].type_id === type.id) {
                            this.rooms_select.splice(i, 1);
                            break;
                        }
                    }
                }
            },
            reservation_new_save() {
                let $this = this;
                //  $('#reservation_save_btn').prop('disabled', true).html(loading_sm);
                let formData = {
                    folio: this.folio,
                    hotel_id: this.folio.hotel_id,
                    rooms: this.rooms_select
                }
                console.log(formData);
                $.ajax({
                    url: '{{ url('external/reservas/reservation-add') }}',
                    method: 'POST',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {

                    $this.folio_edit($this.folio);

                    Toast.fire({
                        icon: "success",
                        title: "Se agregaron las habitaciones correctamente"
                    });

                    //   $this.reservation_info(data.hotel_id, data.data);

                }).fail(function(error) {
                    /*if (error.responseJSON) {
                        console.error('Error:', error.responseJSON.message);

                        Toast.fire({
                            icon: "error",
                            title: error.responseJSON.message
                        });
                    } else {
                        console.error('Error en la solicitud:', textStatus);
                        Toast.fire({
                            icon: "error",
                            title: 'Error en la solicitud: ' + textStatus
                        });
                    }*/


                }).always(function() {

                    // $('#reservation_save_btn').prop('disabled', false).html('Guardar Reserva');

                });

            },
            date_format(fecha) {
                const [year, month, day] = fecha.split('-');
                return `${day}/${month}/${year}`;
            }
        },
        computed: {
            isButtonDisabled() {
                if (this.hotels.length > 0 && Array.isArray(this.hotels[0]['room_types_avails'])) {
                    return this.hotels[0]['room_types_avails'].reduce((sum, roomType) => sum + roomType
                        .quantity, 0) <= 0
                } else {
                    return true;
                }
            }
        },
    });
</script>
