<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventrior</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/inventrior.css') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark inventrior-bg shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">Inventrior</a>

        <div class="ms-auto">
            @auth
                <span class="text-light me-3">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>
                <a href="{{ route('register.company') }}" class="btn btn-light btn-sm">Register</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container" style="padding-top:100px;">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @yield('content')
</div>

<footer class="py-4 inventrior-bg text-center text-light mt-5">
    <div class="container">
        <p class="mb-1">© {{ date('Y') }} Inventrior • All Rights Reserved</p>
        <small>Enterprise Inventory Management System</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>