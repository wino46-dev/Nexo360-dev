
<form id="writeCardNotification<?php echo $id ?>">
    @csrf
    <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
    <input type="hidden" name="step" id="step" value="1">
    <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}">
    <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}">
    <div class="row">
        <div class="col-12 text-right">
            <button class="btn btn-info btn-sm" type="submit">
                Notificar Colocación de tarjeta
            </button>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {

        $('#writeCardNotification<?php echo $id ?>').validate({
            rules: {},
            messages: {},
            submitHandler: function(form) {

                var formData = $(form).serialize();

                $.ajax({
                    url: '{{ route("external.grabacion-tarjeta.notify-put-card-on-reader") }}',
                    method: 'POST',
                    cache: false,
                    processData: false,
                    headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                    //dataType: 'json'
                    data: formData,
                }).done(function(data) {
                    console.log(data);
                    Toast.fire({
                        icon: "success",
                        title: data['success']
                    });

                  //  $('#partner_btn_save_').prop('disabled', false).html('Hacer CheckIn');

                }).fail(function(error) {
                    console.error('Error al guardar el checkin:', error);
                });


            }
        });

    });
</script>
