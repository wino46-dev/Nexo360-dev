<div class="modal fade" id="sh360_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">OCR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function sh360_modal(checkin_id, form_id) {

        $('#sh360_modal').modal('show');
        $('#sh360_modal').find('.modal-body').html('<br />' + loading1);
        $.ajax({
            url: "{{ url('admin/sh360-ocr/index') }}/" + checkin_id,
            method: 'GET',
            cache: false,
            success: function(response) {
                $('#sh360_modal').find('.modal-body').html(response);
            },
            error: function(response) {
                Toast.fire({
                    icon: "error",
                    title: 'Ha ocurrido un error al obtener datos, <br />por favor intentelo de nuevo'
                });
                $('#sh360_modal').find('.modal-body').html('');
                $('#sh360_modal').modal('hide');
            },
        });
    }
</script>
