<div class="modal fade" id="reservation_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Checkin Finalizado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">CERRAR</button>        
      </div>
    </div>
  </div>
</div>

<script>
    
    function checkinFinalizar(reservation_id){
        $('#reservation_modal').find('.modal-dialog').css('margin-top',($('#call_conte').height() + 100) + 'px');

        $('#reservation_modal').modal('show');
        $('#reservation_modal').find('.modal-body').html('<br />' + loading1);
        $.ajax({
            url: "{{ url('evento-home-totems/reservation') }}/"+reservation_id+'?pms={{ $establecimiento->api_pms }}',
            method: 'GET',            
            cache: false,        
            success:function(response){               
                $('#reservation_modal').find('.modal-body').html(response);
            },
            error: function(response) {
                
            },
        });        
    }
    
</script>
<style>
    #reservation_modal .modal-lg {
        max-width: 90%;
    }
</style>