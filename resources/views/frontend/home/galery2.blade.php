<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://kenwheeler.github.io/slick/slick/slick-theme.css"/>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>


@if(isset($imagenes))
    <div style="padding:0px 55px" class="centrar_vertical_container">
        <div class="slider lazy">
            @foreach($imagenes as $key => $media)

                <div>
                    <div class="image">
                        <a data-fancybox="gallery" data-src="{{ $media->getUrl() }}" style="cursor:pointer">
                            <img data-lazy="{{ $media->getUrl() }}" class="img-fluid" />
                        </a>
                    </div>
                </div>

            @endforeach
        </div>
    </div>

@endif
<script>
    function fancysh(){
        Fancybox.bind('[data-fancybox]', {
            Images: {
                zoom: false,
            },
            Thumbs: false,
            wheel:false,


            on: {
                initCarousel: (fancybox, slide) => {
                // The content of this slide is loaded and ready to be revealed
                    console.log('donee')
                    //$('#call_conte').hide();
                    fancybox__backdrop_custom();


                },
                close: (fancybox, slide) => {
                // The content of this slide is loaded and ready to be revealed
                    console.log('close')
                    //$('#call_conte').show();
                },
            },
        });

        // Initialize Slick only when the slider exists and contains slides to avoid '$slides is null' errors
        const initSlickIfReady = () => {
            const $slider = $('.lazy');
            if ($slider.length === 0) return; // container missing
            // Ensure there is at least one child slide element before initializing
            if ($slider.children().length === 0) return;
            if ($slider.hasClass('slick-initialized')) return; // avoid re-init
            $slider.slick({
                autoplay: true,
                lazyLoad: 'ondemand',
                slidesToShow: 3,
                slidesToScroll: 1,
            });
        };
        // Try immediately, then again after a short delay in case images are injected slightly later
        initSlickIfReady();
        setTimeout(initSlickIfReady, 500);

    }


    function fancybox__backdrop_custom(){
        let posicion = $('.page3_reserva').offset();
            $('.fancybox__backdrop, .fancybox__container').css('top', posicion.top + 'px');

            setTimeout(() => {
                $('[data-fancybox-toggle-slideshow]').hide();
                $('[data-panzoom-action]').hide();
                $('[data-fancybox-toggle-fullscreen]').hide();
                $('[data-fancybox-toggle-thumbs]').hide();
            }, 100);
    }

    function imagen_pusher(url){

        Fancybox.close();
        if(url == "ocultar_imagen"){

        }else{
            new Fancybox(
                [
                    {
                        src: url,
                    },
                ],
                {
                    wheel:false,
                    on: {
                        initCarousel: (fancybox, slide) => {

                            fancybox__backdrop_custom();
                        },
                        close: (fancybox, slide) => {

                        },
                    }
                }
            );
        }
    }

    /*setTimeout(() => {
        imagen_pusher("https://sh360.neotech360.com/storage/167/6538e300b6eb5_PARKING-SLIDE-1060-(1).jpg");
    }, 2000);*/

    //fancysh();

    /*$(document).ready(function(){
        $('.lazy').slick({
            lazyLoad: 'ondemand',
            slidesToShow: 3,
            slidesToScroll: 1
        });
    });*/

</script>
<style>
    .image{
        margin:10px;
    }
    .slick-prev, .slick-next{

    }
    .slick-prev:before, .slick-next:before {
        font-size: 35px;
        color:black;
    }
    .fancybox__content{
        padding:10px !important;
    }
    .is-left{
        display:none !important;
    }

    .fancybox__slide::before, .fancybox__slide::after {
        margin:0px !important;
    }
</style>
