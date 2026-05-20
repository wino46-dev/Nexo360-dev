<div class="modal fade" id="alice_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Validación Alice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer d-flex justify-content-between">        
        <button type="button" class="btn btn-danger px-5" onclick="alice_modal_reset()" >Reiniciar Validación</button>        
        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Cerrar</button>        
      </div>
    </div>
  </div>
</div>
<script>
  var checkin_id_current = '';
  function alice_modal(checkin_id){
      
    checkin_id_current = checkin_id;

    $('#alice_modal').modal('show');
    $('#alice_modal').find('.modal-body').html('<br />' + loading1);
    $.ajax({
        url: "{{ url('admin/alice/index') }}/"+checkin_id,
        method: 'GET',            
        cache: false,        
        success:function(response){               
            $('#alice_modal').find('.modal-body').html(response);
        },
        error: function(response) {
            
        },
    });  
  }

  function alice_modal_reset(){
      
      //$('#alice_modal').modal('show');
      $('#alice_modal').find('.modal-body').html('<br />' + loading1);
      $.ajax({
          url: "{{ url('admin/alice/index') }}/"+checkin_id_current+'?action=reset',
          method: 'GET',            
          cache: false,        
          success:function(response){               
              $('#alice_modal').find('.modal-body').html(response);
          },
          error: function(response) {
              
          },
      });  
    }



  function alice_modal_status(checkin_id){
    
    $('#alice_modal').find('.alice_modal_resul').html('<br />' + loading1);
    $.ajax({
        url: "{{ url('admin/alice/status') }}/"+checkin_id,
        method: 'GET',            
        cache: false,        
        success:function(response){               
            $('#alice_modal').find('.alice_modal_resul').html(response);
        },
        error: function(response) {
            
        },
    });  
    
  }

  function getDocument(checkin_id, side, alice_user_id, language, country, document_type){
    
    $.ajax({
        url: "{{ url("admin/alice/events") }}/"+checkin_id,
        method: 'POST',                      
        cache: false,        
        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},        
        data: {          
          emisor_id: '{{ $control_actual->emisor_id }}',
          receptor_id: '{{ $control_actual->receptor_id }}',
          sesion_id: '{{ $control_actual->id }}',
          action: 'capture',
          alice_user_id: alice_user_id,          
          side: side,
          language: language,
          country: country,
          document_type: document_type
        },
    }).done(function(data) {
        
        Toast.fire({
            icon: "success",
            title: 'Solicitando'
        });       
        
    }).fail(function(error) {            
        console.error('Error:', error);            
    });
  }

</script>