@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Task Details</h2>
        <a href="{{ route('user.tasks.index') }}" class="btn btn-outline-secondary">
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
<!-- Task Details Card -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- {{-- Title --}} -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
            <h4 class="fw-bold mb-2 mb-md-0">{{ $task->title }}</h4>

            <!-- {{-- Status --}} -->
            <div>
               <strong>Status: </strong> <span id="statusView">
                    <span class="badge bg-{{ $statusColor }} px-3 py-2">
                        <i class="bi {{ $statusIcon }}"></i>
                        {{ ucfirst(str_replace('_',' ', $task->status)) }}
                    </span>

                    <i class="bi bi-pencil-square ms-2 text-primary"
                       style="cursor:pointer"
                       onclick="toggleStatusEdit()"></i>
                </span>
            </div>
        </div>

        <!-- {{-- Status Edit Form (hidden) --}} -->
        <form id="statusEditForm"
              method="POST"
              action="{{ route('user.user.tasks.show', $task->id) }}"
              class="d-none mb-3">
            @csrf

            <div class="d-flex flex-wrap gap-2 align-items-center">
                <select name="status" class="form-select form-select-sm w-auto">
                    @foreach(['pending','in_progress','review','hold','completed'] as $s)
                        <option value="{{ $s }}" {{ $task->status === $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ', $s)) }}
                        </option>
                    @endforeach
                </select>

                <button class="btn btn-sm btn-success">Save</button>
                <button type="button"
                        class="btn btn-sm btn-outline-secondary"
                        onclick="toggleStatusEdit()">
                    Cancel
                </button>
            </div>
        </form>

        <hr>

        {{-- Info Grid --}}
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="small text-muted">Project</div>
                <div class="fw-semibold">{{ $task->project->name ?? '—' }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="small text-muted">Assigned To</div>
                <div class="fw-semibold">{{ $task->user->name }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="small text-muted">Created At</div>
                <div class="fw-semibold">
                    {{ $task->created_at?->format('d M Y') ?? 'N/A' }}
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="small text-muted">Due Date</div>
                <div class="fw-semibold">
                    {{ $task->due_date?->format('d M Y') ?? 'N/A' }}
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="small text-muted">Priority</div>
                <span class="badge bg-{{ $priorityColor }}">
                    <i class="bi {{ $priorityIcon }}"></i>
                    {{ ucfirst($task->priority) }}
                </span>
            </div>
        </div>

        {{-- Description --}}
        <div class="mt-4">
            <div class="small text-muted mb-1">Description</div>
            <div class="p-3 bg-light rounded">
                {{ $task->description ?? 'No description provided.' }}
            </div>
        </div>

    </div>
</div>

    <!-- Task Comments Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4> <i class="bi bi-list-check fs-0 text-success"></i> Task Updates</h4>

            <form method="POST" action="{{ route('tasks.comments.store', $task) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <textarea name="comment" rows="3" class="form-control" placeholder="Write a comment..." required></textarea>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="file" name="audio" accept="audio/*" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-primary">Add Comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

   
    
    <!-- Display Comments -->
    @foreach($task->comments as $comment)
    <div class="card mb-2">
        <div class="card-body">
            <strong>{{ $comment->user->name }}</strong>
            <small>({{ $comment->created_at->diffForHumans() }})</small>
            <p>{{ $comment->comment }}</p>

            @if($comment->file_path)
                <a href="{{ asset('storage/'.$comment->file_path) }}" target="_blank">📎 Download File</a>
            @endif

            @if($comment->audio_path)
                <audio controls>
                    <source src="{{ asset('storage/'.$comment->audio_path) }}">
                </audio>
            @endif
        </div>
    </div>
    @endforeach
</div>
<script>
function toggleStatusEdit() {
    document.getElementById('statusView').classList.toggle('d-none');
    document.getElementById('statusEditForm').classList.toggle('d-none');
}
</script>
@endsection


