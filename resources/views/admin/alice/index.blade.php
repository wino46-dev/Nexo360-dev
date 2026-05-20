<?php
use App\Services\UtilService;
use App\Services\HotelApiService;

$roomdo_paises = HotelApiService::countries();
$alice_paises = UtilService::countries_all();

foreach ($alice_paises as $ka => $ra) {
    $first = Arr::first($roomdo_paises, function ($value, int $key) use ($ra) {
        return $value['code'] == $ra['country_code'];
    });
    if ($first) {
        $alice_paises[$ka]['code_roomdo'] = $first['id'];
    }
}
$alice_paises = collect($alice_paises)->sortBy('country')->values()->all();
$countriesList = Arr::pluck($alice_paises, 'country', 'code');

?>

<script>
    alice_paises = @json($alice_paises);
    
    $(document).ready(function() {

        $('.select2_ajax').select2({
            dropdownParent: $('#alice_modal')
        });

        /*$('.select2_ajax').on('select2:select', function (e) {
            var data = e.params.data;
            console.log(data);
            const country = alice_paises.find(item => item.code === data.id);
            console.log(country.code_roomdo)

        });*/

    });
</script>
<div class="row">
    <div class="col-md-4 col-12">
        Idioma:<br />
        <select name="alice_language" id="alice_language" class="form-control form-control-sm">
            @foreach ($langs as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 col-12">
        Pais:<br />
        {!! UtilService::drowDownList('alice_country', $countriesList, 'ESP', [
            'id' => 'alice_country',
            'class' => 'form-control form-control-sm select2_ajax',
            'required' => 'true',
            'placeholder' => 'Seleccione...',
        ]) !!}
    </div>
    <div class="col-md-4 col-12">
        Tipo de Documento:<br />
        <select name="alice_document_type" id="alice_document_type" class="form-control form-control-sm">
            @foreach ($documents_type as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-4 col-12">
        <button type="button" class="btn btn-primary" onclick="getDocument_btn('front')">Solicitar parte
            delantera</button>
        <div id="alice_res_get_document_front">

        </div>
    </div>
    <div class="col-md-4 col-12">
        <button type="button" class="btn btn-primary" onclick="getDocument_btn('back')">Solicitar parte
            trasera</button>
        <div id="alice_res_get_document_back">

        </div>
    </div>
    <div class="col-md-4 col-12">

    </div>
</div>
<div class="alice_modal_resul">

</div>
<script>
    function getDocument_btn(side) {
        getDocument("{{ $checkin_id }}", side, '{{ $user_token }}', $('#alice_language').val(),
            $('#alice_country').val(), $('#alice_document_type').val());
    }

    alice_modal_status("{{ $checkin_id }}");


    function copiar_datos(conte, checkin_id, field) {

        let value = $('.' + conte).find('#field_' + checkin_id + '_' + field).val();
        console.log($('#field_' + checkin_id + '_' + field));

        let type_input = 'text';
        let field_to = '';
        if (field == 'id_number') {
            field_to = 'documentNumber';
        }
        if (field == 'document_number') {
            field_to = 'documentSupportNumber';
        }


        if (field == 'first_name') {
            field_to = 'firstname';
        }
        if (field == 'last_name') {
            field_to = 'lastname';
        }
        if (field == 'last_name_1') {
            field_to = 'lastname';
        }
        if (field == 'last_name_2') {
            field_to = 'lastname2';
        }
        if (field == 'birth_date') {
            field_to = 'birthdate';
        }
        if (field == 'issue_date') {
            field_to = 'documentExpeditionDate';
            type_input = 'date'
        }

        if (field == 'document_type') {
            field_to = 'documentType';
            type_input = 'select';

        }
        if (field == 'nationality') {
            const country = alice_paises.find(item => item.code === value);
            field_to = 'nationality';
            value = country.code_roomdo ?? '';
            type_input = 'select2';         
            $('#partnerContainer_' + checkin_id).find('select[name="documentCountryId"]').val(value).trigger('change');        
        }

        if (field == 'sex') {
            if (value == 'M') {
                value = 'male';
            } else if (value == 'F') {
                value = 'female';
            }
            field_to = 'gender';
            type_input = 'select'
        }

        console.log(checkin_id);
        console.log(field_to);
        console.log(value);

        if (type_input == 'select') {            
            $('#partnerContainer_' + checkin_id).find('select[name="' + field_to + '"]').val(value);
        }else if (type_input == 'select2') {            
            $('#partnerContainer_' + checkin_id).find('select[name="' + field_to + '"]').val(value).trigger('change');
        } else {
            $('#partnerContainer_' + checkin_id).find('input[name="' + field_to + '"]').val(value);
        }


        Toast.fire({
            icon: "success",
            title: 'Copiado'
        });
    }

    function copiar_datos_todos() {

        $('.btns-copiar-datos').trigger('click');
        Toast.fire({
            icon: "success",
            title: 'Se copiaron todos los datos'
        });
    }
</script>
