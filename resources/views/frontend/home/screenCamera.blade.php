<div class="card cajaScreenCamera mx-4" id="cajaScreenCamera" style="margin-top: 50px;display:none; z-index:5">
    <div class="card-header">
        <div role="alert" id="successMsgCamera" style="display: none;" >
        <p>Un momento....Estamos procesando su imágen</p>
        </div>
        <div class="alert alert-error" role="alert" id="ErrorMsgCamera" style="display: none;" >

        </div>


        <h5 id="fotoDocumento"></h5>

    </div>

    <div class="card-body" id="divCameraScreen">
            <form id="formCamera">
                <div class="row">
                    <div class="col-7">
                        <input type="hidden" name="respuesta_texto" id="idImgForm" value="Undefined">
                        <div id="my_camera"></div>

                        <br/>
                        <div>
                            <?php if($sociedad->show_btns_capture_document == 'front' || $sociedad->show_btns_capture_document == 'both'){ ?>
                                <input  style="margin-left: 10px;" class="btn btn-success" type=button value="Capturar Documento" onClick="take_snapshot()">
                                <button class="btn btn-warning" id="validar_documento_btn" style="width:120px">Validar</button>
                            <?php } ?>
                            <input type="hidden" name="image" class="image-tag">
                        </div>

                    </div>
                    <div class="col-5">
                        <div id="results"></div>
                    </div>

                </div>
            </form>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

            <script language="JavaScript">
                Webcam.set({
                    width: 640,
                    height: 480,
                    image_format: 'jpeg',
                    jpeg_quality: 100
                });

                function isCameraActive() {
                    return !!Webcam.stream;
                }

                function openCamera() {
                    if (isCameraActive()) {
                        console.log('[CAM] Ya estaba abierta, no hago nada.');
                        return;
                    }
                    console.log('[CAM] Abriendo cámara…');
                    Webcam.set({
                        width: 640,
                        height: 480,
                        image_format: 'jpeg',
                        jpeg_quality: 100
                    });
                    Webcam.attach('#my_camera');
                }

                function closeCamera() {
                    if (!isCameraActive()) {
                        console.log('[CAM] Ya estaba cerrada, no hago nada.');
                        return;
                    }
                    console.log('[CAM] Cerrando cámara…');
                    Webcam.reset();              // detiene el stream
                    document.getElementById('results').innerHTML = '';  // opcional: limpia la vista previa
                }

                //Webcam.attach( '#my_camera' );

                //openCamera();
                //closeCamera();

                function take_snapshot() {
                    Webcam.snap( function(data_uri) {
                        $(".image-tag").val(data_uri);
                        document.getElementById('results').innerHTML = '<img style="float: right" width="70%" src="'+data_uri+'"/>';
                        imgUri = document.getElementById("idImgForm");
                        imgUri.value = data_uri;
                    } );
                }
                function take_snapshot_auto() {
                    Webcam.snap( function(data_uri) {
                        $(".image-tag").val(data_uri);
                        document.getElementById('results').innerHTML = '<img style="float: right" width="70%" src="'+data_uri+'"/>';
                        imgUri = document.getElementById("idImgForm");
                        imgUri.value = data_uri;

                        setTimeout(function() {
                            $('#formCamera').trigger('submit');
                        }, 100);
                    } );
                }

            </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript">
            $(document).ready(function(){

                $('#formCamera').on('submit',function(e){
                    e.preventDefault();
                    let image = $('#image-tag').val();

                    $('#validar_documento_btn').prop('disabled', true).html('Enviando..');

                    $.ajax({
                        url: "{{ url('/evento-home-totems') }}/"+id_evento_foto,
                        method: 'POST',
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val(),'respuesta_texto':image},
                        data:new FormData(this),
                        success:function(response){

                            $('#validar_documento_btn').prop('disabled', false).html('Validar');

                            Swal.fire({
                                title: 'Documento capturado correctamente....!',
                                html: '',
                                timer: 3000,
                                timerProgressBar: true,

                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log('Cerrando Alerta')
                                }
                            })
                            $(".cajaScreenCamera").hide();
                            $(".divcarousel").show();

                        },
                        error: function(response) {
                            $('#validar_documento_btn').prop('disabled', false).html('Validar');
                            $('#successMsgCamera').hide();
                            $('#ErrorMsgCamera').text(response['error']);
                            $('#ErrorMsgCamera').show();
                        },
                    });
                });
            });
        </script>


    </div>
</div>
