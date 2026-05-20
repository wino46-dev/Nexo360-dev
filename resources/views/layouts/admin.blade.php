<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

    @yield('styles')
    @php
        ($isEmbed = request()->boolean('embed') || request()->headers->get('X-Requested-With') === 'XMLHttpRequest')
    @endphp
    @if($isEmbed)
        <style>
            /* Ocultar chrome del panel cuando se renderiza embebido (modal/iframe/AJAX) */
            .c-header, .c-sidebar, #sidebar, .c-footer { display: none !important; }
            .c-wrapper, .c-body, .c-main, .container-fluid { margin: 0 !important; padding: 0 !important; }
            body.c-app { background: transparent; }
        </style>
    @endif
</head>

<body class="c-app">
    @include('partials.menu')
    <style>
        div.dataTables_wrapper div.dataTables_processing {
            background-color: #d5f1de !important;
            padding: 10px !important;
            z-index: 5000;
        }
    </style>
    <div class="c-wrapper">

        <header class="c-header c-header-fixed px-3">

            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <a class="c-header-brand d-lg-none" href="#">{{ trans('panel.site_title') }}</a>

            <button class="c-header-toggler mfs-3 d-md-down-none" type="button" responsive="true">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <ul class="c-header-nav ml-auto">


                @if(count(config('panel.available_languages', [])) > 1)
                    <li class="c-header-nav-item dropdown d-md-down-none">
                        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                            @endforeach
                        </div>
                    </li>
                @endif


            </ul>

        </header>

        <div class="c-body">

            <main class="c-main">
                <x-ControlSesion></x-ControlSesion>

                <div class="container-fluid">

                    @if(session()->has('message'))
                        @php
                            $msg = session('message');
                            $isTotemActiveMsg = \Illuminate\Support\Str::contains((string) $msg, 'Este tótem ya tiene una sesión de control activa');
                        @endphp
                        @if($isTotemActiveMsg)
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Tótem ocupado',
                                        text: @json($msg),
                                        confirmButtonText: 'Entendido'
                                    });
                                });
                            </script>
                        @else
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <div class="alert alert-success" role="alert">{{ $msg }}</div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                </div>


            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@3.2/dist/js/coreui.bundle.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <!-- Removed deprecated Flash export to avoid runtime errors in modern browsers -->
    <!-- <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script> -->
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    @if(!$isEmbed)
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    <script src="{{ asset('vendor/jquery-validate/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(function() {
            let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
            let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
            let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
            let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
            let printButtonTrans = '{{ trans('global.datatables.print') }}'
            let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
            let selectAllButtonTrans = '{{ trans('global.select_all') }}'
            let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

            let languages = {
                'es': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
            };

            if ($.fn && $.fn.dataTable) {
                if ($.fn.dataTable.Buttons && $.fn.dataTable.Buttons.defaults && $.fn.dataTable.Buttons.defaults.dom && $.fn.dataTable.Buttons.defaults.dom.button) {
                    $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' });
                }
                $.extend(true, $.fn.dataTable.defaults, {
                language: {
                url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [ {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                style:    'multi+shift',
                selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [

                {
                    extend: 'copy',
                    className: 'btn-default',
                    text: copyButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn-default',
                    text: csvButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn-default',
                    text: excelButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn-default',
                    text: pdfButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn-default',
                    text: printButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    className: 'btn-default',
                    text: colvisButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                }
                ]
            });

            }
            if ($.fn && $.fn.dataTable && $.fn.dataTable.ext && $.fn.dataTable.ext.classes) {
                $.fn.dataTable.ext.classes.sPageButton = '';
            }
        });
        var loading1 = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';
        var loading_sm = '<div class="text-center"><div class="spinner-border" style="width:1rem; height:1rem" role="status"><span class="sr-only">Loading...</span></div></div>';

    </script>

    <script>

        $(document).ready(function(){

            var user_id = {{ auth()->id() }};

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
            var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                cluster: 'eu'
            });

            var channel = pusher.subscribe('alda_events');

            channel.bind('TotemResponseToBackoffice', function(data) {

                var mensaje = data['message'];
                if (typeof mensaje["emisor_id"] !== 'undefined' && mensaje["emisor_id"] !== null) {
                    if(parseInt(mensaje["emisor_id"]) === user_id){

                        if(parseInt(mensaje['tipo_evento_id']) === 1) { // Notificar Conexión

                        }

                        if(parseInt(mensaje['tipo_evento_id']) === 2) { // Mostrar spinner

                        }

                        if(parseInt(mensaje['tipo_evento_id']) === 3) { // Capturar Foto Anverso

                            Toast.fire({
                                icon: "success",
                                title: 'Se ha recibido un documento (Anverso DNI)!'
                            });
                            if (typeof foto_documento_load === 'function') {
                                console.log(mensaje);
                                foto_documento_load(mensaje['objeto'], mensaje['tipo_evento_id']);
                            }

                            /*
                            let timerInterval

                            Swal.fire({
                                title: 'Se ha recibido un documento (Anverso DNI)!',
                                html: '',
                                timer: 3000,
                                timerProgressBar: true,

                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log('Cerrando Alerta')
                                }
                            })

                            $("#refreshDivCaptureForm").submit();*/

                        }

                        if(parseInt(mensaje['tipo_evento_id']) === 6) { // Capturar Foto Reverso

                            Toast.fire({
                                icon: "success",
                                title: 'Se ha recibido un documento (Reverso DNI)!'
                            });
                            if (typeof foto_documento_load === 'function') {
                                console.log(mensaje);
                                foto_documento_load(mensaje['objeto'], mensaje['tipo_evento_id']);
                            }

                        }

                        if(parseInt(mensaje['tipo_evento_id']) === 4) { // Solicitar Firma
                            Toast.fire({
                                icon: "success",
                                title: 'Se ha recibido la firma por parte del totem'
                            });
                            if (typeof foto_documento_load === 'function') {
                                console.log(mensaje);
                                foto_firma_load(mensaje['objeto']);
                            }
                        }

                        if(parseInt(mensaje['tipo_evento_id']) === 5) { // Mostrar Slide

                        }

                        if(parseInt(mensaje['tipo_evento_id']) === 0) { // Notificar Desconexion

                        }


                    }
                }else{
                    Swal.fire(
                        'Se recibió un socket no identificado!',
                        'Podrá encontrar los detalles en..!',
                        'error'
                    )
                }

            });


            channel.bind('CapturePaymentResponse', function(data) {

                var mensaje = data['message'];
                if (typeof mensaje["emisor_id"] !== 'undefined' && mensaje["emisor_id"] !== null) {
                    if(parseInt(mensaje["emisor_id"]) === user_id){
                        showFolioDetail(folio_current);
                        if(mensaje['resultado'] === 'Autorizada'){

                            Swal.fire({
                                //position: 'top-end',
                                icon: 'success',
                                title: 'Pago Autorizado',
                                html: '<br><p style="text-align: left"><b>Pedido:</b> '+ mensaje['pedido'] + '<p style="text-align: left"><b>Importe:</b> '+ mensaje['importe'] + '</p><p style="text-align: left"><b>Tarjeta:</b> '+ mensaje['tarjeta'] + '</p><p style="text-align: left"><b>Fecha:</b> '+ mensaje['fecha'] + '</p><p style="text-align: left"><b>Firma:</b> '+ mensaje['firma'] + '</p><br>',
                                showConfirmButton: false,
                                timer: 5500
                            })


                        }else{

                            if(parseInt(mensaje['codigoRespuesta']) === 117){

                                Swal.fire({
                                    //position: 'top-end',
                                    icon: 'error',
                                    title: 'Operación de pago denegada: Pin incorrecto',
                                    html: '<br><p style="text-align: left"><b>Pedido:</b> '+ mensaje['pedido'] + '<p style="text-align: left"><b>Importe:</b> '+ mensaje['importe'] + '</p><p style="text-align: left;color:red"><b>Código de Error:</b> '+ mensaje['codigoRespuesta'] + '</p><br>',
                                    showConfirmButton: false,
                                    timer: 5500
                                })

                            }else if(mensaje['resultado'] === 'Error'){

                                Swal.fire({
                                    //position: 'top-end',
                                    icon: 'error',
                                    title: 'Operación de pago denegada',
                                    html: '<br><p style="text-align: left;color:red"><b>Código Error :</b> '+ mensaje['mensaje']['Error']['codigo'] + '</p><p style="text-align: left;color:red"><b>Mensaje Error :</b> '+ mensaje['mensaje']['Error']['mensaje'] + '</p><br>',
                                    showConfirmButton: false,
                                    timer: 5500
                                })

                            }else if(mensaje['estado'] !== 'F'){

                                Swal.fire({
                                    //position: 'top-end',
                                    icon: 'error',
                                    title: 'Operación de pago denegada',
                                    html: '<br><p style="text-align: left"><b>Pedido:</b> '+ mensaje['pedido'] + '<p style="text-align: left"><b>Importe:</b> '+ mensaje['importe'] + '</p><p style="text-align: left;color:red"><b>Código de Error:</b> '+ mensaje['codigoRespuesta'] + '</p><br>',
                                    showConfirmButton: false,
                                    timer: 5500
                                })

                            }else{
                                Swal.fire({
                                    //position: 'top-end',
                                    icon: 'error',
                                    title: 'Operación de pago cancelada',
                                    html: '<br><p style="text-align: left"><b>Pedido:</b> '+ mensaje['pedido'] + '<p style="text-align: left"><b>Importe:</b> '+ mensaje['importe'] + '</p><p style="text-align: left;color:red"><b>Código de Error:</b> '+ mensaje['codigoRespuesta'] + '</p><br>',
                                    showConfirmButton: false,
                                    timer: 5500
                                })

                            }


                        }

                    }
                }else{
                    alert('No user ID detected On request chanel - Notify Payment');
                }
                setTimeout(function(){
                    payment_table_load();
                }, 5500);

            });


            channel.bind('WriteCardResponse', function(data) {

                var mensaje = data['message'];
                if (typeof mensaje["emisor_id"] !== 'undefined' && mensaje["emisor_id"] !== null ) {

                    if(parseInt(mensaje["emisor_id"]) === user_id){

                        if(mensaje["status"] === 'Ok'){ // OK
                            let timerInterval
                            Swal.fire({
                                icon: 'success',
                                title: 'Proceso finalizado con éxito.',
                                html: '<p>El usuario ya puede retirar la tarjeta</p>',
                                timer: 4000,
                                timerProgressBar: true,
                                showConfirmButton: false,

                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log('Cerrando Alerta');

                                }
                            })


                        }if(mensaje["status"] === 'Fail'){ // Fallo
                            let timerInterval
                            Swal.fire({
                                icon: 'error',
                                title: 'Ups!',
                                html: 'Ha habido un fallo en la grabación:' + mensaje["track"],
                                timer: 10000,
                                timerProgressBar: true,
                                showConfirmButton: false,

                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log('Cerrando Alerta');

                                }
                            })



                        }
                    }
                }else{
                    alert('No user ID detected On request chanel - Write Card');
                }

            });

            channel.bind('AliceEvent', function(data) {

                var mensaje = data['message'];

                if (typeof mensaje["emisor_id"] !== 'undefined' && mensaje["emisor_id"] !== null ) {
                    if(parseInt(mensaje["emisor_id"]) === user_id){
                        if(mensaje["action"] == 'notification-timeout'){
                            Swal.fire({
                                title: 'Cierre por inactividad en Totem',
                                text: 'Desea continuar en la llamada?',
                                icon: 'info',
                                showCancelButton: false,
                                confirmButtonText: 'Seguir en llamada',
                                timer: 8000, // Tiempo en milisegundos (10 segundos)
                                timerProgressBar: true,
                                allowOutsideClick: false // Evita que se cierre haciendo clic fuera del alert
                            }).then((result) => {
                            // La función se ejecutará al cerrar el SweetAlert
                                if (result.dismiss === Swal.DismissReason.timer) {

                                } else if (result.isConfirmed) {
                                    alice_event_cancel_timeout()
                                } else if (result.isDenied) {

                                }
                            });
                        }
                        if(mensaje["action"] == 'get-document-success-front'){
                            $('#alice_res_get_document_front').html('Captura Ok');
                            alice_modal_status(mensaje["checkin_id"]);
                        }
                        if(mensaje["action"] == 'get-document-success-back'){
                            $('#alice_res_get_document_back').html('Captura Ok.');
                            alice_modal_status(mensaje["checkin_id"]);
                        }
                    }
                }
            });

        });

        function alice_event_cancel_timeout(){
            $.ajax({
                url: "{{ url("alice/notification") }}",
                method: 'POST',
                cache: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data: {
                    emisor_id: '{{ $control_actual->emisor_id ?? '' }}',
                    receptor_id: '{{ $control_actual->receptor_id ?? '' }}',
                    action: 'notification-timeout-cancel',
                },
            }).done(function(data) {
                console.log(data);
                Toast.fire({
                    icon: "success",
                    title: 'Evento enviado'
                });

            }).fail(function(error) {
                console.error('Error:', error);
            });
        }

        window.Toast = window.Toast || Swal.mixin({
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


    </script>

    @if(!empty($showTotemTypeAlert) && $showTotemTypeAlert)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                try {
                    const count = Number(@json($totemTypeMismatchCount));
                    Swal.fire({
                        icon: 'info',
                        title: 'Usuarios Totem con tipo incorrecto',
                        html: 'Se han detectado <b>' + count + '</b> usuario(s) con rol <b>Totem</b> que tienen el <b>tipo</b> incorrecto.<br><br>' +
                             'Por favor, edite cada usuario y establezca el tipo <b>totem</b> o bien quite el rol <b>Totem</b> si debe permanecer <b>interno</b> o <b>externo</b>.',
                        confirmButtonText: 'Entendido'
                    });
                } catch (e) { console.error(e); }
            });
        </script>
    @endif

    @yield('scripts')
</body>

</html>

