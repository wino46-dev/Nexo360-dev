<div class="row" id="page_llamada_container" style="display:none; cursor:pointer" onclick="page11_llamar()" >
    <div class="col-12 px-0" style="position:relative">
        
        <div class="page2_img_container">
            {{ $totem->imagen_pagina_llamada }}
        </div> 

        <div class="btn_call_container text-center">
            {!! $totem->pagina_llamada_texto_superior !!}
            <!-- <button type="button" class="btn btn-lg btn-outline-light rounded-0 btn_call mt-5" onclick="page11_llamar()">{!! $totem->pagina_llamada_texto_boton !!}</button> -->
            {!! $totem->pagina_llamada_texto_inferior !!}
        </div>

    </div>
</div>

<style>


</style>