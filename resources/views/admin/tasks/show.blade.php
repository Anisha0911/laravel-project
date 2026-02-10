@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Task Details</h2>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tasks
        </a>
    </div>

    <!-- Task Details Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4>{{ $task->title }}</h4>
            <p><strong>Project:</strong> {{ $task->project->name }}</p>
            <p><strong>Assigned To:</strong> {{ $task->user->name }}</p>
            <!-- <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p> -->
             @php
    // Status Colors
    $statusColors = [
        'pending' => 'secondary',
        'in_progress' => 'primary',
        'review' => 'warning',
        'hold' => 'dark',
        'completed' => 'success',
    ];

    // Priority Colors
    $priorityColors = [
        'low' => 'success',
        'medium' => 'warning',
        'high' => 'danger',
        'urgent' => 'dark',
    ];
@endphp

<p>
    <strong>Status:</strong> 
    <span class="badge bg-{{ $statusColors[$task->status] ?? 'secondary' }}">
        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
    </span>
</p>

<p>
    <strong>Priority:</strong> 
    <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}">
        {{ ucfirst($task->priority) }}
    </span>
</p>
<!-- ------------------------------------- -->
            <p><strong>Due Date:</strong> {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}</p>
            <p>{{ $task->description }}</p>
        </div>
    </div>

    <!-- Task Comments Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4>Task Discussion</h4>

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
@endsection
