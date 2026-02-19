@extends('layouts.admin')
@section('content')

<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Projects</h2>
            <p class="text-muted small">Manage all projects in your system</p>
        </div>

        <a href="/admin/projects/create" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Project
        </a>
    </div>

    <!-- Projects Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
<div class="card-header bg-white border-0 px-4 pt-4 pb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

        <!-- Left: Title -->
        <div>
            <h5 class="fw-bold mb-1 d-flex align-items-center">
                <i class="bi bi-kanban-fill text-primary me-2"></i>
                Project List
            </h5>
            <small class="text-muted">
                All active and completed projects
            </small>
        </div>

        <!-- Right: Search -->
        <form method="GET" action="{{ route('admin.projects.index') }}" class="w-auto">
            <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-0 shadow-none"
                       placeholder="Search tasks...">
            </div>
        </form>  

    </div>
</div>


        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Assigned To</th>
                            <th>Duration</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <!-- Project Name -->
                            <td class="fw-semibold">
                                <i class="bi bi-folder-fill text-primary me-1"></i>
                                {{ $project->name }}
                            </td>

                            <!-- Description -->
                            <td class="text-muted small">
                                {{ Str::limit($project->description, 60) }}
                            </td>

                            <!-- Assigned User -->
                            <td>
                                @if($project->user)
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="bi bi-person-circle me-1"></i> {{ $project->user->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Unassigned</span>
                                @endif
                            </td>

                            <!-- Duration -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}
                                    →
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'N/A' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                <a href="{{ route('admin.projects.edit', $project->id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-circle me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#globalDeleteModal"
                                        data-action="{{ route('admin.projects.destroy', $project->id) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-folder-x fs-3"></i>
                                <p class="mb-0">No projects found</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
<!-- Instant Change While Typing -->
<script>
document.querySelector('input[name="search"]').addEventListener('input', function() {
    this.form.submit();
});
</script>
<!-- Wait For 500 Seconds to Change After Typing Ends... -->
<!-- <script>
let timer;
const searchInput = document.querySelector('input[name="search"]');

searchInput.addEventListener('input', function() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        this.form.submit();
    }, 500); // waits 500ms after typing stops
});
</script> -->

@endsection
