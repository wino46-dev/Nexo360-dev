
<?php 
use App\Services\HotelApiService;
$documentTypesList = Arr::pluck(HotelApiService::documentTypes(), 'documentType', 'id');

$gendersList = [
            'M' => 'Masculino',
            'F' => 'Femenino',
            'O' => 'Otro',
        ];  
foreach($report['documents'] as $doc){
?>
<hr />
<div class="row">

    <div class="col-6 front_conte">
        <?php if(isset($doc['sides']['front'])){?>
        <div class="text-center">
            <b>{{ mb_strtoupper($doc['meta']['type']) }} - {{ $doc['meta']['issuing_country'] }} - Front</b>
        </div>
        <img src="<?php echo $doc['sides']['front']['base64Image']; ?>" alt="Imagen" class="img-fluid rounded">

        <div class="row mt-2">
            <?php foreach($doc['sides']['front']['fields'] as $field){ ?>
            <div class="col-6 ">
                <div class="border-bottom pb-1">
                    <div class="d-flex justify-content-between mt-1">
                        <?php echo $field['name']; ?>
                        <button type="button" class="btn btn-secondary btn-xs btns-copiar-datos" title="Copiar Dato"
                            onclick="copiar_datos('front_conte',{{ $checkin_id }}, '{{ $field['name'] }}')">
                            <i class="fa fa-list-alt" aria-hidden="true" style="height:10px"></i>
                        </button>
                    </div>
                    <?php if($field['name'] == 'sex'){ ?>
                    {!! UtilService::drowDownList('gender', $gendersList, $field['value'], [
                        'id' => 'field_' . $checkin_id . '_' . $field['name'],
                        'class' => 'form-control mt-1 py-0',
                        'placeholder' => 'Seleccione...',
                    ]) !!}
                    <?php }else{ ?>
                    <input type="text" id="field_{{ $checkin_id }}_{{ $field['name'] }}"
                        class="form-control mt-1 py-0" value="<?php echo $field['value']; ?>" />
                    <?php }?>
                </div>
            </div>
            <?php } ?>
        </div>

        <?php }?>
    </div>
    <div class="col-6 back_conte">
        <?php if(isset($doc['sides']['back'])){?>
        <div class="text-center">
            <b>{{ mb_strtoupper($doc['meta']['type']) }} - {{ $doc['meta']['issuing_country'] }} - Back</b>
        </div>
        <img src="<?php echo $doc['sides']['back']['base64Image']; ?>" alt="Imagen" class="img-fluid rounded">

        <div class="row mt-2">
            <?php foreach($doc['sides']['back']['fields'] as $field){ ?>
            <div class="col-6 ">
                <div class="border-bottom pb-1">
                    <div class="d-flex justify-content-between mt-1">
                        <?php echo $field['name']; ?>
                        <button type="button" class="btn btn-secondary btn-xs btns-copiar-datos" title="Copiar Dato"
                            onclick="copiar_datos('back_conte',{{ $checkin_id }}, '{{ $field['name'] }}')">
                            <i class="fa fa-list-alt" aria-hidden="true" style="height:10px"></i>
                        </button>
                    </div>
                    <?php if($field['name'] == 'sex'){ ?>
                    {!! UtilService::drowDownList('gender', $gendersList, $field['value'], [
                        'id' => 'field_' . $checkin_id . '_' . $field['name'],
                        'class' => 'form-control mt-1 py-0',
                        'placeholder' => 'Seleccione...',
                    ]) !!}
                    <?php }elseif($field['name'] == 'nationality'){ ?>
                        <input type="text" id="field_{{ $checkin_id }}_{{ $field['name'] }}" readonly
                            class="form-control mt-1" value="<?php echo $field['value']; ?>" />
                            
                    <?php }elseif($field['name'] == 'document_type'){
                        if($field['value'] == 'ID'){
                          $field['value'] = 3;
                        }
                      ?>
                    {!! UtilService::drowDownList('documentType', $documentTypesList, $field['value'], [
                        'id' => 'field_' . $checkin_id . '_' . $field['name'],
                        'class' => 'form-control mt-1 py-0',
                        'required' => 'true',
                        'placeholder' => 'Seleccione...',
                    ]) !!}
                    <?php }else{ ?>
                        <input type="text" id="field_{{ $checkin_id }}_{{ $field['name'] }}"
                            class="form-control mt-1" value="<?php echo $field['value']; ?>" />
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>

        <?php }?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <button type="button" class="btn btn-secondary btn-xs px-3" onclick="copiar_datos_todos()"> Copiar Todos los
            Datos </button>
    </div>
</div>
<?php 
} 
?>
