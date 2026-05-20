<!-- Modal -->
<div class="modal fade" id="folio_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalles de la Reserva</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<script>


    function folio_detail(id){
        $('#folio_detail').modal('show');
        $('#folio_detail').find('.modal-body').html('Cargando...');

        $.ajax({
            url: '{{ url((request()->is('external*')?'external':'admin').'/reservation-api/folio-detail') }}?id=' + id + '&embed=1',
            method: 'GET'
        }).done(function(data) {

          $('#folio_detail').find('.modal-body').html(data);

        }).fail(function(error) {

            Toast.fire({
                icon: "error",
                title: "Error al cargar el detalle de la reserva"
            });
        });
    }

</script>
