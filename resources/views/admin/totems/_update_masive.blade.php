<br />
<div class="card">
    <div class="card-header py-1 px-2">
        <b>Actualización Masiva</b>
    </div>
    <div class="card-body py-1 px-2">
        
            @csrf
            <div class="row">
                <div class="col-md-3 ">
                    <div >Fuente de Imagenes</div>
                    <select class="form-control form-control-sm" id="mass_fuente_imagenes_pagina_1">
                        <option value="">Seleccione...</option>
                        @foreach (App\Models\Totem::FUENTE_IMAGENES_SELECT as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>                
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" id="send-selected" 
                        style="width: 90px"
                    class="btn btn-primary btn-sm mt-4">Actualizar</button>
                </div>
            </div>
        
    </div>
</div>
<script>
    $(document).ready(function() {
        
        $('#send-selected').on('click', function() {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).data('id'));
            });

            if (selectedIds.length > 0) {
                $('#send-selected').html(loading_sm);
                let mass_fuente_imagenes_pagina_1 = $('#mass_fuente_imagenes_pagina_1').val();
                if (mass_fuente_imagenes_pagina_1 == '') {
                    alert('Seleccione una fuente de imagenes');
                    $('#send-selected').html('Actualizar');
                    return;
                }

                $.ajax({
                    url: '{{ url('/admin/totems/update-mass') }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        fuente_imagenes_pagina_1: mass_fuente_imagenes_pagina_1
                    },
                    headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},        
                    
                    success: function(response, status, xhr) {
                        
                        $('#send-selected').html('Actualizar');
                        $('#datatable-Totem').DataTable().ajax.reload(null, false);
                    },
                    error: function() {
                        alert('Ocurrió un error al intentar actualizar');
                        $('#send-selected').html('Actualizar');
                    }
                });
            } else {
                alert('No se ha seleccionado ninguna fila.');
            }
        });

    });
</script>
