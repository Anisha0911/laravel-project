@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- ================= HEADER ================= -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Task Details</h2>
            <p class="text-muted small mb-0">View task information and updates</p>
        </div>

        <a href="{{ route('user.tasks.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left"></i> Back to Tasks
        </a>
    </div>

@php
    $statusMap = [
        'pending' => ['secondary', 'bi-clock'],
        'in_progress' => ['primary', 'bi-arrow-repeat'],
        'review' => ['warning', 'bi-eye'],
        'hold' => ['dark', 'bi-pause-circle'],
        'completed' => ['success', 'bi-check-circle'],
    ];

    $priorityMap = [
        'low' => ['success', 'bi-arrow-down'],
        'medium' => ['warning', 'bi-dash-circle'],
        'high' => ['danger', 'bi-arrow-up'],
        'urgent' => ['dark', 'bi-exclamation-triangle'],
    ];

    $statusColor = $statusMap[$task->status][0] ?? 'secondary';
    $statusIcon  = $statusMap[$task->status][1] ?? 'bi-question-circle';

    $priorityColor = $priorityMap[$task->priority][0] ?? 'secondary';
    $priorityIcon  = $priorityMap[$task->priority][1] ?? 'bi-dash';
@endphp

<!-- ================= TASK DETAILS CARD ================= -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

        <!-- Title + Status -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
            <h4 class="fw-bold text-dark">{{ $task->title }}</h4>

            <div class="d-flex align-items-center gap-2">
                <strong class="small">Status:</strong>

                <span id="statusView">
                    <span class="badge bg-{{ $statusColor }} px-3 py-2 rounded-pill">
                        <i class="bi {{ $statusIcon }}"></i>
                        {{ ucfirst(str_replace('_',' ', $task->status)) }}
                    </span>

                    <i class="bi bi-pencil-square ms-2 text-primary"
                       style="cursor:pointer"
                       onclick="toggleStatusEdit()"></i>
                </span>
            </div>
        </div>

        <!-- Status Edit -->
        <form id="statusEditForm"
              method="POST"
              action="{{ route('user.user.tasks.show', $task->id) }}"
              class="d-none mb-3">
            @csrf
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <select name="status" class="form-select form-select-sm w-auto rounded-pill">
                    @foreach(['pending','in_progress','review','hold','completed'] as $s)
                        <option value="{{ $s }}" {{ $task->status === $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ', $s)) }}
                        </option>
                    @endforeach
                </select>

                <button class="btn btn-sm btn-success rounded-pill px-3">Save</button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                        onclick="toggleStatusEdit()">
                    Cancel
                </button>
            </div>
        </form>

        <hr class="my-4">

        <!-- INFO GRID -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="small text-muted">Project</div>
                <div class="fw-semibold fs-6">{{ $task->project->name ?? '—' }}</div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Assigned To</div>
                <div class="fw-semibold fs-6">{{ $task->user->name }}</div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Created At</div>
                <div class="fw-semibold fs-6">{{ $task->created_at?->format('d M Y') ?? 'N/A' }}</div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Due Date</div>
                <div class="fw-semibold fs-6">{{ $task->due_date?->format('d M Y') ?? 'N/A' }}</div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Priority</div>
                <span class="badge bg-{{ $priorityColor }} px-3 py-2 rounded-pill">
                    <i class="bi {{ $priorityIcon }}"></i>
                    {{ ucfirst($task->priority) }}
                </span>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="mt-4">
            <div class="small text-muted mb-1">Description</div>
            <div class="p-3 bg-light rounded-3">
                {{ $task->description ?? 'No description provided.' }}
            </div>
        </div>

    </div>
</div>

<!-- ================= COMMENTS CARD ================= -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-chat-dots text-primary"></i> Task Updates
        </h5>

        <form method="POST" action="{{ route('tasks.comments.store', $task) }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <textarea name="comment" rows="3" class="form-control rounded-3" placeholder="Write a comment..." required></textarea>
                </div>

                <div class="col-md-2">
                    <input type="file" name="file" class="form-control form-control-sm rounded-pill">
                </div>

                <div class="col-md-2">
                    <input type="file" name="audio" accept="audio/*" class="form-control form-control-sm rounded-pill">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 w-100">
                        <i class="bi bi-send"></i> Add Comment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= COMMENTS LIST ================= -->
@foreach($task->comments as $comment)
<div class="card border-0 shadow-sm rounded-4 mb-2">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between">
            <strong>{{ $comment->user->name }}</strong>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        </div>

        <p class="mb-2 mt-2">{{ $comment->comment }}</p>

        @if($comment->file_path)
            <a href="{{ asset('storage/'.$comment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                📎 Download File
            </a>
        @endif

        @if($comment->audio_path)
            <audio controls class="w-100 mt-2 rounded">
                <source src="{{ asset('storage/'.$comment->audio_path) }}">
            </audio>
        @endif
    </div>
</div>
@endforeach

</div>

<!-- UI Hover Effects -->
<style>
    .card {
        transition: 0.25s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
</style>

<script>
function toggleStatusEdit() {
    document.getElementById('statusView').classList.toggle('d-none');
    document.getElementById('statusEditForm').classList.toggle('d-none');
}
</script>
@endsection
