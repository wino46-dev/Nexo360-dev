@extends('layouts.totem')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                @if(!isset($grabador->id))
                    <div class="card" style="padding: 20px;color: red;margin-bottom: 20px">
                        <h4><b>ATENCIÓN: </b> No se ha podido encontrar la configuración de Grabación de tarjetas para este Totem</h4>
                    </div>
                @endif
                @if(!isset($tpv->id))
                        <div class="card" style="padding: 20px;color: red;margin-bottom: 20px">
                            <h4><b>ATENCIÓN: </b> No se ha podido encontrar la configuración de Redsys para este Totem</h4>
                        </div>
                @endif

                @if(isset($caller->id))
                    @include('frontend.home.vue.callVue')
                @else
                    <div class="card" style="padding: 20px;color: red">
                        <h4><b>ATENCIÓN: </b> No se ha podido encontrar la configuración de video para este totem</h4>
                    </div>
                @endif


                @include('frontend.home.notifyConexion')
                @include('frontend.home.screenCamera')
                @include('frontend.home.signature')
                @include('frontend.home.spinner')
                @include('frontend.home.tourGalery')
                


            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('frontend.home.scripts')
@endsection




