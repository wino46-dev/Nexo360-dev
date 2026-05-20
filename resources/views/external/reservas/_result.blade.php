<div class="card mt-2">
    <div class="card-header py-2">
        <b>Habitaciones Disponibles</b>
    </div>
    <div class="card-body">
        <div class="row ">
            <div class="col-sm-12">
                <table class="w-100 table table-bordered">
                    <tr>
                        <td><b>Hotel</b></td>
                        <td><b>Tipo Habitación</b></td>
                        <td v-for="(day, day_index) in days" class="text-center text-nowrap"><b>@{{ date_format(day) }}</b>
                        </td>
                        <td style="width:100px"><b>Total</b></td>
                        <td style="width:110px"> </td>

                    </tr>
                    <template v-for="(hotel, hotel_index) in hotels" :key="hotel_index">
                        <tr v-for="(room_types, room_types_index) in hotel.room_types_avails" :key="room_types_index">
                            <td>@{{ hotel.hotel_name }}</td>
                            <td>@{{ room_types.name }}</td>
                            <td v-for="(day, day_index) in room_types.days" class="text-center">
                                Hab.: <b>@{{ day.avails }}</b><br />
                                € <b>@{{ day.price }}</b>
                            </td>
                            <td class="text-center">€ <b>@{{ room_types.total  }}</b></td>
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
                    <div class="card-body py-1">
                        <table class="w-100 table-padding-2">
                            <tr>
                                <td style="width:150px">
                                    Tipo:<br />
                                    <b>@{{ room.type.name }}</b>
                                </td>
                                <td style="width:120px">
                                    Checkin:
                                    <input type="date" v-model="room.checkin"
                                        class="form-control form-control-sm text-center" @change="room_select_change(room)" />
                                </td>
                                <td style="width:120px">
                                    Checkout:
                                    <input type="date" v-model="room.checkout"
                                        class="form-control form-control-sm text-center" @change="room_select_change(room)" />
                                </td>
                                <td style="width:100px">
                                    Adultos:
                                    <input type="number" v-model="room.adults"
                                        class="form-control form-control-sm text-center" />
                                </td>
                                <td style="width:100px">
                                    Niños:
                                    <input type="number" v-model="room.children"
                                        class="form-control form-control-sm text-center" />
                                </td>
                                
                                <td style="width:120px">
                                    Total:
                                    <input type="text" v-model="room.total" disabled class="form-control form-control-sm text-center font-weight-bold" />
                                </td>
                                <td style="width:30px" class="text-right p-2">
                                    <button class="btn btn-danger btn-sm" @click="room_delete(room_index)">X</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    Notas:
                                    <input type="texr" class="form-control form-control-sm" v-model="room.notes" />
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
                <br />
                <div class="text-right">
                    <button class="btn btn-sm btn-success px-5" :disabled="isButtonDisabled"
                        @click="info_client_modal()">Continuar</button>
                </div>
            </div>

        </div>
    </div>
</div>
<br /><br />
