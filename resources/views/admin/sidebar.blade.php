<aside class="d-flex flex-column bg-white border-end sidebar-desktop" style="height: 100vh;">

    <!-- Top Header -->
    <div class="p-4 border-bottom">
        <h6 class="fw-bold mb-0">Admin Management</h6>
    </div>

    <!-- Navigation Links -->
    <ul class="nav flex-column p-3 gap-1 flex-grow-1">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->is('admin/dashboard') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.projects.index') }}"
            class="nav-link {{ request()->is('admin/projects*') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                <i class="bi bi-kanban me-2"></i> Projects
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.tasks.index') }}"
            class="nav-link {{ request()->is('admin/task*') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                <i class="bi bi-list-task me-2"></i> Task
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->is('admin/users*') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                <i class="bi bi-people me-2"></i> Users
            </a>
        </li>

        <li class="nav-item">
            <a href="#"
               class="nav-link text-dark">
                <i class="bi bi-gear me-2"></i> Settings
            </a>
        </li>

    <!-- Footer / Sticky Bottom -->
    <div class="p-3 border-top text-left mt-auto">
        <small class="text-muted d-block">Logged in as</small>
        <div class="fw-semibold">{{ auth()->user()->name }}</div>
    </div>

</aside>

