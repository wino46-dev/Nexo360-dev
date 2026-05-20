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
            url: '{{ url((request()->is('external*')?'external':'external').'/reservation-api/folio-detail') }}?id=' + id + '&embed=1',
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

    // Cargar/mostrar desglose de costes bajo la fila de la reserva
    function folio_costs(btn, reservation_id){
        var $tr = $(btn).closest('tr');
        var $table = $('#reservation_detail_table');
        var cols = $table.find('thead th').length;
        var $next = $tr.next('.reservation-costs-row');
        if(!$next.length){
            $next = $('<tr class="reservation-costs-row" style="display:none;"><td colspan="'+cols+'"><div class="py-3" id="reservation_costs_container_'+reservation_id+'"></div></td></tr>');
            $tr.after($next);
        }
        var $container = $('#reservation_costs_container_'+reservation_id);
        if($next.is(':visible')){
            $next.slideUp(150);
            return;
        }
        $container.html('<div class="py-3 text-center">'+ (window.loading1 || 'Cargando...') +'</div>');
        $.ajax({
            url: "{{ url((request()->is('external*')?'external':'external').'/reservation-api/reservation-costs') }}/" + reservation_id + '?embed=1',
            method: 'GET',
            cache: false
        }).done(function(html){
            $container.html(html);
            $next.slideDown(150);
        }).fail(function(xhr){
            var msg = 'No se pudo cargar el desglose de costes';
            try{ var r = JSON.parse(xhr.responseText); if(r.error){ msg = r.error; } }catch(e){}
            $container.html('<div class="text-danger">'+msg+'</div>');
            $next.slideDown(150);
        });
    }

</script>
