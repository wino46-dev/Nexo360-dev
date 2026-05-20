@extends('layouts.totem')

@section('content')
    @if (empty($establecimiento->ocultar_header_totem))
        <div class="container-fluid px-4">
            @include('frontend.home.header')
        </div>
    @endif

    <div class="container-principal" style="overflow: hidden">
        <div class="container-fluid px-0">

            @include('frontend.home.messages')

            @include('frontend.home.page1')

            @include('frontend.home.page_llamada')

            @include('frontend.home.page2')

            @include('frontend.home.page3')

            @include('frontend.home.reservation_modal')

            @include('frontend.home.pago_modal')


        </div>
    </div>
    @include('frontend.home.tabletStatusBar')
    @include('frontend.home.signature')

    @include('frontend.alice.modal')

    @include('frontend.home.footer')
@endsection

@section('scripts')
    @include('frontend.home.scripts')

    <script>
        var loading1 = '<div class="text-center"><img src="/img/loading.gif" /></div>';
        var loading_sm =
            '<div class="text-center"><div class="spinner-border" style="width:1rem; height:1rem" role="status"><span class="sr-only">Loading...</span></div></div>';

        //var tiempoInactividad = 120000; // 2 minutos en milisegundos 120000
        var tiempoInactividad = 360000; // 6 minutos en milisegundos 120000
        var temporizador;

        $(document).ready(function() {
            /// altura contenedor principal
            let alturaDiv = $(".footer").height();
            $(".container-principal").css("bottom", alturaDiv + "px");

            let alturaDiv2 = $(".container-principal").height();

            $('.conte_video').height((alturaDiv2 / 3) - 10);
            $('.page3_reserva').height((alturaDiv2 / 3) - 10);
            $('.page3_gallery').height((alturaDiv2 / 3) - 10);

            reiniciarTemporizador();

            // checkinFinalizar(901947);

        });

        $(document).on('mousemove touchstart touchmove keypress', function() {
            reiniciarTemporizador();
        });

        function reiniciarTemporizador() {
            clearTimeout(temporizador);
            temporizador = setTimeout(function() {

                if ($('#page1_container').is(':visible')) {
                    console.log('No mostrar alert')
                } else if ($('#page_llamada_container').is(':visible')) {
                    location.reload(true);
                } else {
                    console.log('mostrar alert')
                    mostrarAlert();
                }

            }, tiempoInactividad);
        }

        function mostrarAlert() {
            // Utiliza SweetAlert2 para mostrar el alert
            temporizadorNotificar();
            /*Swal.fire({
                title: 'Cierre por inactividad',
                text: 'Desea continuar en la llamada?',
                icon: 'info',
                showCancelButton: false,
                confirmButtonText: 'Seguir en llamada',
                timer: 10000, // Tiempo en milisegundos (10 segundos)
                timerProgressBar: true,
                allowOutsideClick: false // Evita que se cierre haciendo clic fuera del alert
            }).then((result) => {
            // La función se ejecutará al cerrar el SweetAlert
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.reload(true);
                } else if (result.isConfirmed) {
                    reiniciarTemporizador();
                } else if (result.isDenied) {
                    location.reload(true);
                }
            });*/
        }

        function temporizadorNotificar() {

            $.ajax({
                url: "{{ url('alice/notification') }}",
                method: 'POST',
                cache: false,
                data: {
                    emisor_id: emisor_id,
                    receptor_id: '{{ auth()->id() }}',
                    action: 'notification-timeout',
                },
            }).done(function(data) {
                console.log(data);

            }).fail(function(error) {
                console.error('Error:', error);
            });
        }

        function alice_event_get_document_success(side, alice_data) {
            alice_data.action = 'get-document-success-' + side;

            $.ajax({
                url: "{{ url('alice/notification') }}",
                method: 'POST',
                cache: false,
                data: alice_data,
            }).done(function(data) {

            }).fail(function(error) {
                console.error('Error:', error);
            });
        }


        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function showOnly(selector) {
            // Mantener modo demo, pero sin alterar el comportamiento base
            const ids = ['#page1_container', '#page_llamada_container', '#page2_container', '#page3_container'];
            ids.forEach(id => $(id).hide());
            if (selector) {
                $(selector).show();
            }
        }

        function getQueryParam(name) {
            const params = new URLSearchParams(window.location.search);
            return params.get(name);
        }

        // Modo demo por parámetros de URL: ?demo=1&page=page1|llamada|page2|page3
        const isDemo = getQueryParam('demo') === '1';
        const page = getQueryParam('page');
        if (isDemo) {
            switch(page){
                case 'page1':
                    showOnly('#page1_container');
                    break;
                case 'llamada':
                    showOnly('#page_llamada_container');
                    break;
                case 'page2':
                    showOnly('#page2_container');
                    break;
                case 'page3':
                    showOnly('#page3_container');
                    break;
                default:
                    showOnly('#page1_container');
            }
        } else {
            // Comportamiento original: por defecto se muestra page1 via código existente más abajo
            $('#page1_container').show();
        }
    </script>
@endsection

@section('styles')
    <style>
        .container-principal {
            position: absolute;

            @if (empty($establecimiento->ocultar_header_totem))
                top: 80px;
            @else
                top: 10px;
            @endif
            left:0px;
            right:0px;
            bottom: 0px;
            overflow: auto;
        }

        .btn_call_container {
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
        }

        .btn_call {
            width: 50%;
            padding-top: 30px;
            padding-bottom: 30px;
            font-size: 200%;
        }

        .spinner_container {
            position: absolute;
            top: 150px;
            left: 0px;
            right: 0px;
        }

        .page2_img_container img {
            width: 100%
        }

        .colgar_page2_container {
            position: absolute;
            top: 290px;
            left: 0px;
            right: 0px;
        }

        .page3_callname {
            font-size: 150%;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: white;
        }

        .centrar_vertical_container {
            position: absolute;
            top: 50%;
            left: 0px;
            transform: translate(0%, -50%);
            right: 0px;
        }

        .modal-lg {
            max-width: 95% !important;
        }

        #alice-app,
        #alice-app-modal {
            min-height: 100% !important;
        }

        /*.fancybox__backdrop, .fancybox__container{
                top:400px !important;
            }*/

        /* ===== Layout tablet 50/50 (videollamada izquierda, reserva derecha) ===== */
        @media (min-width: 768px) and (max-width: 1024px) {
            /* Contenedor 50/50 sólo cuando se muestra la página 3 (llamada) */
            #page3_container .tablet-two-col {
                display: flex;
                gap: 12px;
                height: 100%;
            }
            #page3_container .tablet-two-col > .tablet-col {
                flex: 0 0 50%;
                max-width: 50%;
                display: flex; /* para estirar el contenido a la altura disponible */
            }
            #page3_container .tablet-two-col > .tablet-col > * {
                flex: 1 1 auto;
                min-height: 0; /* evita overflow en componentes internos */
            }
            /* Ocultar galería en modo tablet para priorizar 50/50 */
            #page3_container .page3_gallery { display: none; }

            /* Ocultar cualquier slider/carrusel dentro de la página de llamada en tablet */
            #page_llamada_container .carousel { display: none !important; }

            /* Barra inferior visible en tablet */
            .tablet-status-bar { display: block; }

            /* Reserva espacio inferior para la barra de estado en tablets */
            .container-principal { padding-bottom: 64px; }
            #page3_container { height: 100%; }
        }

        /* Por defecto, la barra no ocupa espacio en desktop/móvil si no aplica */
        .tablet-status-bar { display: none; }

        /* Refuerzo: centrar modales un poco más abajo */
        .modal.show .modal-dialog { margin-top: 10vh; }

        /* Nota: se mantiene únicamente el responsive de tablet y breadcrumbs; sin aislamientos extra */
    </style>
@endsection
