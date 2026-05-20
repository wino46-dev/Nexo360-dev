<div class="modal fade" id="reservation_costs_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="reservationCostsTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationCostsTitle">Desglose de costes (debug)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
    <style>
        /* Marcar como temporal/Debug */
        #reservationCostsTitle::after { content: ' (temporal)'; font-weight: normal; font-size: 90%; color: #888; }
        .costs-table td, .costs-table th { padding: .35rem .5rem; }
    </style>
</div>
<script>
    function reservation_costs(reservation_id) {
        $('#reservation_costs_modal').modal('show');
        $('#reservation_costs_modal .modal-body').html('<div class="py-4 text-center">' + loading1 + '</div>');
        $.ajax({
            url: "{{ url((request()->is('external*')?'external':'external').'/reservation-api/reservation-costs') }}/" + reservation_id + '?embed=1',
            method: 'GET',
            cache: false,
            success: function(html){
                $('#reservation_costs_modal .modal-body').html(html);
            },
            error: function(xhr){
                var msg = 'No se pudo cargar el desglose de costes';
                try{ var r = JSON.parse(xhr.responseText); if(r.error){ msg = r.error; } }catch(e){}
                $('#reservation_costs_modal .modal-body').html('<div class="text-danger">'+msg+'</div>');
            }
        });
    }
</script>
