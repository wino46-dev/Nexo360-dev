<div class="modal fade" id="partes_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Configuración Envio Partes Viajeros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer ">

                <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success px-5" type="submit"
                    id="partes_btn_guardar"  style="width: 150px "
                    onclick="partes_envios_guardar()">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function partes_envios(id) {

        $('#partes_modal').modal('show');
        $('#partes_modal').find('.modal-body').html('<br />' + loading1);
        $.ajax({
            url: "{{ url('admin/establecimientos/partes-config') }}/" + id,
            method: 'GET',
            cache: false,
            success: function(response) {
                $('#partes_modal').find('.modal-body').html(response);
            },
            error: function(response) {

            },
        });
    }



    function partes_envios_guardar() {
        $('#partes_form').submit();
    }
</script>
