<form id="partes_form" method="POST" action="#">
    <div class="row">
        <div class="col-md-12 h4">
            <b>{{ $establecimiento->nombre }}</b>
        </div>
    </div>


    <div class="row">

        @csrf
        <input type="hidden" name="id" id="id" value="{{ $establecimiento->id }}">

        <div class="col-md-4">
            <div class="form-group">
                <label>Tipo de Envio</label>
                {!! UtilService::drowDownList(
                'partes_tipo_envio',
                ['ftp' => 'FTP'],
                $establecimiento->partes_tipo_envio,
                ['id' => 'partes_tipo_envio', 'class' => 'form-control', 'placeholder' => 'Seleccione...'],
                ) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Hora de Envio</label>
                <input class="form-control" type="time" name="partes_hora_envio" id="partes_hora_envio"
                    value="{{ old('partes_hora_envio', $establecimiento->partes_hora_envio) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Configuración FTP</label>
                <textarea class="form-control" name="partes_ftp_data" rows="10"
                    id="partes_ftp_data">{{ old('partes_ftp_data', $establecimiento->partes_ftp_data) }}
                </textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button type="button"
                id="partes_test_ftp_btn"
                class="btn btn-success px-5" onclick="partes_test_ftp()">Probar Conexión FTP</button>

        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <input type="date" class="form-control" id="backup_fecha"
                        value="<?php echo date('Y-m-d'); ?>" />
                </div>
                <div class="col-md-6">
                    <button type="button" id="partes_btn_guardar" onclick="partes_generar_copias()"
                        class="btn btn-primary w-100">Generar Envio</button>
                </div>
            </div>
        </div>
    </div>


</form>
<script>
    $(document).ready(function() {

        $('#partes_form').validate({
            rules: {},
            messages: {},
            submitHandler: function(form) {
                var formData = $(form).serialize();
                let id = $(form).find('#id').val();

                $('#partes_btn_guardar').attr('disabled', true).html(loading_sm);

                $.ajax({
                    url: '{{ url('admin/establecimientos/partes-config') }}/' + id,
                    method: 'POST',
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: formData,
                }).done(function(data) {
                    console.log(data);
                    Toast.fire({
                        icon: "success",
                        title: data['success']
                    });
                    $('#partes_modal').modal('hide');
                    $('#partes_btn_guardar').prop('disabled', false).html('Guardar');

                }).fail(function(error) {
                    console.error('Error al guardar el checkin:', error);
                    $('#partes_btn_guardar').prop('disabled', false).html('Guardar');
                });
            }
        });

    });

    function partes_generar_copias() {
        let id = $('#partes_form').find('#id').val();
        let fecha = $('#backup_fecha').val();


        $('#partes_btn_guardar').attr('disabled', true).html(loading_sm);

        $.ajax({
            url: '{{ url('admin/establecimientos/partes-backup') }}/' + id,
            method: 'POST',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: {
                id: id,
                fecha: fecha
            },
        }).done(function(data) {
            console.log(data);
            Toast.fire({
                icon: "success",
                title: data['success']
            });
            //$('#partes_btn_guardar').prop('disabled', false).html('Guardar');
            $('#partes_btn_guardar').attr('disabled', false).html('Generar Envio');

        }).fail(function(error) {
            Toast.fire({
                icon: "error",
                title: 'Error al generar copias de partes'
            });
            console.error('Error al generar copias:', error);
            $('#partes_btn_guardar').attr('disabled', false).html('Generar Envio');
            //$('#partes_btn_guardar').prop('disabled', false).html('Guardar');
        });
    }

    function partes_test_ftp() {
        let id = $('#partes_form').find('#id').val();
        let ftp_data = $('#partes_ftp_data').val();


        $('#partes_test_ftp_btn').attr('disabled', true).html(loading_sm);

        $.ajax({
            url: '{{ url('admin/establecimientos/partes-ftp-test') }}/' + id,
            method: 'POST',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: {
                id: id,
                ftp_data: ftp_data
            },
        }).done(function(data) {
            console.log(data);
            Toast.fire({
                icon: "success",
                title: data['success']
            });
            $('#partes_test_ftp_btn').prop('disabled', false).html('Probar Conexión FTP');

        }).fail(function(error) {
            console.error('Error al probar test fpt:', error);
            console.log(error)
            
            Toast.fire({
                icon: "error",
                title: 'Error al probar test ftp :' +  error.responseJSON.error
            });
            $('#partes_test_ftp_btn').prop('disabled', false).html('Probar Conexión FTP');
        });
    }
</script>