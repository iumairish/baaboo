@extends('layouts.customer')

@section('title', 'Submit Support Ticket')

@section('page-content')
    <div class="container">
        <div class="ticket-form-container">
            @include('partials.tickets.form-header')
            
            @include('partials.form-errors')
            
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                
                @include('partials.tickets.form-fields')
                
                <button type="submit" class="btn btn-primary btn-submit">
                    <i class="fas fa-paper-plane me-2"></i> Submit Ticket
                </button>
            </form>
        </div>
    </div>
@endsection