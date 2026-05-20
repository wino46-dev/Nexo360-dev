<?php
$pagos_totem_show = true;
$pagos_manual_show = true;
if($establecimiento->api_pms != 'local'){
    if(empty($establecimiento->pms_payment_method_totem)){
        $pagos_totem_show = false;
    }
    if(empty($establecimiento->pms_payment_method_manual)){
        $pagos_manual_show = false;
    }
}

?>
<div id="pagoFormContainer" class="card"  style="display:none">
    <div class="card-header p-2 d-flex justify-content-between">
        <b><i class="fa fa-credit-card" aria-hidden="true"></i> Pagos</b>
        @if($pagos_totem_show || $pagos_manual_show)
            <button type="button" class="btn btn-light btn-sm py-0 px-1" onclick="payment_table_load()">
                <i class="fa fa-refresh" aria-hidden="true"></i>
            </button>
        @endif
    </div>
    <div class="card-body p-2">

            <form id="pagoForm">
                @csrf
                <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
                <input type="hidden" name="estado" id="estado" value="Pendiente"></input>
                <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}">
                <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}">
                <input type="hidden" name="tipo_operacion" value="PAGO">
                <input type="hidden" name="pms" id="pms" value="{{ $establecimiento->api_pms }}">
                <input type="hidden" name="origen" id="origen" value="">
                <input type="hidden" name="folio_id" id="folio_id" value="">
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li class="nav-item ">
                        <a class="nav-link nav-link active" href="#pago_totem" role="tab" data-toggle="tab">
                            TOTEM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pago_manual" role="tab" data-toggle="tab">
                            MANUAL
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="pago_totem">
                        <div class="row">
                            @if($pagos_totem_show)
                                <div class="col-4">
                                    Importe:<br />
                                    <input class="form-control form-control-sm" type="number" name="importe" id="importe" step="0.01" required>
                                </div>
                                <div class="col-4">
                                    Factura:<br />
                                    <input class="form-control form-control-sm" type="text" name="factura" id="factura" required>
                                </div>
                                <div class="col-4 d-flex align-items-end">
                                    <button class="btn btn-success btn-block btn-sm" type="button" id="payment_btn_totem" onclick="payment_btn_save('totem')">
                                        Solicitar
                                    </button>
                                </div>
                            @else
                                <input type="hidden" name="importe" id="importe" step="0.01" required>
                                <input type="hidden" name="factura" id="factura" required>
                                <div class="col-12 pt-3">
                                    <div class="alert alert-warning"> No hay métodos de pago configurados</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="pago_manual">
                        <div class="row">
                            @if($pagos_manual_show)
                                <div class="col-2">
                                    Importe:<br />
                                    <input class="form-control form-control-sm" type="number" name="importe_manual" id="importe_manual" step="0.01" required>
                                </div>
                                <div class="col-3">
                                    Factura:<br />
                                    <input class="form-control form-control-sm" type="text" name="factura_manual" id="factura_manual" required>
                                </div>
                                <div class="col-4">
                                    Notas:<br />
                                    <input class="form-control form-control-sm" type="text" name="notas" id="notas" >
                                </div>
                                <div class="col-3 d-flex align-items-end">
                                    <button class="btn btn-success btn-block btn-sm" type="button" id="payment_btn_manual" onclick="payment_btn_save('manual')">
                                        Guardar Pago
                                    </button>
                                </div>
                            @else
                                <div class="col-12 pt-3">
                                    <div class="alert alert-warning"> No hay métodos de pago configurados</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </form>


            <br />
            <div id="payment_conte_table">

            </div>


    </div>
</div>
<script>
    $(document).ready(function() {

        $('#pagoForm').validate({
            rules: {
            },
            messages: {
            }
        });

    });

    function payment_btn_save(tipo) {

        if (!$('#pagoForm').valid()) {
            return false;
        }

        $('#pagoForm').find('#origen').val(tipo);
        if (tipo == 'manual') {
            $('#pagoForm').find('#importe').val($('#pagoForm').find('#importe_manual').val());
            $('#pagoForm').find('#factura').val($('#pagoForm').find('#factura_manual').val());

            $('#payment_btn_manual').prop('disabled', true).html(loading_sm);
        } else {
            $('#payment_btn_totem').prop('disabled', true).html(loading_sm);
        }

        var formData = $('#pagoForm').serialize();

        $.ajax({
            url: '{{ route("admin.pago-totems.store") }}',
            method: 'POST',
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: formData,
        }).done(function(data) {
            console.log(data);
            if(!data.success){
                Toast.fire({
                    icon: "error",
                    title: data.message ?? 'Error al realizar el pago'
                });
                return true;
            }
            if (tipo == 'totem') {
                Toast.fire({
                    icon: "success",
                    title: 'Solicitud de pago lanzada correctamente.'
                });
            } else if (tipo == 'manual') {
                Toast.fire({
                    icon: "success",
                    title: 'Pago guardado correctamente.'
                });
            }
            $('.folio_saldo_pendiente').html(loading_sm);
            showFolioDetail(folio_current);

            payment_table_load();
            $('#importe, #factura').val('');
            $('#importe_manual, #factura_manual, #notas').val('');
            //  $('#partner_btn_save_').prop('disabled', false).html('Hacer CheckIn');

        }).fail(function(error) {
            console.error('Error al guardar el checkin:', error);
        }).always(function() {

            $('#payment_btn_manual').prop('disabled', false).html('Guardar Pago');
            $('#payment_btn_totem').prop('disabled', false).html('Solicitar');

        });
    }


    function payment_table_load() {
        $('#payment_conte_table').html(loading1);
        $.ajax({
            url: "{{ url((request()->is('external*')?'external':'admin').'/call-manager/payment-table') }}" + "/" + folio_current + '?embed=1',
            method: 'GET',
            cache: false,
            success: function(response) {
                $('#payment_conte_table').html(response);
            },
            error: function(response) {

            },
        });
    }

    function payment_btn_delete(id) {

        $('#payment_btn_delete_'+id).prop('disabled', true).html(loading_sm);
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                method: 'POST',
                url: '{{ url("/admin/pago-totems/") }}/' + id,
                data: {
                    _method: 'DELETE'
                }
            })
            .done(function() {
                Toast.fire({
                    icon: "success",
                    title: 'Pago eliminado correctamente.'
                });
            }).always(function() {
                payment_table_load();
                $('#payment_btn_delete_'+id).prop('disabled', false).html('Eliminar Pago');

            })
    }
</script>
