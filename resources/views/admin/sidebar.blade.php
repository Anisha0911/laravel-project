<div class="d-flex flex-column h-100">

    <div class="sidebar-header">
        <span>Admin Panel</span>
    </div>

    <ul class="nav flex-column p-2 flex-grow-1">

        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span> Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->is('admin/projects*') ? 'active' : '' }}">
                <i class="bi bi-kanban"></i>
                <span> Projects</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.tasks.index') }}" class="nav-link {{ request()->is('admin/tasks*') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                <span> Tasks</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span> Users</span>
            </a>
        </li>

    </ul>

    <div class="sidebar-footer">
        <small>Logged in as</small><br>
        <strong>{{ auth()->user()->name }}</strong>
    </div>

</div>
