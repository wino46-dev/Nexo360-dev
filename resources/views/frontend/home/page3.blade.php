
<div class="row" id="page3_container" style="display:none">

    <div class="col-12 px-0">

        @if(! empty($caller->id))

            <div class="tablet-two-col">
                <div class="tablet-col">
                    @include('frontend.home.vue.callVue')
                </div>
                <div class="tablet-col">
                    <div class="page3_reserva border-top" style="position:relative">
                        <div class="centrar_vertical_container p-3">
                            @include('frontend.home.notifyConexion')
                            <div class="text-center">
                                @include('frontend.home.reservationInfo')
                            </div>
                        </div>
                        @include('frontend.home.screenCamera')
                    </div>
                </div>
            </div>

            <div class="page3_gallery border-top" style="position:relative">
                @include('frontend.home.galery2')
            </div>

        @else
            <div class="card" style="padding: 20px; color: red">
                <h4><b>ATENCIÓN: </b> No se ha podido encontrar la configuración de video para este totem</h4>
            </div>
        @endif
    </div>
</div>
