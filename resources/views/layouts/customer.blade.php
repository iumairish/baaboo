@extends('layouts.base')

@section('body-class', 'customer-layout')

@section('content')
    <div class="customer-wrapper">
        @include('partials.alerts')
        
        @yield('page-content')
    </div>
@endsection