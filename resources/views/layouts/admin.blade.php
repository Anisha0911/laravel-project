<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<style>/* Modern Cards */
.modern-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    transition: 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-3px);
}

/* Stat Cards */
.stat-card-modern {
    border-radius: 18px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-card-modern i {
    font-size: 32px;
    opacity: 0.8;
}

/* Timeline */
.timeline-body {
    max-height: 350px;
    overflow-y: auto;
}

.timeline-item {
    padding: 12px;
    border-left: 3px solid #0d6efd;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

/* Dark Mode */
.dark-mode {
    background-color: #121212;
    color: #f1f1f1;
}

.dark-mode .modern-card {
    background-color: #1e1e1e;
    color: #fff;
}

.dark-mode .timeline-item {
    background-color: #2a2a2a;
}

.dark-mode .table {
    color: #fff;
}
</style>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<!-- Dashboard Cards -->
<style>
    .form-control {
    font-size: 14px;  /* normal text size */
    padding: 8px 12px;
}/* Make placeholder text smaller */
::placeholder {
    font-size: 12px !important;   /* change to 11px or 13px if you want */
    color: #9aa0a6;               /* optional: lighter color */
}

/* For Bootstrap inputs & textarea specifically */
.form-control::placeholder {
    font-size: 12px !important;
}


    .topbar .bi-bell:hover {
    color: #0d6efd;
    }
    .badge {
        font-size: 11px;
    }

    .stat-card {
        border-radius: 18px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,.15);
    }
    .stat-card i {
        font-size: 2.5rem;
        opacity: 0.3;
        position: absolute;
        right: 15px;
        bottom: 10px;
    }
    .bg-gradient-users { background: linear-gradient(135deg, #667eea, #764ba2); }
    .bg-gradient-projects { background: linear-gradient(135deg, #43cea2, #185a9d); }
    .bg-gradient-tasks { background: linear-gradient(135deg, #ff758c, #ff7eb3); }
    .bg-gradient-admins { background: linear-gradient(135deg, #f7971e, #ffd200); }
</style>
<style>
body {
    background: #f5f6fa;
    overflow-x: hidden;
}

/* Sidebar */
#sidebar {
    width: 260px;
    background: #1e293b;
    color: white;
    height: 100vh;
    position: fixed;
    transition: 0.3s ease;
    z-index: 1000;
}
.sidebar-footer {
        padding: 4px 22px;
}
#sidebar.collapsed {
    width: 80px;
}

#sidebar .nav-link {
    color: #cbd5e1;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 3px;
}

#sidebar .nav-link.active,
#sidebar .nav-link:hover {
    background: #0d6efd;
    color: white;
}

#sidebar.collapsed span {
    display: none;
}

/* Sidebar header */
.sidebar-header {
    padding: 15px;
    font-weight: bold;
    font-size: 18px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

/* Content */
#content {
    margin-left: 260px;
    transition: 0.3s;
}

#sidebar.collapsed ~ #content {
    margin-left: 80px;
}

/* Top bar */
.topbar {
    background: white;
    padding: 10px 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* Mobile */
@media(max-width: 991px) {
    #sidebar {
        left: -260px;
    }
    #sidebar.show {
        left: 0;
    }
    #content {
        margin-left: 0;
    }
}
@media(max-width: 991px) {
    #sidebar.show {
        box-shadow: 0 0 0 10000px rgba(0,0,0,0.5);
    }
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->
<div id="sidebar">
        <!-- Mobile Close Button -->
    <button id="closeSidebar" class="btn btn-danger d-lg-none m-2">
        <i class="bi bi-x-lg"></i>
    </button>
    @if(auth()->user()->role == 'admin')
        @include('admin.sidebar')
    @else
        @include('user.sidebar')
    @endif
</div>


    <!-- Main Content -->
    <div id="content" class="flex-grow-1">

        <!-- Top Navbar -->
        <div class="topbar d-flex justify-content-between align-items-center">

            <!-- Sidebar Toggle -->
            <button id="toggleSidebar" class="btn btn-outline-primary">
                <i class="bi bi-list"></i>
            </button>

            <div class="d-flex align-items-center gap-3 ms-auto">

                <!-- 🔔 Notifications -->
                <div class="dropdown me-3">
                    <a class="position-relative text-dark dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-4"></i>

                        @if(auth()->user()->unreadNotifications->count())
                            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow p-2" style="width:280px;">
                        <li class="dropdown-header fw-bold">Notifications</li>

                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <li>
                                <a class="dropdown-item small">
                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                </a>
                            </li>
                        @empty
                            <li class="dropdown-item text-muted">No new notifications</li>
                        @endforelse

                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center fw-bold">
                                View All Notifications
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
@if(auth()->user()->role == 'admin')
    <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
@else
    <a class="dropdown-item" href="{{ route('user.profile.edit') }}">
@endif
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
        </div>

        <!-- Page Content -->
        <div class="p-4">
                    
        
        <!-- Success/Error Msg -->
    <!-- Success/Error Toast -->
<div class="position-fixed top-0 start-50 translate-middle-x mt-3 z-index-1050" style="min-width: 320px; max-width: 400px;">

    @if(session('success'))
    <div id="successAlert" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div id="errorAlert" class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

</div>


            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const closeBtn = document.getElementById("closeSidebar");

    // Open Sidebar
    toggleBtn.addEventListener("click", function() {
        if (window.innerWidth > 991) {
            sidebar.classList.toggle("collapsed");
        } else {
            sidebar.classList.add("show");
        }
    });

    // Close Sidebar (Mobile)
    closeBtn.addEventListener("click", function() {
        sidebar.classList.remove("show");
    });

});
// document.addEventListener("click", function(e) {
//     if (window.innerWidth <= 991) {
//         if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
//             sidebar.classList.remove("show");
//         }
//     }
// });
</script>
@include('components.delete-modal')
<script src="{{ asset('js/delete-modal.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
