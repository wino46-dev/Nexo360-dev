<!--style="display:none" -->

<div id="grabarTarjetaContainer<?php echo $id ?>" class="card" >
    <div class="card-header p-2 d-flex justify-content-between">
        <b><i class="fa fa-file-o" aria-hidden="true"></i> Grabación Tarjeta</b>
        <button type="button" class="btn btn-light btn-sm py-0 px-1" onclick="write_card_table_load<?php echo $id ?>()">
            <i class="fa fa-refresh" aria-hidden="true"></i>
        </button>
    </div>
    <div class="card-body p-2">
        @include('external.callManager.writeCardNotification', ['id'=> $id])

        <form id="grabarTarjetaForm<?php echo $id ?>">
            @csrf
            <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
            <input type="hidden" name="status" id="status" value="Pendiente"></input>
            <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}">
            <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}">
            <input type="hidden" name="card_qty" value="1">
            <input type="hidden" name="uid_card" id="uid_card" value="Undefined">
            <div class="row pt-2">
                <div class="col-6">
                    {{ trans('cruds.grabacionTarjetum.fields.date_in') }}<br />
                    <input class="form-control form-control-sm" type="date" name="date_in" id="date_in" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}" required>
                </div>
                <div class="col-6">
                    {{ trans('cruds.grabacionTarjetum.fields.time_in') }}<br />
                    <input class="form-control form-control-sm" type="time" name="time_in" id="time_in" value="{{date('H:i:') . '00'}}" required>
                </div>
                <div class="col-6 pt-2">
                    {{ trans('cruds.grabacionTarjetum.fields.date_out') }}<br />
                    <input class="form-control form-control-sm" type="date" name="date_out" id="date_out" value="{{ old('date_out') }}" min="{{date('Y-m-d')}}" required>
                </div>
                <div class="col-6 pt-2">
                    {{ trans('cruds.grabacionTarjetum.fields.time_out') }}
                    <input class="form-control form-control-sm" type="time" name="time_out" id="time_out" value="{{ $establecimiento->departure_time_card }}" required>
                </div>
                <div class="col-6 pt-2">
                    <div class="d-flex justify-content-between">
                        {{ trans('cruds.grabacionTarjetum.fields.room_no') }} <span class="font-weight-light">0/9999</span>
                    </div>
                    <input class="form-control form-control-sm" type="text" name="room_no" id="room_no" step="1" required>
                </div>
                <div class="col-6 pt-2">
                    <div class="d-flex justify-content-between">
                        {{ trans('cruds.grabacionTarjetum.fields.room_no_2') }}<span class="font-weight-light">0/9999</span>
                    </div>
                    <input class="form-control form-control-sm" type="text" name="room_no_2" id="room_no_2" step="1">
                </div>
                <div class="col-6 pt-2">
                    <div class="d-flex justify-content-between">
                        {{ trans('cruds.grabacionTarjetum.fields.room_no_3') }}<span class="font-weight-light">0/9999</span>
                    </div>

                    <input class="form-control form-control-sm" type="text" name="room_no_3" id="room_no_3" step="1">
                </div>
                <div class="col-6 pt-2">
                    {{ trans('cruds.grabacionTarjetum.fields.seq_mode') }}<br />
                    <select class="form-control form-control-sm" name="seq_mode" id="seq_mode" required>
                        <option value="C" selected >C</option>
                        <option value="N">N</option>
                    </select>
                </div>
                <div class="col-6 pt-2">
                    {{ trans('cruds.grabacionTarjetum.fields.safe_box_helper') }}<br />
                    <input class="form-control form-control-sm" type="text" name="safe_box" id="safe_box" value="0" required>
                </div>
                <div class="col-12 pt-2">
                    {{ trans('cruds.grabacionTarjetum.fields.common_doors') }}<br />
                    <textarea class="form-control form-control-sm mb-3" style="display:none" name="common_doors" id="common_doors" rows="2"></textarea>

                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all-zonas" style="border-radius: 0">Seleccionar globales</span>
                        <span class="btn btn-info btn-xs deselect-all-zonas" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2" name="common_doors_array[]" id="common_doors_array" multiple>
                        @foreach($zonas_comunes as $idd => $zona)
                        <option value="{{ $zona['codigo'] }}" {{ !empty($zona['global']) ? 'selected' : '' }} class="{{ !empty($zona['global']) ? 'global' : '' }}">
                            {{ $zona['nombre'] }}
                            {{ !empty($zona['global']) ? '(global)' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 text-right pt-2 ">
                    <button class="btn btn-success btn-sm px-5" type="submit" id="writeCardSave<?php echo $id ?>" style="min-width:173px">
                        Grabar Tarjeta
                    </button>
                </div>


            </div>
        </form>
        <br />
        <div id="write_card_conte_table<?php echo $id ?>">

        </div>

    </div>
</div>
<script>
    $(document).ready(function() {
        write_card_table_load<?php echo $id ?>();

        $('#grabarTarjetaForm<?php echo $id ?>').validate({
            rules: {},
            messages: {},
            submitHandler: function(form) {

                var valoresSeleccionados = $('#grabarTarjetaForm<?php echo $id ?>').find('#common_doors_array').val();

                if (valoresSeleccionados.length > 0) {
                    var resultado = valoresSeleccionados.join('');
                    $('#grabarTarjetaForm<?php echo $id ?>').find('#common_doors').val(resultado);
                } else {
                    $('#grabarTarjetaForm<?php echo $id ?>').find('#common_doors').val('');
                }

                let time_in = $('#grabarTarjetaForm<?php echo $id ?>').find('#time_in').val();
                let time_out = $('#grabarTarjetaForm<?php echo $id ?>').find('#time_out').val();

                const regex = /^(\d{2}):(\d{2})$/;
                const match = time_out.match(regex);

                if (match) {
                    time_out = `${match[1]}:${match[2]}:00`;
                    $('#grabarTarjetaForm<?php echo $id ?>').find('#time_out').val(time_out);
                }

                var formData = $(form).serialize();
                formData += '&folio_id=' + folio_current;

                $('#writeCardSave<?php echo $id ?>').html(loading_sm).prop('disabled', true);

                $.ajax({
                    url: '{{ route("external.grabacion-tarjeta.store") }}',
                    method: 'POST',
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    //dataType: 'json'
                    data: formData,
                }).done(function(data) {
                    console.log(data);
                    write_card_table_load<?php echo $id ?>();
                    Toast.fire({
                        icon: "success",
                        title: 'Grabando Tarjeta'
                    });
                    $('#writeCardSave<?php echo $id ?>').html('Grabar Tarjeta').prop('disabled', false);


                }).fail(function(error) {
                    console.error('Error al guardar el checkin:', error);
                    $('#writeCardSave<?php echo $id ?>').html('Grabar Tarjeta').prop('disabled', false);
                    Toast.fire({
                        icon: "error",
                        title: 'Ocurrio un error al grabar la tarjeta'
                    });
                });


            }
        });

        $('#grabarTarjetaForm<?php echo $id ?>').find('.select-all-zonas').click(function() {
            let $select2 = $(this).parent().siblings('.select2')
            $select2.find('option.global').prop('selected', 'selected')
            $select2.trigger('change')
        })
        $('#grabarTarjetaForm<?php echo $id ?>').find('.deselect-all-zonas').click(function() {
            let $select2 = $(this).parent().siblings('.select2')
            $select2.find('option').prop('selected', '')
            $select2.trigger('change')
        })

    });

    function write_card_table_load<?php echo $id ?>() {
        $('#write_card_conte_table<?php echo $id ?>').html(loading1);
        $.ajax({
            url: "{{ url('external/call-manager/write-card-table') }}",
            method: 'GET',
            data: { folio_id: folio_current },
            cache: false,
            success: function(response) {
                $('#write_card_conte_table<?php echo $id ?>').html(response);
            },
            error: function(response) {

            },
        });
    }
</script>
