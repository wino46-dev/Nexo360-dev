<?php
use App\Services\UtilService;
use App\Services\HotelApiService;

$paises = UtilService::countries_all();

$gendersList = [
    'M' => 'Masculino',
    'F' => 'Femenino',
    'O' => 'Otro',
];

$documentsList = UtilService::documents_list();

?>
<div class="row" id="sh360_ocr_form">
    <?php foreach($data_ocr['result_formated'] as $field =>$value){ ?>
    <div class="col-6 mb-2">
        <div><b>{{ $field }}</b></div>
        <div class="d-flex justify-content-between">

            <?php if($field == 'sexo'){ ?>
            {!! UtilService::drowDownList('sexo', $gendersList, $value, [
                'id' => 'field_' . $field,
                'name' => $field,
                'class' => 'form-control form-control-sm mt-1 py-0',
                'placeholder' => 'Seleccione...',
            ]) !!}
            <?php }elseif($field == 'pais_documento' || $field == 'nacionalidad'){ ?>
            <input type="text" id="field_{{ $field }}" name="{{ $field }}" readonly
                class="form-control form-control-sm mt-0 py-0" value="<?php echo $value; ?>" />
            <?php }elseif($field == 'tipo_documento'){ ?>
            {!! UtilService::drowDownList('tipo_documento', $documentsList, $value, [
                'id' => 'field_' . $field,
                'name' => $field,
                'class' => 'form-control form-control-sm mt-1 py-0',
                'placeholder' => 'Seleccione...',
            ]) !!}
            <?php }else{ ?>
            <input type="text" id="field_{{ $field }}" name="{{ $field }}"
                class="form-control form-control-sm mt-0 py-0" value="<?php echo $value; ?>" />
            <?php } ?>
            <button type="button" class="btn btn-secondary btn-xs btns-copiar-datos ml-1" title="Copiar Dato"
                onclick="h360_copiar_dato('{{ $field }}')">
                <i class="fa fa-copy" aria-hidden="true" style="height:10px"></i>
            </button>
        </div>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-12 text-right">
        <button type="button" class="btn btn-secondary btn-xs px-3" onclick="sh360_copiar_datos_todos()">
            Copiar Todos los
            Datos </button>
    </div>
</div>

<script>
    paises_sh = @json($paises);

    function h360_copiar_dato(field) {
        let checkin_id = '{{ $checkin_id }}';
        //console.log(checkin_id);
        let value = $('#sh360_ocr_form').find('#field_' + field).val();

        let type_input = 'text';
        let field_to = '';
        if (field == 'numero_identificacion') {
            field_to = 'documentNumber';
        }
        /*if (field == 'document_number') {
            field_to = 'documentSupportNumber';
        }*/

        if (field == 'nombre') {
            field_to = 'firstname';
        }
        /*if (field == 'apellidos') {
            field_to = 'lastname';
        }*/
        if (field == 'apellido1') {
            field_to = 'lastname';
        }

        if (field == 'apellido2') {
            field_to = 'lastname2';
        }

        if (field == 'fecha_nacimiento') {
            field_to = 'birthdate';
            type_input = 'date'
            value = convertirFecha(value);
        }

        if (field == 'direccion') {
            field_to = 'residenceStreet';
        }

        if (field == 'codigo_documento') {
            field_to = 'documentSupportNumber';
        }
        if (field == 'poblacion') {
            field_to = 'residenceCity';            
        }

        if (field == 'validez_documento') {
            field_to = 'documentExpeditionDate';
            type_input = 'date'
            value = convertirFecha(value);
        }

        if (field == 'tipo_documento') {
            field_to = 'documentType';
            type_input = 'select';

        }

        if (field == 'nacionalidad') {
            const country = paises_sh.find(item => item.code === value);
            console.log(country);
            field_to = 'nationality';
            value = country.id ?? '';
            type_input = 'select2';
        }

        if (field == 'pais_documento') {
            const country = paises_sh.find(item => item.code === value);
            
            field_to = 'documentCountryId';
            value = country.id ?? '';
            type_input = 'select2';

            $('#partnerContainer_' + checkin_id).find('select[name="countryId"]')
                .val(value)
                .trigger('change')
                .trigger('select2:select');                
        }

        if (field == 'sexo') {
            if (value == 'M') {
                value = 'male';
            } else if (value == 'F') {
                value = 'female';
            }
            field_to = 'gender';
            type_input = 'select'
        }
        if (field == 'codigo_postal') {
            field_to = 'zip';
        }

        if (type_input == 'select') {
            $('#partnerContainer_' + checkin_id).find('select[name="' + field_to + '"]').val(value);
        } else if (type_input == 'select2') {
            $('#partnerContainer_' + checkin_id).find('select[name="' + field_to + '"]').val(value).trigger('change');
        } else {
            $('#partnerContainer_' + checkin_id).find('input[name="' + field_to + '"]').val(value);
        }

        Toast.fire({
            icon: "success",
            title: 'Copiado'
        });

    }

    function sh360_copiar_datos_todos() {
        $('#sh360_ocr_form').find('input, select').each(function() {
            let inputName = $(this).attr('name');
            h360_copiar_dato(inputName);            
        });
    }

    function convertirFecha(formatoOriginal) {
        const [dia, mes, año] = formatoOriginal.split('/');
        const formatoNuevo = `${año}-${mes}-${dia}`;
        return formatoNuevo;
    }
</script>
