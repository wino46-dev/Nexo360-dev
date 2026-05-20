<div class="card divcarousel" id="divcarousel" style="border:none;margin-top: 20px;">
    @if(isset($imagenes))
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

            <ol class="carousel-indicators">
                @foreach($imagenes as $key => $media)
                    @if($key == 0)
                        <li  style="display: none" data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" class="active"></li>
                    @else
                        <li style="display: none" data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" ></li>
                    @endif
                @endforeach
            </ol>


            <div class="carousel-inner">
                @foreach($imagenes as $key => $media)
                    @if($key == 0)
                        <div class="carousel-item active">
                            <img id="21" class="d-block w-100" src="{{ $media->getUrl() }}" alt="{{$key}} slide">
                        </div>
                    @else
                        <div class="carousel-item">
                            <img id="21" class="d-block w-100" src="{{ $media->getUrl() }}" alt="{{$key}} slide">
                        </div>
                    @endif

                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    @endif
</div>





