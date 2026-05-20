<div class="row" id="page1_container" style="display:none; cursor:pointer" onclick="page1_llamar()" >
    <div class="col-12 px-0" style="position:relative">

        <div class=" divcarousel" id="page1_divcarousel" >
            @if(isset($imagenes_pagina_inicial))
                <div id="carouselExampleIndicators1" class="carousel slide" data-ride="carousel"  data-interval="3000">

                    <ol class="carousel-indicators">
                        @foreach($imagenes_pagina_inicial as $key => $media)
                            @if($key == 0)
                                <li  style="display: none" data-target="#carouselExampleIndicators1" data-slide-to="{{$key}}" class="active"></li>
                            @else
                                <li style="display: none" data-target="#carouselExampleIndicators1" data-slide-to="{{$key}}" ></li>
                            @endif
                        @endforeach
                    </ol>


                    <div class="carousel-inner">
                        @foreach($imagenes_pagina_inicial as $key => $media)
                            @if($key == 0)
                                <div class="carousel-item active">
                                    <img id="21" class="d-block w-100 page1-hero-img" src="{{ $media->getUrl() }}" alt="{{$key}} slide">
                                </div>
                            @else
                                <div class="carousel-item">
                                    <img id="21" class="d-block w-100 page1-hero-img" src="{{ $media->getUrl() }}" alt="{{$key}} slide">
                                </div>
                            @endif

                        @endforeach
                    </div>
                    <!-- <a class="carousel-control-prev" href="#carouselExampleIndicators1" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators1" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a> -->
                </div>
            @endif
        </div>

        <div class="btn_call_container text-center">
            {!! $totem->texto_superior_pagina_1 !!}
            <!-- <button type="button" class="btn btn-lg btn-outline-light rounded-0 btn_call mt-5" onclick="page1_llamar()">START</button> -->
        </div>

    </div>
</div>

<style>
/* Página 1: hacer que las imágenes sean completamente fluidas sin recortarse en tablet/móvil */
.page1-hero-img {
    width: 100%;
    height: auto; /* evita recorte vertical */
    object-fit: contain; /* mantiene proporción sin cortar */
}

/* Asegurar que el contenedor no oculta parte inferior en tablet por la barra */
@media (min-width: 768px) and (max-width: 1024px) {
    #page1_container {
        padding-bottom: 64px; /* espacio para breadcrumbs de tablet si se mostraran */
    }
}

/* (Eliminado ajuste de pointer-events para no interferir con otras páginas) */
</style>
