<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    @include('partials.styles')
    
    @stack('styles')
</head>
<body class="@yield('body-class')">
    @yield('content')
    
    @include('partials.scripts')
    
    @stack('scripts')
</body>
</html>