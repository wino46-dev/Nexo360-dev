@if(isset($ayudaStepTotem->pase_imagenes))
    <div class="mt-4">
        <h4 class="mb-0">Selección de Imágenes
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-imagenes-modal-lg">Ayuda</button>
        </h4>
    </div>
    <div id="modalImagenes" class="modal fade bd-imagenes-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="padding: 20px !important;">
                    {!! $ayudaStepTotem->pase_imagenes !!}
                </div>
            </div>
        </div>
    </div>

@else
    <h4>Selección de Imágenes</h4>
@endif

@include('external.callManager.imagesGallery')
