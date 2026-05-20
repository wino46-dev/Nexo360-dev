<div class="card mt-2">
    <div class="card-header py-2">
        <b>Habitaciones Disponibles</b>
    </div>
    <div class="card-body">
        <div class="row ">
            <div class="col-sm-12">
                <table class="w-100 table table-bordered">
                    <tr>
                        <td><b>Tipo Habitación</b></td>
                        <td v-for="(day, day_index) in days" class="text-center text-nowrap"><b>@{{ date_format(day) }}</b>
                        </td>
                        <td style="width:110px"> </td>

                    </tr>
                    <template v-for="(hotel, hotel_index) in hotels" :key="hotel_index">
                        <tr v-for="(room_types, room_types_index) in hotel.room_types_avails" :key="room_types_index">
                            <td>@{{ room_types.name }}</td>
                            <td v-for="(day, day_index) in room_types.days" class="text-center">
                                Hab.: <b>@{{ day.avails }}</b><br />
                                € <b>@{{ day.price }}</b>
                            </td>
                            <td class="text-center">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="button" class="btn btn-sm btn-dark" style="width:35px"
                                        @click="type_room_select(hotel.hotel_id, room_types, '-')"><b>-</b></button>
                                    <input type="text" class="form-control form-control-sm text-center rounded-0"
                                        v-model="room_types.quantity" readonly style="width:40px" value="0">
                                    <button type="button" class="btn btn-sm btn-dark" style="width:35px"
                                        @click="type_room_select(hotel.hotel_id, room_types, '+')"><b>+</b></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="card mt-2">
    <div class="card-header  py-2">
        <b>Habitaciones Seleccionadas</b>
    </div>
    <div class="card-body">
        <div class="row ">
            <div class="col-sm-12">

                <div class="card mt-0 mb-1" v-for="(room, room_index) in rooms_select" :key="room_index">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-3 ">
                                Tipo:<br />
                                <b>@{{ room.type.name }}</b>
                            </div>
                            <div class="col-2">
                                Checkin:
                                <input type="date" v-model="room.checkin"
                                    class="form-control form-control-sm text-center" />
                            </div>
                            <div class="col-2">
                                Checkout:
                                <input type="date" v-model="room.checkout"
                                    class="form-control form-control-sm text-center" />
                            </div>
                            <div class="col-2  text-nowrap">
                                Adultos:
                                <input type="number" v-model="room.adults"
                                    class="form-control form-control-sm text-center" />
                            </div>
                            <div class="col-2">
                                Niños:
                                <input type="number" v-model="room.children"
                                    class="form-control form-control-sm text-center" />
                            </div>

                            <div class="col-1 d-flex align-items-end justify-content-center">
                                <button class="btn btn-danger btn-sm" @click="room_delete(room_index)">X</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Notas
                                <input type="texr" class="form-control form-control-sm" v-model="room.notes" />
                            </div>

                        </div>


                    </div>
                </div>
                <br />
                <div class="text-right">
                    <button class="btn btn-sm btn-success px-5" :disabled="isButtonDisabled"
                        @click="reservation_new_save()">Agregar Habitaciones</button>
                </div>
            </div>

        </div>
    </div>
</div>
<br /><br />
