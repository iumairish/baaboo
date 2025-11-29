@extends('layouts.base')

@section('body-class', 'auth-layout')

@section('content')
    <div class="auth-wrapper">
        @include('partials.alerts')
        
        @yield('page-content')
    </div>
@endsection