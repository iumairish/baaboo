@extends('layouts.auth')

@section('title', 'Admin Login')

@section('page-content')
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h2 class="mb-2">Admin Panel</h2>
            <p class="text-muted">Please login to continue</p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                    >
                </div>
                @error('password')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Default: admin@example.com / password</small>
        </div>
    </div>
@endsection