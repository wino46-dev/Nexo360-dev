<!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script> -->
<script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
<script src="https://unpkg.com/aliceonboarding@8.1.21/dist/aliceonboarding.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/aliceonboarding@8.1.21/dist/aliceonboarding.css" />
<script>
    var id_evento_foto = 0;
    var id_evento_firma = 0;
    var emisor_id = null;
    $(document).ready(function() {


        var user_id = {{ auth()->id() }};

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;
        console.log('PUSHER INIT....')
        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: 'eu'
        });


        var channel = pusher.subscribe('alda_events');

        channel.bind('TotemHomeEvent', function(data) {



            var mensaje = data['message'];

            if (typeof mensaje["receptor_id"] !== 'undefined' && mensaje["receptor_id"] !== null &&
                mensaje["canal_transmision"] == 'Home Inferior') {
                reiniciarTemporizador();

                Fancybox.close();


                console.log('PUSHER', 'TotemHomeEvent');

                emisor_id = mensaje["emisor_id"] ?? null;

                if (parseInt(mensaje["receptor_id"]) === user_id) {

                    if (parseInt(mensaje['tipo_evento_id']) != 8) {
                        $(".card:not(.divcall)").hide();
                    }
                    if (parseInt(mensaje['tipo_evento_id']) === 99) { // Notificacion de sesion automática
                        console.log("Evento recibido por "+ mensaje['receptor_id'] + " con código 99 desde usuario " + mensaje['emisor_id'])
                        openCamera();
                    }

                    if (parseInt(mensaje['tipo_evento_id']) === 1) { // Notificar Conexión

                        //if ( !$( ".mensajeh5" ).length ) {
                        //if (typeof mensaje["receptor_id"] !== 'undefined'){
                        $(".showAgent").show().append(mensaje["mensaje_conectado"]);
                        //}
                        //}
                        $(".divspinner").hide();
                        $(".cajaScreenCamera").hide();
                        $("#fotoDocumento").hide()
                        $(".divTourGalery").hide();
                        $("#results").hide()
                        $(".divcarousel").show();
                        $('.page3_gallery').show();

                    }

                    if (parseInt(mensaje['tipo_evento_id']) === 2) { // Mostrar spinner
                        $(".divspinner").show();
                    }
                    if (parseInt(mensaje['tipo_evento_id']) === 3) {
                        console.log('Captura foto parte delantera')
                        $(".divspinner").hide();
                        $(".cajaScreenCamera").show();
                        $(".divTourGalery").hide();
                        $("#results").empty()
                        $("#fotoDocumento").empty()
                        $("#fotoDocumento").append('Paso 1: Capturar Anverso del documento (' + mensaje[
                            'mensaje'] + ')')
                        $(".divcarousel").hide();
                        id_evento_foto = mensaje['id'];

                        $('.page3_gallery').hide();
                    }
                    if (parseInt(mensaje['tipo_evento_id']) === 6) {
                        console.log('Captura foto parte trasera')
                        $(".divspinner").hide();
                        $(".cajaScreenCamera").show();
                        $("#fotoDocumento").empty()
                        $(".divTourGalery").hide();
                        $("#results").empty()
                        $("#fotoDocumento").append('Paso 2: Capturar Reverso del documento (' + mensaje[
                            'mensaje'] + ')')
                        id_evento_foto = mensaje['id'];
                        $(".divcarousel").hide();
                        $('.page3_gallery').hide();
                    }
                    if (parseInt(mensaje['tipo_evento_id']) === 8) {
                        take_snapshot_auto();
                    }

                    if (parseInt(mensaje['tipo_evento_id']) === 4) { // Solicitar Firma
                        $(".divspinner").hide();
                        $(".cajaScreenCamera").hide();
                        $(".divTourGalery").hide();
                        $(".divcarousel").hide();
                        $(".centrar_vertical_container").hide();
                        $('.accept_checkin_class').css('color', 'black');

                        //$(".cajaFirma").show();
                        $('#firma_modal').modal('show');
                        id_evento_firma = mensaje['id'];
                        //alert(id_evento_firma);
                        let timerInterval
                        Swal.fire({
                            //icon: 'success',
                            title: 'Iniciando proceso de Firma!',
                            //html: '<p>Retire la tarjeta</p>',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,

                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log('Cerrando Alerta')
                            }
                        })

                        console.log('objeto', mensaje['objeto']);
                        $('#firma_parte_viajero_header').html('');
                        $('#accept_checkin').prop('checked', false);

                        $.ajax({
                            url: "{{ url('/evento-home-totems/parte-viajero-header') }}/" +
                                mensaje['objeto']+'?pms='+mensaje['pms'],
                            method: 'GET',
                            cache: false,
                            success: function(response) {
                                $('#firma_parte_viajero_header').html(response);
                            },
                            error: function(response) {

                            },
                        });

                        $('.page3_gallery').hide();
                    }

                    if (parseInt(mensaje['tipo_evento_id']) === 5) { // Mostrar Slide
                        $(".divspinner").hide();
                        $(".cajaScreenCamera").hide();
                        $("#fotoDocumento").hide()
                        $(".divTourGalery").hide();
                        $("#results").hide()
                        $('.page3_gallery').show();
                        //   $(".divcarousel").show();
                    }

                    if (parseInt(mensaje['tipo_evento_id']) === 0) { // Notificar Desconexion
                        closeCamera();
                        location.reload(true);
                    }

                    if (parseInt(mensaje['tipo_evento_id']) === 7) { // Notificar DesconexioFn
                        checkinFinalizar(mensaje['objeto']);
                    }

                    if (mensaje['tipo_evento_id'] == 'reverse_call') { // Notificar Desconexion
                        //alert(mensaje['mensaje'])
                        openCamera();
                        llamada_inversa(mensaje['mensaje']);

                    }

                    if (mensaje['tipo_evento_id'] ==
                        'cerrar_parte_viajero') { // Notificar Desconexion
                        $('#reservation_modal').modal('hide');
                    }


                }
            }

        });

        channel.bind('NotifyPayment', function(data) {
            reiniciarTemporizador();
            console.log('PUSHER', 'NotifyPayment');
            var mensaje = data['message'];
            if (typeof mensaje["receptor_id"] !== 'undefined' && mensaje["receptor_id"] !== null) {

                emisor_id = mensaje["emisor_id"] ?? null;

                if (parseInt(mensaje["receptor_id"]) === user_id) {

                    let timerInterval
                    Swal.fire({
                        title: 'Iniciando proceso de Pago!',
                        html: '',
                        timer: 3000,
                        timerProgressBar: true,

                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            console.log('Cerrando Alerta')
                        }
                    })

                }
            } else {
                alert('No user ID detected On request chanel - Notify Payment');
            }

        });

        channel.bind('CapturePaymentResponse', function(data) {
            reiniciarTemporizador();
            console.log('PUSHER', 'CapturePaymentResponse');
            var mensaje = data['message'];
            if (typeof mensaje["receptor_id"] !== 'undefined' && mensaje["receptor_id"] !== null) {

                emisor_id = mensaje["emisor_id"] ?? null;

                if (parseInt(mensaje["receptor_id"]) === user_id) {

                    var html_denegada = `
                            <div>
                                <div class="text-center">
                                    <img src="{{ $establecimiento->logo_establecimiento ? $establecimiento->logo_establecimiento->getUrl() : '' }}" class="img-fluid" style="max-height:110px"  />
                                </div>
                                <div class="text-center">
                                    <div class="swal2-icon swal2-error swal2-icon-show" style="display: flex; margin: 1.5em auto .6em">
                                        <span class="swal2-x-mark">
                                            <span class="swal2-x-mark-line-left"></span>
                                            <span class="swal2-x-mark-line-right"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                    if (mensaje['resultado'] === 'Autorizada') {

                        $('#pago_modal').modal('show');
                        $('#pago_modal').find('.modal-body').html('<br />' + loading1);

                        $.ajax({
                            url: "{{ url('/evento-home-totems/pago-pdf') }}",
                            method: 'POST',
                            cache: false,
                            data: mensaje,
                            success: function(response) {
                                $('#pago_modal').find('.modal-body').html(response);
                            },
                            error: function(response) {

                            },
                        });


                        let a_codigoRespuesta = mensaje['codigoRespuesta'];
                        let a_pedido = mensaje['pedido'];
                        let a_importe = mensaje['importe'];
                        let a_tarjeta = mensaje['tarjeta'];
                        let a_fecha_full = mensaje['fecha'];
                        let a_firma = mensaje['firma'];
                        let a_comercio = mensaje['comercio'];
                        let a_terminal = mensaje['terminal'];
                        let a_tarjetaClienteRecibo = mensaje['tarjetaClienteRecibo'];
                        let a_etiquetaApp = mensaje['etiquetaApp'];
                        let a_literales = mensaje['Literales'];
                        let a_literales_autenticado_por = a_literales.autenticadoPorPin ?? '';
                        let a_idapp = mensaje['idapp'];
                        let a_conttrans = mensaje['conttrans'];
                        let a_codrespauto = mensaje['codrespauto'];
                        let a_resverificacion = mensaje['resverificacion'];

                        let [a_fecha, a_hora] = a_fecha_full.split(' ');

                        let html_autorizada = `
                            <div>
                                <div class="text-center">
                                    <img src="{{ $establecimiento->logo_establecimiento ? $establecimiento->logo_establecimiento->getUrl() : '' }}" class="img-fluid" style="max-height:110px"  />
                                </div>
                                <div class="text-center mt-3 mb-3" style="font-size:140%">
                                    <b>PAGO AUTORIZADO</b>
                                </div>
                                <div class="text-center mt-1">
                                    {{ $establecimiento->nombre }}
                                </div>
                                <div class="text-left mt-2">
                                    No. TRANSACCIÓN: <b>${a_conttrans}</b>
                                </div>
                                <div class="text-left mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            COMERCIO: ${a_comercio}
                                        </div>
                                        <div class="col-6">
                                            AID: ${a_idapp}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            TPV: ${a_terminal}
                                        </div>
                                        <div class="col-6">
                                            ARC: ${a_codrespauto}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-1">
                                    <span style="font-size:120%">${a_tarjeta}</span>
                                </div>
                                <div class="text-left mt-1">
                                    ${a_etiquetaApp}
                                </div>
                                <div class="text-left mt-3">
                                    VENTA
                                </div>
                                <div class="mt-1">
                                    <table style="width:100%" border="0">
                                        <tr>
                                            <td>
                                                <div>Aut: ${a_codigoRespuesta}</div>
                                                <div>Fecha: ${a_fecha} </div>
                                            </td>
                                            <td>
                                                <div>Ped: ${a_pedido}</div>
                                                <div>Hora: ${a_hora}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    TRANSACTION CURRENCY
                                </div>
                                <div class="text-center mt-1">
                                    <b><span style="font-size:130%">${a_importe} &nbsp;&nbsp;&nbsp; EUR</span> </b>
                                </div>
                                <div class="text-center mt-2 mb-2 ">
                                    <div class="alert alert-secondary p-1" role="alert">
                                        ${a_literales_autenticado_por}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img src="/img/redsys-pago-seguro-300x85-1.png" class="img-fluid" style="max-height:110px"  />
                                </div>
                                <div class="mt-4">
                                    {!! $sociedad->pago_html_inferior !!}
                                </div>
                            </div>
                        `;
                        /*Swal.fire({
                            //position: 'top-end',
                            icon: null,
                            title:null,
                            html: html_autorizada,
                            showConfirmButton: false,
                            timer: 10000
                        })*/


                    } else {

                        if (parseInt(mensaje['codigoRespuesta']) === 117) {

                            Swal.fire({
                                //position: 'top-end',
                                icon: null,
                                title: null,
                                html: html_denegada +
                                    '<h3>Operación de pago denegada 117. Pin incorrecto</h3><br><p style="text-align: left"><b>Pedido:</b> ' +
                                    mensaje['pedido'] +
                                    '<p style="text-align: left"><b>Importe:</b> ' + mensaje[
                                        'importe'] +
                                    '</p><p style="text-align: left;color:red"><b>Código de Error:</b> ' +
                                    mensaje['codigoRespuesta'] + '</p><br>',
                                showConfirmButton: false,
                                timer: 5500
                            })
                        } else if (parseInt(mensaje['codigoRespuesta']) === 101) {
                            Swal.fire({
                                //position: 'top-end',
                                icon: null,
                                title: null,
                                html: html_denegada +
                                    '<h3>Operación de pago denegada 101. Tarjeta Caducada</h3><br><p style="text-align: left"><b>Pedido:</b> ' +
                                    mensaje['pedido'] +
                                    '<p style="text-align: left"><b>Importe:</b> ' + mensaje[
                                        'importe'] +
                                    '</p><p style="text-align: left;color:red"><b>Código de Error:</b> ' +
                                    mensaje['codigoRespuesta'] + '</p><br>',
                                showConfirmButton: false,
                                timer: 5500
                            })

                        } else if (mensaje['resultado'] === 'Error') {

                            Swal.fire({
                                //position: 'top-end',
                                icon: null,
                                title: null,
                                html: html_denegada +
                                    '<h3>Operación de pago denegada</h3><br><p style="text-align: left;color:red"><b>Código Error :</b> ' +
                                    mensaje['mensaje']['Error']['codigo'] +
                                    '</p><p style="text-align: left;color:red"><b>Mensaje Error :</b> ' +
                                    mensaje['mensaje']['Error']['mensaje'] + '</p><br>',
                                showConfirmButton: false,
                                timer: 5500
                            })

                        } else if (mensaje['estado'] !== 'F') {

                            Swal.fire({
                                //position: 'top-end',
                                icon: null,
                                title: null,
                                html: html_denegada +
                                    '<h3>Operación de pago denegada</h3><br><p style="text-align: left"><b>Pedido:</b> ' +
                                    mensaje['pedido'] +
                                    '<p style="text-align: left"><b>Importe:</b> ' + mensaje[
                                        'importe'] +
                                    '</p><p style="text-align: left;color:red"><b>Código de Error:</b> ' +
                                    mensaje['codigoRespuesta'] + '</p><br>',
                                showConfirmButton: false,
                                timer: 5500
                            })

                        } else {
                            Swal.fire({
                                //position: 'top-end',
                                icon: null,
                                title: null,
                                html: html_denegada +
                                    '<h3>Operación de pago cancelada</h3><br><p style="text-align: left"><b>Pedido:</b> ' +
                                    mensaje['pedido'] +
                                    '<p style="text-align: left"><b>Importe:</b> ' + mensaje[
                                        'importe'] +
                                    '</p><p style="text-align: left;color:red"><b>Código de Error:</b> ' +
                                    mensaje['codigoRespuesta'] + '</p><br>',
                                showConfirmButton: false,
                                timer: 5500
                            })

                        }

                    }

                }
            } else {
                alert('No user ID detected On request chanel - Notify Payment');
            }

        });

        channel.bind('WriteCard', function(data) {
            reiniciarTemporizador();
            console.log('PUSHER', 'WriteCard');
            var mensaje = data['message'];
            if (typeof mensaje["receptor_id"] !== 'undefined' && mensaje["receptor_id"] !== null) {

                emisor_id = mensaje["emisor_id"] ?? null;

                if (parseInt(mensaje["receptor_id"]) === user_id) {

                    if (parseInt(mensaje["step"]) === 1) { // Notificar colocar tarjeta en tarjetero
                        let timerInterval
                        Swal.fire({
                            title: 'Coloque la tarjeta sobre el grabador bajo la pantalla!',
                            html: '',
                            timer: 4000,
                            timerProgressBar: true,
                            showConfirmButton: false,

                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log('Cerrando Alerta')
                            }
                        })

                    } else if (mensaje["status"] === 'Pendiente') { // Iniciando grabación
                        let timerInterval
                        Swal.fire({
                            title: 'Grabando tarjeta. Por favor no la retire!',
                            html: '',
                            timer: 10000,
                            timerProgressBar: true,
                            showConfirmButton: false,

                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log('Cerrando Alerta')
                            }
                        })
                    }
                }
            } else {
                alert('No user ID detected On request chanel - Write Card');
            }

        });

        channel.bind('WriteCardResponse', function(data) {
            reiniciarTemporizador();
            console.log('PUSHER', 'WriteCardResponse');
            var mensaje = data['message'];
            if (typeof mensaje["receptor_id"] !== 'undefined' && mensaje["receptor_id"] !== null) {

                emisor_id = mensaje["emisor_id"] ?? null;

                if (mensaje["receptor_id"] === user_id) {

                    if (mensaje["status"] === 'Ok') { // OK
                        let timerInterval
                        Swal.fire({
                            icon: 'success',
                            title: 'Proceso finalizado con éxito.',
                            html: '<p>Retire la tarjeta</p>',
                            timer: 4000,
                            timerProgressBar: true,
                            showConfirmButton: false,

                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log('Cerrando Alerta')
                            }
                        })

                    }
                    if (mensaje["status"] === 'Fail') { // Fallo
                        let timerInterval
                        Swal.fire({
                            icon: 'error',
                            title: 'Ups!',
                            html: 'Ha habido un fallo en la grabación. Nuestro agente le ayudará',
                            timer: 4000,
                            timerProgressBar: true,
                            showConfirmButton: false,

                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log('Cerrando Alerta')
                            }
                        })
                    }
                }
            } else {
                alert('No user ID detected On request chanel - Write Card');
            }

        });

        channel.bind('TourGaleryEvent', function(data) {
            reiniciarTemporizador();
            console.log('PUSHER', 'TourGaleryEvent');
            var mensaje = data['message'];
            if (typeof mensaje["receptor_id"] !== 'undefined' && mensaje["receptor_id"] !== null) {

                emisor_id = mensaje["emisor_id"] ?? null;

                if (parseInt(mensaje["receptor_id"]) === user_id) {

                    $(".divcarousel").hide();
                    $(".divspinner").hide();
                    $(".cajaScreenCamera").hide();
                    $("#fotoDocumento").hide()
                    $("#results").hide()
                    $(".divTourGalery").empty()
                    $(".divTourGalery").show();
                    imagen_pusher(mensaje['url']);


                    //$(".divTourGalery").append('<img src="'+ mensaje['url'] + '" width="100%">');

                }
            } else {
                alert('No user ID detected On request chanel - Write Card');
            }

        })

        channel.bind('TotemReservationLoadEvent', function(data) {
            reiniciarTemporizador();
            console.log('PUSHER', 'TotemReservationLoadEvent');
            var mensaje = data['message'];
            emisor_id = mensaje["emisor_id"] ?? null;
            if (parseInt(mensaje["receptor_id"]) === user_id) {

                if (typeof mensaje.reserva.id !== 'undefined' && mensaje.reserva.id !== null) {

                    let reservation_info = $('.reservation-info');
                    //console.log(mensaje.reserva);

                    reservation_info.find('.codigo').html(mensaje.reserva.name);
                    reservation_info.find('.cliente').html(mensaje.reserva.partnerName);
                    //reservation_info.find('.nif').html(mensaje.reserva.cliente.nif);

                    reservation_info.find('.adultos').html(mensaje.reserva.adults);
                    reservation_info.find('.ninos').html(mensaje.reserva.children);
                    reservation_info.find('.roomName').html(mensaje.reserva.roomName);
                    reservation_info.find('.total').html(mensaje.reserva.priceTotal + ' €');

                    let che_checkin = mensaje.reserva.checkin;
                    let che_checkout = mensaje.reserva.checkout;

                    reservation_info.find('.entrada').html(che_checkin.split("T")[0]);
                    reservation_info.find('.salida').html(che_checkout.split("T")[0]);


                    //reservation_info.find('.pendiente').html(mensaje.reserva.pendingAmount);


                }

            } else {
                console.log('no ntrooo')
            }

        });

        channel.bind('AliceEvent', function(data) {
            var mensaje = data['message'];

            emisor_id = mensaje["emisor_id"] ?? null;
            if (parseInt(mensaje["receptor_id"]) === user_id) {

                if (mensaje["action"] == 'capture') {
                    $('#alice_modal').modal('show');
                    $('#alice_modal').find('.modal-body').html('<br />' + loading1);
                    $.ajax({
                        url: "{{ url('alice/capture') }}",
                        method: 'POST',
                        cache: false,
                        data: mensaje,
                        success: function(response) {
                            $('#alice_modal').find('.modal-body').html(response);
                        },
                        error: function(response) {

                        },
                    });
                }
                if (mensaje["action"] == 'notification-timeout-cancel') {
                    reiniciarTemporizador();
                    Swal.close();
                }

            }
        });
    });



    function page1_llamar() {
        $('#page1_container').hide();
        $('#page_llamada_container').show();
        $('#page2_container').hide();
        $('#page3_container').hide();
    }

    function page11_llamar() {
        miVueApp.llamada_iniciar();
    }

    function llamada_inversa(sipIdentityDestino) {
        miVueApp.llamada_inversa_iniciar(sipIdentityDestino);
    }

    function page2_colgar() {
        miVueApp.llamada_colgar();
    }
</script>

<script>
    function updateTime() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        /*
        let amPm = 'am';
        if (hours > 12) {
            hours -= 12;
            amPm = 'pm';
        }
        if (hours === 0) {
            hours = 12;
        }
        */

        const timeString = `${hours}:${minutes} `;

        const day = now.toLocaleDateString('es-ES', {
            day: 'numeric'
        });
        const month = now.toLocaleDateString('es-ES', {
            month: 'long'
        });

        const dateString = `${day} ${month.toUpperCase()}`;

        $('#reloj_header').html(timeString);
        $('#fecha_header').html(dateString);
    }

    function startClock() {
        setInterval(updateTime, 1000);
    }
    startClock();
</script>
