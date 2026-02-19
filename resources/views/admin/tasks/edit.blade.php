@extends('layouts.admin')
@section('content')

<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Task</h2>
            <p class="text-muted small">Update task details and assignments</p>
        </div>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Tasks
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i> Task Details
            </h5>
            <small class="text-muted">Edit task information below</small>
        </div>

        <div class="card-body px-4 pb-4">
            <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST" id="taskForm">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <!-- Task Title -->
                    <div class="col-12">
                        <label for="title" class="form-label fw-semibold">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg" value="{{ $task->title }}" required>
                    </div>

                    <!-- Task Description -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Task Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control form-control-lg" required>{{ $task->description }}</textarea>
                    </div>

                    <!-- Project -->
                    <div class="col-12 col-md-4">
                        <label for="project_id" class="form-label fw-semibold">Project</label>
                        <select name="project_id" id="project_id" class="form-control form-control-lg">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected($project->id==$task->project_id)>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Assign User -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label fw-semibold">Assign User</label>
                        <select name="user_id" id="user_id" class="form-control form-control-lg">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                @if($user->role === 'user')
                                    <option value="{{ $user->id }}" @selected($user->id==$task->user_id)>
                                        {{ $user->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label fw-semibold">Task Status</label>
                        <select name="status" id="status" class="form-control form-control-lg">
                            <option value="">Select Status</option>
                            <option value="pending" @selected($task->status=='pending')>Pending</option>
                            <option value="in_progress" @selected($task->status=='in_progress')>In Progress</option>
                            <option value="review" @selected($task->status=='review')>Review</option>
                            <option value="hold" @selected($task->status=='hold')>Hold</option>
                            <option value="completed" @selected($task->status=='completed')>Completed</option>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div class="col-12 col-md-4">
                        <label for="due_date" class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ $task->due_date?->format('Y-m-d') }}" class="form-control form-control-lg">
                    </div>

                    <!-- Priority -->
                    <div class="col-12 col-md-4">
                        <label for="priority" class="form-label fw-semibold">Task Priority</label>
                        <select name="priority" id="priority" class="form-control form-control-lg">
                            <option value="">Select Priority</option>
                            <option value="low" @selected($task->priority=='low')>Low</option>
                            <option value="medium" @selected($task->priority=='medium')>Medium</option>
                            <option value="high" @selected($task->priority=='high')>High</option>
                            <option value="urgent" @selected($task->priority=='urgent')>Urgent</option>
                        </select>
                    </div>

                    <!-- Creation Date -->
                    <div class="col-12 col-md-4">
                        <label for="created_date" class="form-label fw-semibold">Creation Date</label>
                        <input type="date" name="created_date" id="created_date" value="{{ $task->created_date?->format('Y-m-d') }}" class="form-control form-control-lg">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 mt-4 d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitTask">
                            <i class="bi bi-check-circle me-1"></i> Save Task
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

<!-- ================= JS FIXES ================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Voice Recording Buttons
    const recordBtn   = document.getElementById('recordBtn');
    const preview     = document.getElementById('preview');
    const commentForm = document.getElementById('commentForm');

    if (recordBtn && preview && commentForm) {
        let mediaRecorder, audioChunks = [], isRecording = false, stream;

        recordBtn.onclick = async () => {

            if (!isRecording) {
                stream = await navigator.mediaDevices.getUserMedia({ audio:true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.start();

                isRecording = true;
                recordBtn.innerText = '⏹️ Stop Recording';
                return;
            }

            mediaRecorder.stop();
            isRecording = false;

            mediaRecorder.onstop = () => {
                const blob = new Blob(audioChunks, { type:'audio/webm' });
                preview.src = URL.createObjectURL(blob);
                preview.classList.remove('d-none');

                const file = new File([blob], 'voice.webm', { type:'audio/webm' });
                const dt = new DataTransfer();
                dt.items.add(file);

                // Remove old audio input
                const old = commentForm.querySelector('input[name="audio"]');
                if (old) old.remove();

                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'audio';
                input.files = dt.files;

                commentForm.appendChild(input);
                stream.getTracks().forEach(t => t.stop());
                recordBtn.innerText = '🎙️ Start Recording';
            };
        };
    }

    // Prevent double submit
    const taskForm = document.getElementById('taskForm');
    const submitTask = document.getElementById('submitTask');
    if (taskForm && submitTask) {
        taskForm.addEventListener('submit', () => {
            submitTask.disabled = true;
        });
    }

});
</script>

@endsection
