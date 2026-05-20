@extends('layouts.external')
@section('content')

    @if(isset($control_actual))

        @include('external.manager.styles')


        @include('external.manager.main')
        @include('external.manager.scripts')


    @endif

@endsection




