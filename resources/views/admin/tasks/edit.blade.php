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
    
    <!-- Edit Task Form Start -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            

            <!-- Form -->
            <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')<!-- Important for update -->

                <div class="row g-3">
                    <!-- Task Title -->
                    <div class="col-12">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                    </div>

                    <!-- Task Description -->
                    <div class="col-12">
                        <label for="name" class="form-label">Task Description</label>
                     <textarea name="description" rows="4" name="description" id="description" class="form-control" placeholder="Task description" required>{{ old('description', $task->description) }}</textarea>
                    </div>

                    <!-- Task - Project List  -->
                    <div class="col-12 col-md-4">
                        <label for="project_id" class="form-label">Project Name</label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ $project->id == $task->project_id ? 'selected' : '' }}>
                            {{ $project->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Task - Assign User -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label">Assign User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $task->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Task Satus -->
                    <div class="col-12 col-md-4">
                        <label for="role" class="form-label">Task Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Task Due Dates -->
                    <div class="col-12 col-md-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ $task->due_date->format('Y-m-d') }}" class="form-control">
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Project
                        </button>
                    </div>

                </div>
            </form>

            
        </div>
    </div>
    <!-- Edit Task Form End -->


    <!-- Task Comment section start -->
    <div class="card shadow-sm mt-2">        
        <div class="card-body">
            <h4>Task Discussion</h4>
            <form method="POST" action="{{ route('tasks.comments.store', $task) }}" enctype="multipart/form-data">
                @csrf     
                <div class="row g-3">                    
                    <div class="col-12">
                        <textarea name="comment" rows="4" name="comment" id="comment" class="form-control" placeholder="Write a comment..." required></textarea>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <!-- <div class="col-12 col-md-3">
                        <input type="file" name="audio" accept="audio/*" class="form-control">
                    </div> -->

<div class="col-12 col-md-3 d-flex align-items-center gap-2">
    <button type="button" id="recordBtn" class="btn btn-outline-danger">
        🎙️ Start Recording
    </button>
</div>

<div class="col-12">
    <audio id="preview" controls class="w-100 d-none"></audio>
</div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Project
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach($task->comments as $comment)
    <div style="border:1px solid #ddd; padding:10px; margin-bottom:10px">
        <strong>{{ $comment->user->name }}</strong>
        <small>({{ $comment->created_at->diffForHumans() }})</small>

        @if($comment->comment)
            <p>{{ $comment->comment }}</p>
        @endif


       @if($comment->file_path)
            <a href="{{ asset('storage/'.$comment->file_path) }}" target="_blank">
                📎 Download File
            </a>
        @endif

        @if($comment->audio_path)
            <audio controls>
                <source src="{{ asset('storage/'.$comment->audio_path) }}">
            </audio>
        @endif
    </div>
@endforeach
    <!-- Task Comment section end -->

<!-- <button onclick="testMic()">Test Mic Permission</button>
TO Test Mic is woriking or not
    <script>
async function testMic() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        alert('🎉 Mic permission GRANTED');
        stream.getTracks().forEach(t => t.stop());
    } catch (e) {
        alert('❌ Mic permission BLOCKED');
        console.error(e);
    }
}
</script> -->

<!-- <script>
 alert('JS loaded');
    if (!navigator.mediaDevices) {
     alert('mediaDevices not supported');
    }

    if (!window.MediaRecorder) {
     alert('MediaRecorder not supported in this browser');
    }
</script> -->

<script>
let mediaRecorder;
let audioChunks = [];
let isRecording = false;
let stream;

const recordBtn = document.getElementById('recordBtn');
const preview = document.getElementById('preview');
const commentForm = document.querySelector('form[action*="comments"]');

recordBtn.addEventListener('click', async () => {

    // ▶️ START RECORDING
    if (!isRecording) {
        stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);

        mediaRecorder.start();
        isRecording = true;

        recordBtn.innerText = '⏹️ Stop Recording';
        recordBtn.classList.remove('btn-outline-danger');
        recordBtn.classList.add('btn-danger');
        return;
    }

    // ⏹️ STOP RECORDING
    mediaRecorder.stop();
    isRecording = false;

    mediaRecorder.onstop = () => {
        const blob = new Blob(audioChunks, { type: 'audio/webm' });
        const audioURL = URL.createObjectURL(blob);

        preview.src = audioURL;
        preview.classList.remove('d-none');

        // Convert blob → file
        const file = new File([blob], 'voice.webm', { type: 'audio/webm' });

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        let audioInput = document.createElement('input');
        audioInput.type = 'file';
        audioInput.name = 'audio';
        audioInput.files = dataTransfer.files;

        commentForm.appendChild(audioInput);

        // stop mic
        stream.getTracks().forEach(track => track.stop());

        recordBtn.innerText = '🎙️ Start Recording';
        recordBtn.classList.remove('btn-danger');
        recordBtn.classList.add('btn-outline-danger');
    };
});
</script>


</div>
@endsection
