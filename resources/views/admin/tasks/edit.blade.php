@extends('layouts.admin')
@section('content')

<div class="container mt-5">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Edit Task</h2>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tasks
        </a>
    </div>

    <!-- Edit Task Form -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Task Description</label>
                        <textarea name="description" rows="4" class="form-control" required>{{ $task->description }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-select">
                             <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected($project->id==$task->project_id)>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Assign User</label>
                        <select name="user_id" class="form-select">
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

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Select Task Status</option>
                            <option value="pending" @selected($task->status=='pending')>Pending</option>
                            <option value="in_progress" @selected($task->status=='in_progress')>In Progress</option>
                            <option value="review" @selected($task->status=='review')>Review</option>
                            <option value="hold" @selected($task->status=='hold')>Hold</option>
                            <option value="completed" @selected($task->status=='completed')>Completed</option>
                        </select>
                    </div>

<div class="col-md-4">
    <label class="form-label">Due Date</label>
    <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}" class="form-control">
</div>

<!-- Priority -->
<div class="col-md-4">
    <label class="form-label">Task Priority</label>
    <select name="priority" class="form-select">
        <option value="">Select Priority</option>
        <option value="low" @selected($task->priority=='low')>Low</option>
        <option value="medium" @selected($task->priority=='medium')>Medium</option>
        <option value="high" @selected($task->priority=='high')>High</option>
        <option value="urgent" @selected($task->priority=='urgent')>Urgent</option>
    </select>
</div>

<!-- Created Date -->
<div class="col-md-4">
    <label class="form-label">Task Creation Date</label>
    <input type="date" name="created_date" value="{{ $task->created_date?->format('Y-m-d') }}" class="form-control">
</div>

                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ================= JS FIXES ================= -->

<script>
let mediaRecorder, audioChunks = [], isRecording = false, stream;

const recordBtn   = document.getElementById('recordBtn');
const preview     = document.getElementById('preview');
const commentForm = document.getElementById('commentForm');

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

        // ✅ REMOVE OLD AUDIO INPUT
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

// ✅ PREVENT DOUBLE SUBMIT
commentForm.addEventListener('submit', () => {
    document.getElementById('submitComment').disabled = true;
});
</script>

@endsection
