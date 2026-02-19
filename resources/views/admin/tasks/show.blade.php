@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Task Details</h2>
            <p class="text-muted small">View all task information and discussions</p>
        </div>

        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Tasks
        </a>
    </div>

    <!-- Task Overview Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body px-4 py-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <!-- Left -->
                <div>
                    <h4 class="fw-bold mb-2">
                        <i class="bi bi-check2-square text-primary me-2"></i>
                        {{ $task->title }}
                    </h4>
                    <p class="text-muted mb-2">{{ $task->description }}</p>
                </div>

                <!-- Right Status Badges -->
                <div class="text-end">
                    @php
                        $statusColors = [
                            'pending' => 'secondary',
                            'in_progress' => 'primary',
                            'review' => 'warning',
                            'hold' => 'dark',
                            'completed' => 'success',
                        ];

                        $priorityColors = [
                            'low' => 'success',
                            'medium' => 'warning',
                            'high' => 'danger',
                            'urgent' => 'dark',
                        ];
                    @endphp

                    <span class="badge bg-{{ $statusColors[$task->status] ?? 'secondary' }} px-3 py-2 me-2">
                        {{ ucfirst(str_replace('_',' ', $task->status)) }}
                    </span>

                    <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }} px-3 py-2">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
            </div>

            <hr>

            <!-- Task Meta Info -->
            <div class="row g-3 small">
                <div class="col-md-3">
                    <div class="border rounded-3 p-3 bg-light">
                        <strong><i class="bi bi-folder me-1"></i> Project: </strong>
                        {{ $task->project->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded-3 p-3 bg-light">
                        <strong><i class="bi bi-person me-1"></i> Assigned To:</strong>
                        {{ $task->user->name ?? 'Unassigned' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded-3 p-3 bg-light">
                        <strong><i class="bi bi-calendar me-1"></i> Due Date: </strong>
                        {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded-3 p-3 bg-light">
                        <strong><i class="bi bi-clock-history me-1"></i> Created</strong>
                        {{ $task->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Comments Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-chat-dots text-primary me-2"></i>
                Task Discussion
            </h5>
        </div>

        <div class="card-body px-4 py-3">

            <!-- Comment Form -->
            <form method="POST" action="{{ route('tasks.comments.store', $task) }}" enctype="multipart/form-data" id="commentForm">
                @csrf
                <div class="row g-3 align-items-end">

                    <div class="col-12">
                        <textarea name="comment" rows="3"
                                  class="form-control form-control-sm"
                                  placeholder="Write a comment..." required></textarea>
                    </div>

                    <div class="col-md-3">
                        <input type="file" name="file" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <input type="file" name="audio" accept="audio/*" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitComment">
                            <i class="bi bi-send me-1"></i> Comment
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Comments List -->
    @foreach($task->comments as $comment)
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body px-4 py-3">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">
                    <i class="bi bi-person-circle text-primary me-1"></i>
                    {{ $comment->user->name }}
                </div>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>

            <p class="mb-2">{{ $comment->comment }}</p>

            @if($comment->file_path)
                <a href="{{ asset('storage/'.$comment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="bi bi-paperclip"></i> Attachment
                </a>
            @endif

            @if($comment->audio_path)
                <div class="mt-2">
                    <audio controls class="w-100 rounded border">
                        <source src="{{ asset('storage/'.$comment->audio_path) }}">
                    </audio>
                </div>
            @endif

        </div>
    </div>
    @endforeach

</div>

<!-- Prevent Double Submit -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    const submitComment = document.getElementById('submitComment');

    if(commentForm && submitComment) {
        commentForm.addEventListener('submit', () => {
            submitComment.disabled = true;
        });
    }
});
</script>

@endsection
