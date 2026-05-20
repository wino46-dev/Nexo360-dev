<div class="card mt-2" v-if="reservation_new_show">
    <div class="card-header py-2">
        <b>Agregar Habitación</b>
    </div>
    <div class="card-body">
        <form id="reservation_find_form">
            <div class="row">

                <div class="col-3">
                    Checkin
                    <input type="date" v-model="reservation_new_find_data.checkin" name="checkin"
                        class="form-control form-control-sm text-center" required />
                </div>
                <div class="col-3">
                    Checkout
                    <input type="date" v-model="reservation_new_find_data.checkout" name="checkout"
                        class="form-control form-control-sm text-center" required />
                </div>
                <div class="col-3">
                    Adultos
                    <input type="number" v-model="reservation_new_find_data.adults" name="adults"
                        class="form-control form-control-sm text-center" required />
                </div>
                <div class="col-3">
                    Niños
                    <input type="number" v-model="reservation_new_find_data.children" name="children"
                        class="form-control form-control-sm text-center" />
                </div>
            </div>
            <div class="row mt-2">

                <div class="col-12 text-right">
                    <button type="submit" style="width:155px" class="btn btn-sm btn-primary">
                        <div v-if="reservation_new_find_loading" class="spinner-border" style="width:1rem; height:1rem"
                            role="status"><span class="sr-only">Loading...</span></div>
                        <div v-if="!reservation_new_find_loading">Buscar Disponibilidad</div>
                    </button>
                </div>
            </div>


        </form>
        @include('admin.reservas.edit._folio_reservations_add_reservation')
    </div>
</div>
