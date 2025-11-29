@extends('layouts.base')

@section('body-class', 'admin-layout')

@section('content')
    @include('partials.admin.navbar')
    
    <div class="main-content">
        <div class="container-fluid">
            @include('partials.alerts')
            
            @yield('page-content')
        </div>
    </div>
@endsection