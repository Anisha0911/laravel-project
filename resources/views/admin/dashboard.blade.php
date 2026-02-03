@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Admin Dashboard</h2>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Users</small>
                    <h3 class="fw-bold mb-0">{{ $users->count() }}</h3>
                </div>
                <i class="bi bi-people fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Projects</small>
                    <h3 class="fw-bold mb-0">
                        {{ $projects->count() }}
                    </h3>
                </div>
                <i class="bi bi-kanban fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Tasks</small>
                    <h3 class="fw-bold mb-0">
                        {{ $task->count() }}
                    </h3>
                </div>
                <i class="bi bi-list-task fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Admins</small>
                    <h3 class="fw-bold mb-0">
                        {{ $users->where('role','admin')->count() }}
                    </h3>
                </div>
                <i class="bi bi-shield-lock fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <!-- Completed Projects -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success bg-opacity-10">
            <div class="card-body">
                <small class="text-success fw-semibold">
                    Completed Tasks
                </small>
                <h3 class="fw-bold text-success">
                    {{ $completedTasks }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Pending Projects -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
            <div class="card-body">
                <small class="text-warning fw-semibold">
                    Pending Tasks
                </small>
                <h3 class="fw-bold text-warning">
                    {{ $pendingTasks }}
                </h3>
            </div>
        </div>
    </div>

    <!-- <div class="col-sm-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Normal Users</small>
                    <h3 class="fw-bold mb-0">
                        {{ $users->where('role','user')->count() }}
                    </h3>
                </div>
                <i class="bi bi-person fs-1 text-secondary"></i>
            </div>
        </div>
    </div> -->

</div>

<!-- Users Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
        Users List
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role == 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
