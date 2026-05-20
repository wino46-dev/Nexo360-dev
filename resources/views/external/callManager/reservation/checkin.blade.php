<?php
use App\Services\HotelApiService;
$relationshipList = HotelApiService::relationshipList();
if (!empty($establecimiento) && !empty($establecimiento->sociedad) && empty($establecimiento->sociedad->show_btns_capture_document)) {
    $establecimiento->sociedad->show_btns_capture_document = 'back';
}
function checkin_partner_status_class($status)
{
    if ($status == 'onboard') {
        return 'text-success border-success';
    }
}

?>
<table style="width:100%" class="table table-bordered table-striped table-hover table_partner">
    @foreach ($checkinPartners as $key => $partner)
        <tr id="partnerContainer_{{ $partner['id'] }}">
            <td>
                <form id="partner_form_{{ $key }}" class="partner_form">
                    <b>
                        <i class="fa fa-user" aria-hidden="true"></i> Información Huésped {{ $key + 1 }} <span
                            class="badge badge-secondary ml-2 {{ checkin_partner_status_class($partner['checkinPartnerState']) }}">
                            {{ $partner['checkinPartnerState'] }}</span>
                    </b>
                    <hr class="my-1" />
                    <input type="hidden" name="reservation_id" value="{{ $reservation_id }}" />
                    <input type="hidden" name="id" value="{{ $partner['id'] }}" />
                    <input type="hidden" name="action" value="" />
                    <div class="row" id="parentesco_conte_{{ $partner['id'] }}" style="display:none">
                        <div class="col-md-7 form-group">
                            <label>Huésped Parentesco :</label>
                            <select name="responsibleCheckinPartnerId"
                                class="form-control form-control-sm select_parentesco">
                                <option value="{{ $partner['responsibleCheckinPartnerId'] }}">
                                    {{ $partner['responsibleCheckinPartnerId'] }}</option>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label>Parentesco:</label>
                            {!! UtilService::drowDownList('relationship', $relationshipList, $partner['relationship'], [
                                'id' => 'relationship' . $key,
                                'class' => 'form-control form-control-sm select2',
                                //'required' => 'true',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="col-12">
                            <hr class="my-1" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Tipo Documento:</label>
                            {!! UtilService::drowDownList('documentType', $documentTypesList, $partner['documentType'], [
                                'id' => 'documentType' . $key,
                                'class' => 'form-control form-control-sm',
                                'required' => 'true',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>No. Documento:</label>
                            <input type="text" class="form-control" name="documentNumber"
                                value="{{ $partner['documentNumber'] }}" required>
                        </div>
                        <div class="col-md-4 form-group d-flex align-items-end">
                            @unless(request()->is('external*'))
                            <button type="button" id="partner_find_huesped_btn_{{ $partner['id'] }}"
                                class="btn btn-sm btn-info w-100"
                                onclick="client_search({{ $partner['id'] }},{{ $key }})">
                                Buscar Huésped</button>
                            @endunless
                        </div>
                    </div>
                    <hr class="my-1" />

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                onchange="parentesco_change()" value="{{ $partner['firstname'] }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Apellido:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                onchange="parentesco_change()" value="{{ $partner['lastname'] }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Segundo Apellido:</label>
                            <input type="text" class="form-control" id="lastname2" name="lastname2"
                                value="{{ $partner['lastname2'] }}">
                        </div>
                        <div class="col-md-8 form-group">
                            <label>Email:</label>
                            <button class="btn btn-secondary btn-sm py-0 mb-1" type="button"
                                onclick="checkin_copiar_reserva('{{ $partner['id'] }}','email')"
                                title="Copiar email de la reserva">
                                <i class="fa fa-copy" aria-hidden="true"></i>
                            </button>

                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $partner['email'] }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Mobile:</label>
                            <button class="btn btn-secondary btn-sm py-0 mb-1" type="button"
                                onclick="checkin_copiar_reserva('{{ $partner['id'] }}','mobile')"
                                title="Copiar Mobile de la reserva">
                                <i class="fa fa-copy" aria-hidden="true"></i>
                            </button>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                value="{{ $partner['mobile'] }}">
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 form-group">
                            <label>Fecha Expe. Docu:</label>
                            <input type="date" class="form-control" name="documentExpeditionDate"
                                value="{{ $partner['documentExpeditionDate'] }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>País Origen Docu:</label>
                            {!! UtilService::drowDownList('documentCountryId', $countriesList, $partner['documentCountryId'], [
                                'id' => 'documentCountryId' . $key,
                                'class' => 'form-control form-control-sm select2',
                                'required' => 'true',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>No. Docu Soporte:</label>
                            <input type="text" class="form-control" name="documentSupportNumber" required
                                value="{{ $partner['documentSupportNumber'] }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Genero:</label>
                            {!! UtilService::drowDownList('gender', $gendersList, $partner['gender'], [
                                'id' => 'gender' . $key,
                                'class' => 'form-control form-control-sm',
                                'required' => 'true',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Fecha Nacimiento:</label>
                            <input type="date" class="form-control"
                                onchange="birthdate_change('{{ $partner['id'] }}')" name="birthdate"
                                value="{{ $partner['birthdate'] }}" required>
                        </div>
                    </div>
                    <div class="row">


                        <div class="col-md-6 form-group">
                            <label>Nacionalidad:</label>
                            {!! UtilService::drowDownList('nationality', $countriesList, $partner['nationality'], [
                                'id' => 'nationality' . $key,
                                'class' => 'form-control form-control-sm select2',
                                'required' => 'true',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Pais Residencia:</label>
                            {!! UtilService::drowDownList('countryId', $countriesList, $partner['countryId'], [
                                'id' => 'countryId' . $key,
                                'class' => 'form-control form-control-sm select2',
                                'required' => 'true',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Provincia Residencia:</label>
                            {!! UtilService::drowDownList('countryState', $partner['countryStateList'], $partner['countryState'], [
                                'id' => 'countryState' . $key,
                                'class' => 'form-control form-control-sm select2',
                            ]) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Ciudad Residencia:</label>
                            <input type="text" class="form-control" name="residenceCity"
                                value="{{ $partner['residenceCity'] }}" required>
                        </div>
                        <div class="col-md-8 form-group">
                            <label>Dirección Residencia:</label>
                            <input type="text" class="form-control" name="residenceStreet"
                                value="{{ $partner['residenceStreet'] }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Código Postal:</label>
                            <input type="text" class="form-control" name="zip" required
                                value="{{ $partner['zip'] }}">
                        </div>

                        <input type="hidden" name="genderName" value="">
                        <input type="hidden" name="documentTypeName" value="">
                        <input type="hidden" name="nationalityName" value="">
                        <input type="hidden" name="countryName" value="">
                        <input type="hidden" name="countryStateName" value="">

                    </div>
                </form>

            </td>
            <td class="text-center"style="width:150px; position:relative">
                <!-- <div>
                    <button class="btn btn-success btn-sm mt-1" type="button" style="width:100%" onclick="alice_modal({{ $partner['id'] }})">
                        Validación Alice
                    </button>
                </div> -->
                <div>
                    <div id="foto_documento_3_{{ $partner['id'] }}" class="mt-2">
                        <img src="/img/no_docu_1.png" class="img-fluid" />
                    </div>

@unless(request()->is('external*'))
                    <button class="btn btn-danger btn-sm mt-1" type="button" style="width:100%"
                        onclick="foto_documento({{ $partner['id'] }}, 3, {{ $key }})">
                        Solicitar Parte delantera
                    </button>
                    <?php if($establecimiento->sociedad->show_btns_capture_document == 'back' || $establecimiento->sociedad->show_btns_capture_document == 'both'){ ?>

                    <button class="btn btn-success btn-sm mt-1 btn_tomar_foto_3" id="btn_tomar_foto_3" type="button"
                        style="width:100%; display:none"
                        onclick="foto_documento({{ $partner['id'] }}, 8, {{ $key }})">
                        Tomar Foto
                    </button>

                    <?php } ?>
                    <hr class="my-2" />
                    <div id="foto_documento_6_{{ $partner['id'] }}" class="mt-2">
                        <img src="/img/no_docu_2.png" class="img-fluid " />
                    </div>
                    <button class="btn btn-warning btn-sm mt-1" type="button" style="width:100%"
                        onclick="foto_documento({{ $partner['id'] }}, 6, {{ $key }})">
                        Solicitar Parte Trasera
                    </button>

                    <?php if($establecimiento->sociedad->show_btns_capture_document == 'back' || $establecimiento->sociedad->show_btns_capture_document == 'both'){ ?>
                    <button class="btn btn-success btn-sm mt-1 btn_tomar_foto_6" type="button" id="btn_tomar_foto_6"
                        style="width:100%; display:none"
                        onclick="foto_documento({{ $partner['id'] }}, 8, {{ $key }})">
                        Tomar Foto
                    </button>
                    <?php } ?>
                    <button class="btn btn-success btn-sm mt-2" type="button" style="width:100%"
                        onclick="sh360_modal({{ $partner['id'] }}, {{ $key }})">
                        Obtener datos OCR
                    </button>
                    <hr class="my-2" />
                    <div id="foto_firma_{{ $partner['id'] }}" class="mt-2">
                        <img src="/img/no_firma.jpg" class="img-fluid border rounded" />
                    </div>
                    <!-- <button class="btn btn-info btn-sm mt-1" type="button" style="width:100%" onclick="foto_firma({{ $partner['id'] }}, {{ $key }})">
                        Solicitar Firma
                    </button> -->
                    <button class="btn btn-info btn-sm mt-1" type="button" style="width:100%"
                        id="partner_signature_capture_btn_{{ $key }}"
                        onclick="partner_signature_capture({{ $partner['id'] }}, {{ $key }})">
                        Solicitar Firmar
                    </button>

                    <button class="btn btn-success btn-sm mt-3" type="button" style="width:100%"
                        id="partner_checkin_save_btn_{{ $key }}"
                        onclick="partner_checkin_save({{ $partner['id'] }}, {{ $key }})">
                        Check-In Parcial
                    </button>
@endunless
                    <?php if($partner['checkin_ok']){ ?>
                    <button class="btn btn-info btn-sm mt-3" type="button" style="width:100%"
                        id="partner_checkin_show_btn_{{ $key }}"
                        onclick="reservation_resumen({{ $partner['reservationId'] }}, {{ $partner['id'] }})">
                        Ver Check-In
                    </button>
                    <?php } ?>
                </div>



            </td>
        </tr>
        <tr>
            <td colspan="2" style="height:40px"> </td>
        </tr>
    @endforeach
</table>
@unless(request()->is('external*'))
<div class="text-right">
    <button class="btn btn-success btn-sm mt-1" type="button" style="width:50%"
        onclick="checkin_confirm(this, 'validate_pago')">
        FINALIZAR PROCESO
    </button>
</div>
@endunless
@include('external.callManager.reservation.resumenModal')
{{-- @include('external.alice.modal', ['control_actual' => $control_actual]) --}}
@unless(request()->is('external*'))
@include('external.sh360-ocr.modal', ['control_actual' => $control_actual])
@endunless

<script>
    setTimeout(function() {
        $('#grabarTarjetaForm').find('#room_no').val("{{ $reservation['roomName'] }}");
    }, 100);

    @if(request()->is('external*'))
    // En External: modo solo lectura y sin acciones
    $(function(){
        var $c = $('#checkin_partners');
        $c.find('input, select, textarea').attr('readonly', true).prop('disabled', true);
        // Ocultar botones de acciones (capturas, OCR, firma, parciales)
        $('[id^="partner_signature_capture_btn_"], [id^="partner_checkin_save_btn_"]').closest('button').remove();
        $('button[onclick^="foto_documento("], button[onclick^="sh360_modal("]').remove();
        // Ocultar botón FINALIZAR PROCESO si hubiera llegado
        $('button').filter(function(){ return this.textContent && this.textContent.indexOf('FINALIZAR PROCESO')>-1; }).remove();
    });
    // Neutralizar funciones de acción en External para evitar llamadas a rutas external
    window.foto_documento = function(){};
    window.foto_documento_load = function(){};
    window.foto_firma = function(){};
    window.foto_firma_load = function(){};
    window.partner_signature_capture = function(){};
    window.partner_checkin_save = function(){};
    @endif

    function checkin_confirm($this, tipo) {
        // validaciones


        var dataSend = {
            partners: {}
        };
        $($this).html(loading_sm).prop('disabled', true);
        let error_form = 0;
        $(".partner_form").each(function(index, form) {
            let formularioValido = $(form).valid();

            if (!formularioValido) {
                error_form++;
            }

            campos = $(form).serializeArray();

            jsonData = {};
            $.each(campos, function() {
                jsonData[this.name] = this.value;
            });

            setValuesSelect2Form(form);

            dataSend.partners[index] = jsonData;

        });

        if (error_form > 0) {
            $($this).html('REALIZAR CHECKIN').prop('disabled', false);
            return false;
        }
        if (tipo == 'validate_pago') {
            $.ajax({
                url: '{{ url((request()->is('external*')?'external':'external').'/reservation-api/checkin') }}?opc=validate_pago&embed=1',
                method: 'POST',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                dataType: 'json',
                data: dataSend,
            }).done(function(response) {

                checkin_confirm($this, 'validate');


            }).fail(function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON['error']) {
                    Toast.fire({
                        icon: "error",
                        title: error.responseJSON['error']
                    });
                } else if (error.responseJSON && error.responseJSON['confirm']) {
                    Swal.fire({
                        html: error.responseJSON['confirm'] + ",<br /><br />Desea continuar?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Continuar",
                        cancelButtonText: `Cancelar`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            checkin_confirm($this, 'validate');
                        }
                    });

                } else {
                    alert('Error al finalizar el checkin')
                    console.error('Error al guardar el checkin:', error);
                }
                $($this).html('REALIZAR CHECKIN').prop('disabled', false);

            });
        }
        if (tipo == 'validate') {
            $.ajax({
                url: '{{ url((request()->is('external*')?'external':'external').'/reservation-api/checkin') }}?opc=validate&embed=1',
                method: 'POST',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                dataType: 'json',
                data: dataSend,
            }).done(function(response) {

                checkin_confirm($this, 'save');


            }).fail(function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON['error']) {
                    Toast.fire({
                        icon: "error",
                        title: error.responseJSON['error']
                    });
                } else if (error.responseJSON && error.responseJSON['confirm']) {
                    Swal.fire({
                        html: error.responseJSON['confirm'] + ",<br /><br />Desea continuar?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Continuar",
                        cancelButtonText: `Cancelar`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            checkin_confirm($this, 'save');
                        }
                    });

                } else {
                    alert('Error al finalizar el checkin')
                    console.error('Error al guardar el checkin:', error);
                }
                $($this).html('REALIZAR CHECKIN').prop('disabled', false);

            });

        } else if (tipo == 'save') {

            $.ajax({
                url: '{{ url((request()->is('external*')?'external':'external').'/reservation-api/checkin') }}?opc=save&embed=1',
                method: 'POST',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                dataType: 'json',
                data: dataSend,
            }).done(function(response) {

                $($this).html('REALIZAR CHECKIN').prop('disabled', false);

                if (response['success']) {
                    Toast.fire({
                        icon: "success",
                        title: response['success']
                    });
                    reservation_resumen(response['id']);
                }

            }).fail(function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON['error']) {
                    Toast.fire({
                        icon: "error",
                        title: error.responseJSON['error']
                    });
                } else {
                    alert('Error al finalizar el checkin')
                    console.error('Error al guardar el checkin:', error);
                }
                $($this).html('REALIZAR CHECKIN').prop('disabled', false);

            });
        }

    }



    var foto_view_current = '';

    function foto_documento_ampliar(id, tipo, obj) {

        if (foto_view_current == id + '_' + tipo) {
            if ($("#photoView").is(":visible")) {
                $('#photoView').hide();
                $('#pagoFormContainer').show();
                $('#grabarTarjetaContainer1').show();
                return true;
            }
        }

        foto_view_current = id + '_' + tipo;

        let top = $('#partnerContainer_' + id).show().position().top;
        let width = $('#pagoFormContainer').show().width() + 1;

        $('#photoView').show();
        $('#pagoFormContainer').hide();
        $('#grabarTarjetaContainer1').hide();

        $('#photoView').css({
            "top": (top + 10) + 'px',
            "width": (width + 1) + 'px',
            "min-height": "150px"
        });

        $('#photoView').find('.card-body').html('<img src="' + $(obj).attr('src') +
            '" class="img-fluid border rounded" />');

    }

    function photoViewClose() {
        $('#photoView').hide();
        $('#pagoFormContainer').show();
        $('#grabarTarjetaContainer').show()
    }


    function foto_documento_load(id, tipo) {

        let data = {
            'tipo_evento_id': tipo,
            'objeto': id,
            'destino': 'small'
        }

        $.ajax({
            url: "{{ route('external.call-manager.documentView') }}",
            method: 'POST',
            //dataType: 'JSON',
            //  contentType: false,
            cache: false,
            // processData: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: data,
            success: function(response) {
                $('#foto_documento_' + tipo + '_' + id).html(response);
            },
            error: function(response) {

            },
        });
    }

    function foto_firma_load(id) {

        let data = {
            'objeto': id,
            'tipo_evento_id': 4,
            'destino': 'small'
        }

        $.ajax({
            url: "{{ route('external.call-manager.signView') }}",
            method: 'POST',
            //dataType: 'JSON',
            //  contentType: false,
            cache: false,
            // processData: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: data,
            success: function(response) {
                $('#foto_firma_' + id).html(response);
            },
            error: function(response) {

            },
        });

    }




    function foto_firma(id, form_id) {

        evento_totem(id, 4, form_id);
    }

    function foto_documento(id, tipo, form_id) {
        evento_totem(id, tipo, form_id)
    }

    function evento_totem(id, tipo, form_id) {

        let partner_name = $('#partner_form_' + form_id).find('input[name="firstname"]').val() + ' ' + $(
            '#partner_form_' + form_id).find('input[name="lastname"]').val();

        let data = {
            'receptor_id': '{{ $control_actual->receptor_id }}',
            'emisor_id': '{{ $control_actual->emisor_id }}',
            'sesion_id': '{{ $control_actual->id }}',
            'tipo_evento_id': tipo,
            'canal_transmision': 'Home Inferior',
            'pms': '{{ $establecimiento->api_pms }}',
            'objeto': id,
            'mensaje': partner_name
        }

        $.ajax({
            url: "{{ route('external.evento-home-totems.store') }}",
            method: 'POST',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: data,
            success: function(response) {

                if (response['success']) {
                    Toast.fire({
                        icon: "success",
                        title: response['success']
                    });
                    if (tipo == 3) {
                        $('#partnerContainer_' + id).find('.btn_tomar_foto_3').show();
                        $('#partnerContainer_' + id).find('.btn_tomar_foto_6').hide();
                    }
                    if (tipo == 6) {
                        $('#partnerContainer_' + id).find('.btn_tomar_foto_3').hide();
                        $('#partnerContainer_' + id).find('.btn_tomar_foto_6').show();
                    }
                    if (tipo == 8) {
                        $('#partnerContainer_' + id).find('.btn_tomar_foto_3').hide();
                        $('#partnerContainer_' + id).find('.btn_tomar_foto_6').hide();
                    }
                }
                if (response['error']) {
                    Toast.fire({
                        icon: "success",
                        title: response['error']['errorInfo']
                    });
                }

            },
            error: function(response) {

            },
        });
    }


    $('.table_partner .select2').select2();

    @foreach ($checkinPartners as $key => $partner)

        $('#countryId{{ $key }}').on('select2:select', function(e) {
            let value_select = $('#countryId{{ $key }}').select2('val')
            console.log('value_select', value_select)
            countries_states(value_select, '#countryState{{ $key }}')
        });
        @if (empty($partner['nationality']))
            $('#nationality{{ $key }}').val(null).trigger('change');
        @endif
        @if (empty($partner['countryId']))

            $('#countryId{{ $key }}').val(null).trigger('change');
        @endif

        $(document).ready(function() {
            $('#partner_form_{{ $key }}').validate({
                rules: {

                },
                messages: {

                },
                submitHandler: function(form) {
                    console.log(form)
                    var formData = $(form).serialize();

                    let partner_form_action = $(form).find('input[name="action"]').val();


                    if (partner_form_action == 'signature_capture') {
                        $('#partner_signature_capture_btn_{{ $key }}').prop('disabled',
                            true).html(
                            loading_sm);
                    }
                    if (partner_form_action == 'checkin_save') {
                        $('#partner_checkin_save_btn_{{ $key }}').prop('disabled', true)
                            .html(
                                loading_sm);
                    }
                    $.ajax({
                        url: '{{ url((request()->is('external*')?'external':'external').'/reservation-api/checkin-partner') }}?embed=1',
                        method: 'POST',
                        data: formData,
                    }).done(function(data) {
                        console.log(data);
                        if (partner_form_action == 'signature_capture') {
                            evento_totem({{ $partner['id'] }}, 4, {{ $key }});
                            Toast.fire({
                                icon: "success",
                                title: data.success
                            });
                            $('#partner_signature_capture_btn_{{ $key }}').prop(
                                    'disabled', false)
                                .html('Solicitar Firmar');
                        }
                        if (partner_form_action == 'checkin_save') {
                            Toast.fire({
                                icon: "success",
                                title: 'Checkin parcial creado'
                            });
                            $('#partner_checkin_save_btn_{{ $key }}').prop(
                                    'disabled', false)
                                .html('Checkin Parcial');
                            reservation_resumen(data.reservation_id, data
                                .checkin_partner_id);
                            // Recalcular costes de la reserva (si la sección fija está presente)
                            try {
                                if (typeof window.load_fixed_costs === 'function' && data && data.reservation_id) {
                                    console.log('[EXTERNAL] Refrescando costes tras checkin parcial para', data.reservation_id);
                                    window.load_fixed_costs(data.reservation_id);
                                }
                            } catch (e) { /* no-op */ }
                        }

                    }).fail(function(error) {
                        console.log(error.responseJSON.error);

                        if (partner_form_action == 'signature_capture') {
                            console.error('Error al solicitar la firma:', error);

                            Toast.fire({
                                icon: "error",
                                title: 'Error al solicitar la firma',
                                toast: true,
                                showConfirmButton: false
                            });

                            $('#partner_signature_capture_btn_{{ $key }}').prop(
                                    'disabled', false)
                                .html('Solicitar Firmar');
                        }
                        if (partner_form_action == 'checkin_save') {
                            console.error('Error al guardar el checkin:', error);

                            Toast.fire({
                                icon: "error",
                                title: 'Error al guardar el checkin independiente',
                                toast: true,
                                showConfirmButton: false
                            });

                            $('#partner_checkin_save_btn_{{ $key }}').prop(
                                    'disabled', false)
                                .html('Checkin Parcial');


                        }
                        if (error.responseJSON.error) {
                            Toast.fire({
                                icon: "error",
                                title: error.responseJSON.error,
                                toast: true,
                                showConfirmButton: false
                            });
                        }
                    });

                }
            });
        });
        foto_documento_load({{ $partner['id'] }}, 3);
        foto_documento_load({{ $partner['id'] }}, 6);
        foto_firma_load({{ $partner['id'] }});
    @endforeach


    function partner_signature_capture(partner, form_key) {

        $('#partner_form_' + form_key).find('input[name="action"]').val('signature_capture');
        setValuesSelect2Form('#partner_form_' + form_key);
        $('#partner_form_' + form_key).submit();
    }

    function partner_checkin_save(partner, form_key) {
        $('#partner_form_' + form_key).find('input[name="action"]').val('checkin_save');
        setValuesSelect2Form('#partner_form_' + form_key);
        $('#partner_form_' + form_key).submit();
    }

    function partner_checkin_show(partner, form_key) {
        console.log(partner);
        console.log(form_key);
        reservation_resumen
    }

    function countries_states(country_id, select_to) {
        let select_container = $(select_to);
        if (!country_id) {
            select_container.empty();
            select_container.val(null).trigger('change');
            return false;
        }

        $.ajax({
            url: '{{ url((request()->is('external*')?'external':'external').'/reservation-api/countries-states') }}/' + country_id + '?embed=1',
            method: 'GET',
            dataType: 'json'
        }).done(function(data) {
            select_container.empty();
            $.each(data, function(i, item) {
                var option = new Option(item.name, item.id, true, true);
                select_container.append(option).trigger('change');
            });
            select_container.val(null).trigger('change');

        }).fail(function(error) {
            console.error('Error al obtener los estados:', error);
        });

    }

    function setValuesSelect2Form(form) {
        $(form).find('[name="genderName"]').val($(form).find('[name="gender"]').find('option:selected').text());
        $(form).find('[name="documentTypeName"]').val($(form).find('[name="documentType"]').find('option:selected')
            .text());
        $(form).find('[name="nationalityName"]').val($(form).find('[name="nationality"]').find('option:selected')
            .text());
        $(form).find('[name="countryName"]').val($(form).find('[name="countryId"]').find('option:selected').text());
        $(form).find('[name="countryStateName"]').val($(form).find('[name="countryState"]').find('option:selected')
            .text());
    }

    function checkin_copiar_reserva(parner_id, type) {

        if (type == 'email') {
            let email = $('#folio_detail_email').html();
            $('#partnerContainer_' + parner_id).find('[name="email"]').val(email)
        }
        if (type == 'mobile') {
            let mobile = $('#folio_detail_mobile').html();
            mobile = mobile.replace(/\s+/g, '');
            $('#partnerContainer_' + parner_id).find('[name="mobile"]').val(mobile)
        }

    }

    function birthdate_change(partner_id) {
        let birthdate = $('#partnerContainer_' + partner_id).find('[name="birthdate"]').val()
        const hoy = new Date();
        const nacimiento = new Date(birthdate);
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }

        if (edad < 18) {
            $('#parentesco_conte_' + partner_id).show();
        } else {
            $('#parentesco_conte_' + partner_id).hide();
        }
        parentesco_change();
        //return edad;
    }

    function parentesco_change() {
        let list_parent = [];
        $(".partner_form").each(function(index, form) {
            if (!$('#parentesco_conte_' + $(form).find('[name="id"]').val()).is(':visible')) {
                if ($(form).find('[name="firstname"]').val() || $(form).find('[name="lastname"]').val()) {
                    list_parent.push({
                        'partner_id': $(form).find('[name="id"]').val(),
                        'nombre': $(form).find('[name="firstname"]').val() + ' ' + $(form).find(
                            '[name="lastname"]').val()
                    })
                }
            }
        });

        $(".select_parentesco").each(function() {
            let selectedValue = $(this).val();

            $(this).empty();
            $(this).append('<option value="">Seleccione una opción</option>');
            $.each(list_parent, function(key, value) {
                $(this).append(`<option value="${value.partner_id}">${value.nombre}</option>`);
            }.bind(this));

            if (selectedValue && $(this).find(`option[value="${selectedValue}"]`).length) {
                $(this).val(selectedValue);
            }
        });

        console.log(list_parent);
    }
    @foreach ($checkinPartners as $key => $partner)
        birthdate_change({{ $partner['id'] }});
    @endforeach

    function huesped_relacion_load(reservation_id) {
        $.ajax({
            url: '{{ url('external/call-manager/huesped-relacion') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            data: {
                reservation_id: reservation_id
            },
        }).done(function(data) {
            console.log(data);

        }).fail(function(error) {
            console.error('Error cargando relación de huésped', error);
        });
    }

    function client_search(partner_id, key) {
        let documentType = $('#partnerContainer_' + partner_id).find('[name="documentType"]').val()
        let documentNumber = $('#partnerContainer_' + partner_id).find('[name="documentNumber"]').val()

        $('#partner_find_huesped_btn_' + partner_id).html(loading_sm).prop('disabled', true);

        $.ajax({
            url: "{{ url('external/call-manager/huesped-info') }}",
            method: 'POST',
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            data: {
                'document_type': documentType,
                'document_number': documentNumber
            },
            success: function(response) {
                //console.log(response);
                Toast.fire({
                    icon: "success",
                    title: 'Huésped Encontrado',
                    toast: true,
                    showConfirmButton: false
                });
                client_search_set(partner_id, response.data, key)

                $('#partner_find_huesped_btn_' + partner_id).html('Buscar Huésped').prop('disabled', false);
            },
            error: function(response) {
                Toast.fire({
                    icon: "error",
                    title: 'Huésped NO ENCONTRADO',
                    toast: true,
                    showConfirmButton: false
                });
                $('#partner_find_huesped_btn_' + partner_id).html('Buscar Huésped').prop('disabled', false);
            },
        });
    }

    function client_search_set(partner_id, data, key) {
        console.log(partner_id);
        console.log(data);

        const lastName  = data.lastname || data.lastname1 || data.surname || '';
        const lastName2 = data.lastname2 || data.lastname_second || '';
        $('#partnerContainer_' + partner_id).find('[name="firstname"]').val(text_clean(data.firstname))
        $('#partnerContainer_' + partner_id).find('[name="lastname"]').val(text_clean(lastName))
        $('#partnerContainer_' + partner_id).find('[name="lastname2"]').val(text_clean(lastName2))
        $('#partnerContainer_' + partner_id).find('[name="email"]').val(text_clean(data.email))
        $('#partnerContainer_' + partner_id).find('[name="mobile"]').val(text_clean(data.mobile))
        $('#partnerContainer_' + partner_id).find('[name="documentSupportNumber"]').val(text_clean(data
            .documentSupportNumber))
        $('#partnerContainer_' + partner_id).find('[name="gender"]').val(text_clean(data.gender))
        $('#partnerContainer_' + partner_id).find('[name="residenceStreet"]').val(text_clean(data.residenceStreet))
        $('#partnerContainer_' + partner_id).find('[name="residenceCity"]').val(text_clean(data.residenceCity))
        $('#partnerContainer_' + partner_id).find('[name="zip"]').val(text_clean(data.zip))

        const documentExpeditionDate = isoDatePart(data.documentExpeditionDate);
        $('#partnerContainer_' + partner_id).find('[name="documentExpeditionDate"]').val(documentExpeditionDate)

        const birthdate = isoDatePart(data.birthdate);
        $('#partnerContainer_' + partner_id).find('[name="birthdate"]').val(birthdate)

        $('#partnerContainer_' + partner_id).find('[name="documentCountryId"]').val(data.documentCountryId).trigger(
            "change")
        $('#partnerContainer_' + partner_id).find('[name="nationality"]').val(data.nationality).trigger("change")
        $('#partnerContainer_' + partner_id).find('[name="countryId"]').val(data.countryId).trigger("change")

        countries_states(data.countryId, '#countryState' + key)



    }

    function text_clean(texto) {
        if (typeof texto === 'string') {
            return texto.trim().replace(/\s+/g, ' ');
        }
        return '';
    }
    function isoDatePart(val){
        if (!val) return '';
        if (typeof val !== 'string') return '';
        if (val.includes('T')) return val.split('T')[0];
        if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return val;
        return '';
    }
</script>
<style>
    .table_partner .form-control {
        height: 30px
    }

    .table_partner label {
        margin-bottom: 0px;
    }

    .table_partner .form-group {
        margin-bottom: 5px;
    }
</style>
