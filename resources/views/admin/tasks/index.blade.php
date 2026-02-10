@extends('layouts.admin')
@section('content')

<div class="container mt-5">
    <!-- Header: Title + Add User button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Task</h2>
        <a href="/admin/tasks/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Task
        </a>
    </div>

    <!-- Task Table Card -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
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
                    <td class="fw-medium">{{ $task->title }}</td>
                    <td class="fw-medium">{{ $task->project->name }}</td>
                    <td>{{ $task->user->name }}</td>
                    <td>{{ ucfirst(str_replace('_',' ', $task->status)) }}</td>
                    <!-- Due Date -->
                    <td>
                        <span class="text-muted">
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        <a href="{{ route('admin.tasks.edit', $task->id) }}"
                            class="btn btn-sm btn-outline-warning me-1">
                            <i class="bi bi-pencil-fill"></i>
                        </a>

                        <a href="{{ route('admin.tasks.show', $task->id) }}"
                            class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-eye-slash-fill"></i>
                        </a>

                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#globalDeleteModal"
                                data-action="{{ route('admin.tasks.destroy', $task->id) }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            No Task found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<!-- Bootstrap Icons (required for pencil/trash/add icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
