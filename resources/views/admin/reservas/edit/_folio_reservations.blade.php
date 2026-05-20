<div class="text-right mt-2">
    <button type="button" class="btn btn-sm btn-success" @click="reservation_new_btn()">
        Agregar Habitaciones
    </button>
</div>
<table id="reservation_detail_table" class="table table-bordered table-striped table-hover mt-2">
    <thead>
        <tr>
            <th style="width:100px" class="text-center">Reserva </th>
            <th class="text-nowrap">Fechas</th>
            <th class="text-center text-nowrap">Noches</th>
            <th class="text-center text-nowrap">Adultos</th>
            <th class="text-center text-nowrap">Niños</th>
            <th class="text-center text-nowrap">Habitación</th>
            <th class="text-center text-nowrap">Servicios</th>
            <th class="text-nowrap">Precio</th>
            <th class="text-nowrap"> </th>

        </tr>
    </thead>
    <tbody>

        <tr v-for="(reservation, reservation_index) in folio.reservations" :key="reservation_index">
            <td class="text-center">
                @{{ reservation.name }}
                <div>
                    <span :class="['badge', reservation.stateCode === 'cancel' ? 'badge-danger' : 'badge-secondary']">
                        @{{ reservation.stateCode }}
                    </span>
                </div>
            </td>
            <td class="text-center">
                @{{ reservation.checkin2 }}<br />
                @{{ reservation.checkout2 }}
            </td>
            <td class="text-center">@{{ reservation.nights }}</td>
            <td class="text-center">@{{ reservation.adults }}</td>
            <td class="text-center">@{{ reservation.children }}</td>
            <td class="text-center">
                <span class="badge badge-secondary">
                    @{{ reservation.roomTypeName }}
                </span><br />
                @{{ reservation.roomName }}
            </td>
            <td class="text-center">@{{ reservation.numServices }}</td>
            <td class="text-right">@{{ reservation.priceTotal }} €</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i aria-hidden="true" class="fa fa-edit"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="reservation.stateCode != 'cancel'">
                        <a class="dropdown-item" href="#" @click="reservation_edit(reservation)">Modificar</a>
                        <a class="dropdown-item" href="#" @click="reservation_status_change(reservation,'cancel')">Cancelar</a>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="reservation.stateCode == 'cancel'">
                        
                        <a class="dropdown-item" href="#" @click="reservation_status_change(reservation, 'confirm')">Confirmar</a>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@include('admin.reservas.edit._folio_reservations_edit')
@include('admin.reservas.edit._folio_reservations_add')
