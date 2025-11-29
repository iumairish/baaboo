@extends('layouts.admin')

@section('title', 'All Tickets')

@section('page-content')
<div class="row">
    <div class="col-12">
        <div class="card">
            @include('partials.admin.tickets.list-header', ['count' => $tickets->count()])
            
            <div class="card-body">
                @include('partials.admin.tickets.table', ['tickets' => $tickets])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('partials.admin.tickets.datatable-script')
@endpush