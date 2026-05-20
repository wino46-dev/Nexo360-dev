<div class="modal fade" id="reservation_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Checkin Finalizado</h5>
                <button type="button" onclick="reservation_modal_close()" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" onclick="reservation_modal_close()" class="btn btn-secondary px-5"
                    data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>
<script>
    var tmp_reservation_id = '';
    var tmp_checkin_partner_id = '';


    function reservation_resumen(reservation_id, checkin_partner_id = '') {

        tmp_reservation_id = reservation_id;
        tmp_checkin_partner_id = checkin_partner_id;

        $('#reservation_modal').modal('show');
        $('#reservation_modal').find('.modal-body').html('<br />' + loading1);
        $.ajax({
            url: "{{ url((request()->is('external*')?'external':'admin').'/call-manager/reservation-resumen') }}/" + reservation_id +
                '?checkin_partner_id=' + checkin_partner_id + '&pms={{ $establecimiento->api_pms }}&embed=1',
            method: 'GET',
            cache: false,
            success: function(response) {
                $('#reservation_modal').find('.modal-body').html(response);

            },
            error: function(response) {

            },
        });

    }

    function reservation_resumen_test() {
        reservation_resumen(tmp_reservation_id, tmp_checkin_partner_id);
    }

    function reservation_modal_close() {
        $.ajax({
            url: "{{ url('admin/call-manager/cerrar-parte-viajero-totem') }}",
            method: 'POST',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: {
                receptor_id: '{{ $control_actual->receptor_id }}',
                emisor_id: '{{ $control_actual->emisor_id }}',
                sesion_id: '{{ $control_actual->id }}',
            },
            success: function(response) {

            },
            error: function(response) {

            },
        });
    }
</script>
