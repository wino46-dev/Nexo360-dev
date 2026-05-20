
<script src="/vendor/jquery-validate/jquery.validate.min.js"></script>
<script src="/vendor/jquery-validate/localization/messages_es.min.js"></script>

<div class=" main-manager" style="max-width: 11920px">
    <div class="card">
        <div class="form">
            <div class="left-side rounded-left" >
                <div class="left-heading">
                    <br>
                    <h3>Gestión de Reserva</h3>
                </div>
                <div class="steps-content" style="display: none">
                    <h5>Paso <span class="step-number">1</span></h5>
                </div>
                <ul class="progress-bar">
                    <li id="pushli" style="text-align: left !important;padding-bottom: 10px;" class="active">Eventos Push.</li>
                    <li id="imagesli" style="text-align: left !important;padding-bottom: 10px;">Pase de Imágenes.</li>
                    <li id="capturasli" style="text-align: left !important;padding-bottom: 10px">Captura de Documento.</li>
                    <li id="pagosli"  style="text-align: left !important;padding-bottom: 10px">Solicitar Pago de Reserva.</li>
                    <li id="cardsli" style="text-align: left !important;padding-bottom: 10px">Grabación de Tarjeta.</li>
                    <li id="signsli" style="text-align: left !important;padding-bottom: 10px">Firma parte de Viajero.</li>
                </ul>



            </div>

            <div class="right-side">
                <div id="reservation_detail_container" style="margin:0px 30px; padding:15px" class="border border-success">
                    Seleccione una reserva                    
                </div>
            
                
                <div id="divpushli" class="main active">
                    <div class="text">
                        @if(isset($ayudaStepTotem->eventos_push))
                            <h2>Eventos Push
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-push-modal-lg">Ayuda</button></h2>
                            <div id="modalPush" class="modal fade bd-push-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 20px">
                                            {!! $ayudaStepTotem->eventos_push !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h2>Eventos Push</h2>
                        @endif

                        <div class="buttons" style="float: right">

                        </div>
                    </div>
                    <br><br>
                    @include('admin.manager.create')
                    <br><br>

                </div>
                <div id="divimagesli" class="main">
                    <div class="text">

                        @if(isset($ayudaStepTotem->pase_imagenes))
                            <h2>Selección de Imágenes
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-imagenes-modal-lg">Ayuda</button></h2>
                            <div id="modalImagenes" class="modal fade bd-imagenes-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 20px !important;">
                                            {!! $ayudaStepTotem->pase_imagenes !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h2>Selección de Imágenes</h2>
                        @endif

                        @include('admin.manager.galeryTour')

                    </div>
                    <br><br>

                </div>
                <div id="divcapturasli" class="main">
                    <div class="text">

                        @if(isset($ayudaStepTotem->captura_documentos))
                            <h2>
                                Huéspedes y Documentos
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-modalDocumentos-modal-lg">Ayuda</button>
                            </h2>
                            <div id="modalDocumentos" class="modal fade bd-modalDocumentos-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 20px !important;">
                                            {!! $ayudaStepTotem->captura_documentos !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h2>Huéspedes y Documentos</h2>
                        @endif


                        @include('admin.manager.capturePhoto')
                    </div>

                    <br><br>

                </div>
                <div id="divpagosli" class="main">
                    <div class="text">

                        @if(isset($ayudaStepTotem->pago_reserva))
                            <h2>Pago de Reserva
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-modalPagoReserva-modal-lg">Ayuda</button></h2>
                            <div id="modalPagoReserva" class="modal fade bd-modalPagoReserva-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 20px !important;">
                                            {!! $ayudaStepTotem->pago_reserva !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h2>Pago de Reserva</h2>
                        @endif


                    </div>

                    <br>
                    @include('admin.manager.payment')
                    <br>

                </div>
                <div id="divcardsli" class="main">
                    <div class="text">

                        @if(isset($ayudaStepTotem->grabacion_tarjeta))
                            <h2>Grabación de tarjeta
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-modalGrabacionTarjeta-modal-lg">Ayuda</button></h2>
                            <div id="modalGrabacionTarjeta" class="modal fade bd-modalGrabacionTarjeta-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 20px !important;">
                                            {!! $ayudaStepTotem->grabacion_tarjeta !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h2>Grabación de tarjeta</h2>
                        @endif
                    </div>

                    <br><br>
                    @include('admin.manager.writeCard')
                    <br>

                </div>
                <div id="divsingsli" class="main">
                    <div class="text">

                        @if(isset($ayudaStepTotem->parte_viajero))
                            <h2>Solicitar Firma de Documento
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".bd-modalParteViajeros-modal-lg">Ayuda</button></h2>
                            <div id="modalParteViajeros" class="modal fade bd-modalParteViajeros-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 20px !important;">
                                            {!! $ayudaStepTotem->parte_viajero !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <h2>Solicitar Firma de Documento</h2>
                        @endif

                    </div><br><br>
                    @include('admin.manager.captureSign')


                </div>



            </div>
        </div>
    </div>
</div>



