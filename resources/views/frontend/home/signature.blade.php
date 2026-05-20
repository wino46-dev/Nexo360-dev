<div class="modal" tabindex="-1" id="firma_modal" style="z-index:5000">
    <div class="modal-dialog modal-dialog-centered" style="max-width:95% !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Panel de firma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="firma_parte_viajero_header">

                </div>
                <br />

                <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
                <form id="solicitudFirma">
                    <div style="width:100%;margin-top:20px;text-align: center;" class="accept_checkin_class">
                        Marque con una cruz para aceptar (imprescindible para acceder al establecimiento)
                        <input type="checkbox" id="accept_checkin" />
                    </div>
                    <div style="width:90%;margin-top:20px;text-align: center; font-size:90%; ">
                        Autorizo a la empresa {{ $establecimiento->sociedad->nombre }}, compuesto por
                        {{ $establecimiento->nombre }}, a la utilización de mis datos personales para la realización de
                        acciones comerciales propias y de terceros ..
                        <label>SI <input type="radio" name="accept_personal_data" value="1" checked /></label>
                        &nbsp;&nbsp;&nbsp;
                        <label>NO <input type="radio" name="accept_personal_data" value="0 " /></label>
                    </div>
                    <div style="text-align: center; margin-top:20px">
                        <b>FIRMA DEL CLIENTE</b>
                        <input type="hidden" id="respuesta_texto" name="respuesta_texto">
                        <div class="wrapper">
                            <canvas id="signature-pad" class="signature-pad rounded" width=600 height=200></canvas>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-secondary px-5"
                                onclick="signatura_clear()">Borrar</button>
                            <button type="button" class="btn btn-success px-5"
                                onclick="signatura_send()">Enviar</button>
                        </div>
                    </div>
                </form>

                <div id="firma_parte_viajero_footer" class="mt-3">
                    {{ $sociedad->parte_viajero_html }}
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });

    function signatura_send() {
        if (!$('#accept_checkin').is(':checked')) {


            Swal.fire({
                //position: 'top-end',
                icon: 'error',
                //title: 'Pago Autorizado',
                html: 'Por favor Acepte los terminos',
                showConfirmButton: true,                
                didOpen: (popup) => {
                    popup.style.zIndex = '6050'; // Ajusta el z-index
                }
            })

            $('.accept_checkin_class').css('color', 'red');
            return false;
        }

        if (signaturePad.isEmpty()) {
            
            Swal.fire({                
                icon: 'error',                
                html: 'Por favor proporcione una firma',
                showConfirmButton: true,                
                didOpen: (popup) => {
                    popup.style.zIndex = '6050'; // Ajusta el z-index
                }
            })
            return false;
        }
        let sign_data = signaturePad.toDataURL('image/png');
        $('#respuesta_texto').val(sign_data);
        $('#solicitudFirma').submit();
        $('.accept_checkin_class').css('color', 'black');

        console.log(sign_data);

    }

    function signatura_clear() {
        signaturePad.clear();
    }

    $(document).ready(function() {



        $('#solicitudFirma').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/evento-home-totems') }}/" + id_evento_firma,
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,

                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                data: new FormData(this),
                success: function(response) {
                    signatura_clear();
                    console.log(response)
                    //$('#successMsg').show();
                    //$("<p>" + response['success'] + "</p>").appendTo("#successMsg");
                    //$('#successImg').show();
                    //$("#solicitudfirmadiv").hide();
                    // $("#solicitudFirmah5").hide();
                    //$(".cajaFirma").hide();
                    $('#firma_modal').modal('hide');

                    $('.page3_gallery').show();
                    $(".centrar_vertical_container").show();

                    Swal.fire({
                        title: 'Firma capturada correctamente....!',
                        html: '',
                        timer: 2000,
                        timerProgressBar: true,

                    }).then((result) => {

                    })

                },
                error: function(response) {
                    $('#signErrorMsg').text(response);
                    $('#firma_modal').modal('hide');
                },
            });
        });
    });
</script>

<div class="card cajaFirma mx-4" id="cajaFirma" style="margin-top: 50px;display: none; z-index:5">
    <div class="card-header">
        <div class="alert alert-success" role="alert" id="successMsg" style="display: none;">
        </div>
        <div id="successImg" style="display: none;">

        </div>
        <h5 id="solicitudFirmah5"></h5>

    </div>

    <div class="card-body" id="solicitudfirmadiv">

    </div>
</div>
<style>
    .signature-pad {
        border: 1px solid #CCC;
    }

    input[type="checkbox"] {
        transform: scale(2);
        /* Cambia el tamaño aquí, 1.5 es un 150% más grande */
        -webkit-transform: scale(2);
        /* Para compatibilidad con navegadores webkit */
        margin: 10px;
        /* Ajusta el margen si es necesario */
    }

    input[type="radio"] {
        transform: scale(2);
        /* Cambia el tamaño aquí, 1.5 es un 150% más grande */
        -webkit-transform: scale(2);
        /* Para compatibilidad con navegadores webkit */
        margin: 10px;
        /* Ajusta el margen si es necesario */
    }
    .swal2-container{
        z-index: 6040 !important;
    }
</style>
