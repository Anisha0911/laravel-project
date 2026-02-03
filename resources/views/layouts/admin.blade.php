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
    </style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">

        <!-- Mobile toggle -->
        <button class="btn btn-outline-secondary d-lg-none"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
            <i class="bi bi-list"></i>
        </button>

        <span class="navbar-brand fw-bold ms-2">Admin Panel</span>

        <div class="ms-auto dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <span>{{ auth()->user()->name }}</span>
            </a>

<ul class="dropdown-menu dropdown-menu-end shadow-sm">
    <li>
        <a href="{{ route('profile.edit') }}" class="dropdown-item">
            <i class="bi bi-person me-2"></i> Edit Profile
        </a>
    </li>

    <li><hr class="dropdown-divider"></li>

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
        <div class="col-lg-2 d-none d-lg-block p-0">
            @include('admin.sidebar')
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-12 col-lg-10 p-4">
            @yield('content')
        </div>

    </div>
</div>

<!-- MOBILE SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Admin Menu</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @include('admin.sidebar')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
