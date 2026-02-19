<div class="d-flex flex-column h-100">

<div class="sidebar-header">
    User Panel
</div>

<ul class="nav flex-column p-2 flex-grow-1">

    <li class="nav-item">
        <a href="{{ route('user.dashboard') }}"
           class="nav-link {{ request()->is('user/dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('user.projects.index') }}"
           class="nav-link {{ request()->is('user/projects*') ? 'active' : '' }}">
            <i class="bi bi-kanban me-2"></i> <span>Projects</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('user.tasks.index') }}"
           class="nav-link {{ request()->is('user/tasks*') ? 'active' : '' }}">
            <i class="bi bi-list-task me-2"></i> <span>Tasks</span>
        </a>
    </li>

</ul>

<div class="sidebar-footer">
    <small>Logged in as</small><br>
    <b>{{ auth()->user()->name }}</b>
</div>
</div>