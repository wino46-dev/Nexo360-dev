<div class="row" id="page2_container" style="display:none">
    <div class="col-12  px-0" style="position:relative">
        <div class="page2_img_container">
            {{ $totem->imagen_pagina_2 }}
        </div>
        
        <div class="spinner_container text-center">
            {{ $spinner_call }}
        </div>
        
        <div style="position:absolute; top:0px; left:0px; right:0px">
            {!! $totem->texto_superior_pagina_2 !!}
        </div>
        <div style="position:absolute; top:0px; left:0px; right:0px">
            {!! $totem->texto_inferior_pagina_2 !!}
        </div>
        <div class="colgar_page2_container text-center">
            <!-- <button type="button" v-else class="btn btn-danger" onclick="page2_colgar()">
                COLGAR3
            </button>      -->
        </div>

        
    </div>
</div>