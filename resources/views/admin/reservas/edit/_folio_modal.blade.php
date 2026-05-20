<div class="modal fade" id="folio_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Modificar Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" v-if="folio_edit_loading">
                    <div class="spinner-border" style="width:1rem; height:1rem" role="status"><span
                            class="sr-only">Loading...</span>
                    </div>
                </div>
                <div v-if="!folio_edit_loading">
                    @include('admin.reservas.edit._folio_client')

                    @include('admin.reservas.edit._folio_reservations')
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-5" data-dismiss="modal">Volver</button>

                <!-- <button type="button" id="reservation_save_btn" style="width: 190px" class="btn btn-sm btn-success "
                    @click="folio_save()">Guardar Reserva</button> -->
            </div>
        </div>
    </div>
</div>
