@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- ================= HEADER ================= -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-folder2-open text-primary me-1"></i> {{ $project->name }}
            </h2>
            <p class="text-muted small mb-0">Tasks assigned to you in this project</p>
        </div>

        <a href="{{ route('user.projects.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Projects
        </a>
    </div>

    <!-- ================= TASK LIST ================= -->
    <div class="row g-3">

        @forelse($tasks as $task)
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex flex-column">

                    <!-- Task Title -->
                    <h5 class="fw-bold mb-2">
                        <i class="bi bi-check2-square text-primary me-1"></i>
                        {{ $task->title }}
                    </h5>

                    <!-- Description -->
                    <p class="text-muted small mb-3">
                        {{ Str::limit($task->description, 100) ?? 'No description' }}
                    </p>

                    @php
                        $statusColors = [
                            'pending' => 'secondary',
                            'in_progress' => 'primary',
                            'review' => 'warning',
                            'completed' => 'success',
                            'hold' => 'dark'
                        ];
                    @endphp

                    <!-- Status + Due Date -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-{{ $statusColors[$task->status] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_',' ', $task->status)) }}
                        </span>

                        <small class="text-muted">
                            <i class="bi bi-calendar-event"></i>
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No Due Date' }}
                        </small>
                    </div>

                    <!-- View Button -->
                    <div class="mt-auto">
                        <a href="{{ route('user.tasks.show', $task->id) }}" 
                           class="btn btn-sm btn-primary rounded-pill w-100">
                            <i class="bi bi-eye me-1"></i> View Task
                        </a>
                    </div>

                </div>
            </div>

        </div>
        @empty

        <!-- Empty State -->
        <div class="col-12 text-center py-5">
            <i class="bi bi-list-task fs-1 text-muted"></i>
            <h5 class="mt-2">No tasks assigned</h5>
            <p class="text-muted small">You currently have no tasks in this project.</p>
        </div>

        @endforelse

    </div>

</div>
@endsection
