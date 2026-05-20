@extends('layouts.admin')
@section('content')

    @if(isset($control_actual))
    
        @include('admin.manager.styles')

       
        @include('admin.manager.main')
        @include('admin.manager.scripts')


    @endif

@endsection




