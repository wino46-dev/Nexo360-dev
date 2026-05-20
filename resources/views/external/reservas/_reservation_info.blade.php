<div class="modal fade" id="reservation_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle" @dblclick="data_reload()">
                    Reserva se ha creado con éxito
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-5" data-dismiss="modal">Volver</button>

            </div>
        </div>
    </div>
</div>
<script>
    var reservationInfoApp = new Vue({
        el: '#reservation_info',
        data: {
            hotel_id: null,
            folio_id: null,
            folio:null
        },
        mounted() {

        },
        methods: {
            data_reload(){
                this.data_load(this.hotel_id, this.folio_id);
            },
            data_load(hotel_id, folio_id) {
                console.log('hotel: ' + hotel_id)
                console.log('folio: ' + folio_id);

                $('#reservation_info').find('.modal-body').html(loading_sm);
                let $this = this;
                this.hotel_id = hotel_id;
                this.folio_id = folio_id;

                let formData = {
                    hotel_id: this.hotel_id,
                    folio_id: this.folio_id
                }
                $.ajax({
                    url: '{{ url('external/reservas/reservation-info') }}',
                    method: 'POST',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {

                    $('#reservation_info').find('.modal-body').html(data);

                }).fail(function(error) {
                    console.error('Error al consultar el folio:', error);
                }).always(function() {


                });
            },

        }
    });
</script>
