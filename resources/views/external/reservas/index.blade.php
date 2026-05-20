@extends('layouts.external')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"></script>
    <script src="https://unpkg.com/vuex@4.0.0/dist/vuex.global.js"></script>
    <script src="/vendor/jquery-validate/jquery.validate.min.js"></script>
    <script src="/vendor/jquery-validate/localization/messages_es.min.js"></script>

    <script>
        Vue.config.delimiters = ['@{{ ', ' }}'];
    </script>
    <?php
    //dump($establecimientos);
    ?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">
                <b>CREAR RESERVAS</b>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="false">
                <b>GESTIÓN DE RESERVAS</b>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="import-tab" data-toggle="tab" href="#import" role="tab" aria-controls="import"
                aria-selected="false">
                <b>IMPORTAR RESERVAS</b>
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active bg-white p-3" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-md-12" id="search_app">
                    @include('external.reservas._search')
                </div>

                <div class="col-12 mt-2" id="result_app" style="display:none">
                    @include('external.reservas._result')
                </div>
                @include('external.reservas._client_info')

            </div>
        </div>
        <div class="tab-pane fade bg-white p-3 " id="profile" role="tabpanel" aria-labelledby="profile-tab">

            <div class="row">
                <div class="col-md-12" >
                    @include('external.reservas.edit._search')
                    @include('external.reservas.edit._result')
                </div>
            </div>
        </div>
        <div class="tab-pane fade bg-white p-3 " id="import" role="tabpanel" aria-labelledby="import-tab">

            <div class="row">
                <div class="col-md-12" >
                    @include('external.reservas.import._search')
                </div>
            </div>
        </div>
    </div>
    @include('external.reservas._reservation_info')
@endsection
@section('scripts')
    @parent
    @include('external.reservas._result_js')



    <style>
        #reserva_find label {
            margin-bottom: 0px;
            font-weight: bold;
        }

        .select2-results__option {
            padding-left: 10px;
        }

        .table-padding-2 td{
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
@endsection
