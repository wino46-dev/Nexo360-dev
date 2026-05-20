<div class="card mt-2" v-if="reservation">
    <div class="card-header py-2">
        <b>Modificar Habitación</b>
    </div>
    <div class="card-body">
        <form id="reservation_form">

            <!-- <div class="row">
                <div class="col-4">
                    Tipo<br />
                </div>
            </div> -->
            <div class="row">

                <div class="col-3">
                    Checkin
                    <input type="date" v-model="reservation.checkin3" name="checkin"
                        class="form-control form-control-sm text-center" required />
                </div>
                <div class="col-3">
                    Checkout
                    <input type="date" v-model="reservation.checkout3" name="checkout"
                        class="form-control form-control-sm text-center" required />
                </div>
                <div class="col-3">
                    Adultos
                    <input type="number" v-model="reservation.adults" name="adults"
                        class="form-control form-control-sm text-center" required />
                </div>
                <div class="col-3">
                    Niños
                    <input type="number" v-model="reservation.children" name="children"
                        class="form-control form-control-sm text-center" />
                </div>
            </div>
            <div class="row mt-2">

                <div class="col-6">
                    Notas
                    <input type="text" class="form-control form-control-sm" name="notes"
                        v-model="reservation.notes" />
                </div>
                <div class="col-3 text-right pt-3">
                    <button type="button" class="btn btn-sm btn-secondary" style="width: 150px;"
                        @click="reservation = null">
                        Cerrar
                    </button>
                </div>
                <div class="col-3 text-right pt-3">
                    <button type="submit" class="btn btn-sm btn-success" style="width: 150px;">
                        <div v-if="reservation_save_loading" class="spinner-border" style="width:1rem; height:1rem"
                            role="status"><span class="sr-only">Loading...</span></div>
                        <div v-if="!reservation_save_loading">Guardar</div>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
