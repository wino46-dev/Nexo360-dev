<?php

?>
<div class="card">
    <div class="card-body">
        <form id="reserva_import" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-sm-4">
                    <label><b>Hotel (local)</b></label>
                    {!! UtilService::drowDownList('import_hotel_id', $establecimientos_locales, '3', [
                        'id' => 'import_hotel_id',
                        'class' => 'form-control form-control-sm select2',
                        'required' => 'true',
                        'placeholder' => 'Seleccione...',
                    ]) !!}

                </div>
                <div class="col-3">
                    <div class="mb-2"><b>Archivo</b> (permitidos: xls y csv)</div>
                    <input type="file" id="fileInput" name="file" />
                </div>
                <div class="col-2">
                    <b>PMS Origen:</b><br />
                    <select name="pms_origen" id="pms_origen" class="form-control form-control-sm">
                        @foreach ($pms_list_import as $pms)
                            <option value="{{ $pms }}">{{ $pms }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="buscar_btn" type="submit">
                        Importar Reservas
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {



        $("#reserva_import").on("submit", function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            let formData = new FormData();
            let file = $("#fileInput")[0].files[0];

            if (!file) {
                alert("Por favor selecciona un archivo.");
                return;
            }

            formData.append("file", file);
            formData.append("hotel_id", $('#import_hotel_id').val());
            formData.append("pms_origen", $('#pms_origen').val());

            // Determine current panel prefix (admin or external)
            @php($panelPrefix = (auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal()) ? 'external' : 'admin')

            $.ajax({
                url: '{{ url($panelPrefix . "/reservas/import") }}', // Ruta del script backend
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    $("#response").html(
                        "<p style='color:green;'>Archivo subido correctamente.</p>");
                },
                error: function() {
                    $("#response").html(
                        "<p style='color:red;'>Error al subir el archivo.</p>");
                }
            });
        });

    });
</script>
