@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">User Dashboard</h2>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">

    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">All Projects</small>
                    <h3 class="fw-bold mb-0">{{ $projects }}</h3>
                </div>
                <i class="bi bi-kanban fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">All Tasks</small>
                    <h3 class="fw-bold mb-0">{{ $tasks }}</h3>
                </div>
                <i class="bi bi-list-task fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Completed Tasks</small>
                    <h3 class="fw-bold mb-0">{{ $completedTasks }}</h3>
                </div>
                <i class="bi bi-check-circle fs-1 text-success"></i>
            </div>
        </div>
    </div>

</div>

<!-- Optional: My Projects Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
        My Projects
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Project Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myProjects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>
                        <span class="badge {{ $project->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
