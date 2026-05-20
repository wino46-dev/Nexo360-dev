<div class="modal fade" id="client_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Finalizar Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mt-0 mb-2">
                    <div class="card-header py-2">
                        <b>Resumen de la Reserva</b>
                    </div>
                    <div class="card-body py-2">

                        <table class="w-100">
                            <template v-for="(room, room_index) in rooms" :key="room_index">
                                <tr>
                                    <td><b>Habitación:</b><br />@{{ room.type.name }}</td>
                                    <td><b>Checkin:</b><br />@{{ date_format(room.checkin) }}</td>
                                    <td><b>Checkout:</b><br />@{{ date_format(room.checkout) }}</td>
                                    <td><b>Adultos:</b><br />@{{ room.adults }}</td>
                                    <td><b>Niños:</b><br />@{{ room.children }}</td>
                                    <td class="text-right"><b>Total:</b><br />€ @{{ room.total }}</td>
                                </tr>
                                <tr>
                                    <td class="border-bottom" colspan="5">Notas: @{{ room.notes }}</td>
                                    <td class="border-bottom"></td>
                                </tr>

                            </template>
                            <tr>
                                <td class="border-bottom text-right" colspan="5"><b>Total</b></td>
                                <td class="border-bottom text-right">€ <b>@{{ rooms_select_total_calculate(rooms) }}</b></td>

                            </tr>

                        </table>
                    </div>
                </div>
                <div class="card mt-0 ">
                    <div class="card-header py-2">
                        <b>Información del Cliente</b>
                    </div>
                    <div class="card-body py-2">
                        <form id="client_form">
                            <div class="row">
                                <div class="col-12">
                                    Nombre
                                    <input type="text" name="name" id="name" v-model="client.name"
                                        class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    Email
                                    <input type="text" name="email" id="email" v-model="client.email"
                                        class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    Mobile
                                    <input type="text" name="mobile" id="mobile"
                                        v-model="client.mobile"class="form-control">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-5" data-dismiss="modal">Volver</button>

                <button type="button" id="reservation_save_btn" style="width: 190px" class="btn btn-sm btn-success "
                    @click="reservation_save()">Guardar Reserva</button>
            </div>
        </div>
    </div>
</div>
<script>
    var clientModalApp = new Vue({
        el: '#client_modal',
        data: {
            client: {
                name: '',
                email: '',
                mobile: ''
            },
            rooms: []
        },
        mounted() {

        },
        methods: {
            data_load(rooms) {
                this.rooms = rooms;
            },
            data_reset() {
                this.client = {
                    name: '',
                    email: '',
                    mobile: ''
                };
                this.rooms = [];
            },
            reservation_save() {
                let $this = this;
                $('#reservation_save_btn').prop('disabled', true).html(loading_sm);
                let formData = {
                    client_info: this.client,
                    rooms: this.rooms
                }
                $.ajax({
                    url: '{{ url('admin/reservas/reservation-save') }}',
                    method: 'POST',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {

                    if (data.data) {
                        $this.reservation_info(data.hotel_id, data.data);
                    }
                }).fail(function(error) {
                    if (error.responseJSON) {
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
                    }


                }).always(function() {

                    $('#reservation_save_btn').prop('disabled', false).html('Guardar Reserva');

                });
            },
            reservation_info(hotel_id, folio_id) {
                this.data_reset();
                $('#client_modal').modal('hide');

                resultApp.reset();

                $('#reservation_info').modal('show');
                $('#reservation_info').find('.modal-title').html('Reserva se ha creado con éxito');
                reservationInfoApp.data_load(hotel_id, folio_id)

            },
            date_format(fecha) {
                const [year, month, day] = fecha.split('-');
                return `${day}/${month}/${year}`;
            },
            rooms_select_total_calculate(rooms) {
                
                let total = 0;
                Object.values(rooms).forEach((item, index) => {
                    total += parseFloat(item.total);
                });
                return total;
            }
        }
    });
</script>
