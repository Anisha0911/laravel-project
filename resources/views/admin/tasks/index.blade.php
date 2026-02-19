@extends('layouts.admin')
@section('content')

<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tasks</h2>
            <p class="text-muted small">Manage all tasks in your system</p>
        </div>

        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary rounded-pill px-3 btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Task
        </a>
    </div>

    <!-- Task Table Card -->
    <div class="card border-0 shadow-sm rounded-4">

        <!-- Card Header -->
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

                <!-- Left Title -->
                <div>
                    <h5 class="fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-list-task text-primary me-2"></i>
                        Task List
                    </h5>
                    <small class="text-muted">All assigned and pending tasks</small>
                </div>

                <!-- Search -->
                <form method="GET" action="{{ route('admin.tasks.index') }}" class="w-auto">
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

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Title</th>
                            <th>Project</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <!-- Task Title -->
                            <td class="fw-semibold">
                                <i class="bi bi-check2-square text-primary me-1"></i>
                                {{ $task->title }}
                            </td>

                            <!-- Project -->
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="bi bi-folder me-1"></i> {{ $task->project->name ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Assigned User -->
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="bi bi-person-circle me-1"></i> {{ $task->user->name ?? 'Unassigned' }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'in_progress' => 'primary',
                                        'completed' => 'success'
                                    ];
                                    $status = $task->status ?? 'pending';
                                @endphp

                                <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_',' ', $status)) }}
                                </span>
                            </td>

                            <!-- Due Date -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-circle me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="{{ route('admin.tasks.show', $task->id) }}"
                                   class="btn btn-sm btn-outline-info rounded-circle me-1">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#globalDeleteModal"
                                        data-action="{{ route('admin.tasks.destroy', $task->id) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-clipboard-x fs-3"></i>
                                <p class="mb-0">No tasks found</p>
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
document.querySelector('input[name="search"]').addEventListener('keyup', function() {
    this.form.submit();
});
</script>
@endsection
