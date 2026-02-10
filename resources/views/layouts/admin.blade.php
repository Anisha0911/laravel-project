<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar-desktop {
            min-height: 100vh;
        }
        .navbar .bi-bell:hover {
            color: #0d6efd; /* Bootstrap primary color */
            transform: scale(1.2);
            transition: transform 0.2s, color 0.2s;
        }
        .alert-dismissible {
            transform: translateY(-20px);
            opacity: 0;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        .alert-dismissible.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>

<!-- TOP NAVBAR -->
@php
    $panelName = auth()->user()->role === 'admin' ? 'Admin Panel' : 'User Panel';
    $profileRoute = auth()->user()->role === 'admin'
        ? route('admin.profile.edit')
        : route('user.profile.edit');

    $dashboardRoute = auth()->user()->role === 'admin'
        ? route('admin.dashboard')
        : route('user.dashboard');
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">

        <!-- Mobile toggle -->
        <button class="btn btn-outline-secondary d-lg-none"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
            <i class="bi bi-list"></i>
        </button>

        <span class="navbar-brand fw-bold ms-2">{{ $panelName }}</span>

        <a href="{{ route('notifications.index') }}" class="position-relative text-dark me-3">
            <i class="bi bi-bell fs-4"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ auth()->user()->unreadNotifications->count() }}
                    <span class="visually-hidden">unread notifications</span>
                </span>
            @endif
        </a>

        <div class="ms-auto dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <span>{{ auth()->user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <!-- Profile Link -->
                <li>
                    <a href="{{ $profileRoute }}" class="dropdown-item">
                        <i class="bi bi-person me-2"></i> Edit Profile
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <!-- Logout -->
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- DESKTOP SIDEBAR -->
        <div class="col-lg-2 d-none d-lg-block p-0 border-end sidebar-desktop">
            @if(auth()->user()->role == 'admin')
                @include('admin.sidebar')
            @else
                @include('user.sidebar')
            @endif
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-12 col-lg-10 p-4">
            @if(session('success'))
                <div id="successAlert" class="alert alert-success alert-dismissible fade position-fixed top-1 start-50 translate-middle-x mt-3 z-index-1050" role="alert" style="min-width: 300px;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div id="errorAlert" class="alert alert-danger alert-dismissible fade position-fixed top-1 start-50 translate-middle-x mt-3 z-index-1050" role="alert" style="min-width: 300px;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

    </div>
</div>

<!-- MOBILE SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">
            @if(auth()->user()->role == 'admin') Admin Menu @else User Menu @endif
        </h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @if(auth()->user()->role == 'admin')
            @include('admin.sidebar')
        @else
            @include('user.sidebar')
        @endif
    </div>
</div>

@include('components.delete-modal')
<script src="{{ asset('js/delete-modal.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
