<div class="card mt-2">
    <div class="card-header py-2">
        <b>Cliente</b>
    </div>
    <div class="card-body">
        <form id="folio_client_form">
            <div class="row">
                <div class="col-2 d-inline-flex">
                    <label>Nombre</label>
                </div>
                <div class="col-10 d-inline-flex">
                    <input type="text" name="cli_name" id="cli_name" class="form-control form-control-sm"
                        :disabled="!folio_client_edit" v-model="folio.partnerName" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-2">
                    Email:
                </div>
                <div class="col-sm-4">
                    <input type="email" name="cli_email" id="cli_email" class="form-control form-control-sm"
                        :disabled="!folio_client_edit" v-model="folio.partnerEmail" required>
                </div>
                <div class="col-sm-2">
                    Mobile
                </div>
                <div class="col-sm-4">
                    <input type="text" name="cli_mobile" id="cli_mobile" v-model="folio.partnerPhone"
                        :disabled="!folio_client_edit" class="form-control  form-control-sm" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <button type="button" v-on:click="folio_client_edit = true" v-if="!folio_client_edit"
                        class="btn btn-sm btn-primary" style="width: 150px;">
                        Modificar Cliente
                    </button>
                </div>
                <div class="col-12 text-right" v-if="folio_client_edit == true">

                    <button type="button" v-on:click="folio_client_edit = false" class="btn btn-sm btn-secondary"
                        style="width: 150px;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm btn-success ml-2" style="width: 150px;">
                        <div v-if="folio_cliente_save_loading" class="spinner-border" style="width:1rem; height:1rem"
                            role="status"><span class="sr-only">Loading...</span></div>
                        <div v-if="!folio_cliente_save_loading">Guardar Cliente</div>
                    </button>

                </div>
            </div>
        </form>
    </div>
</div>
