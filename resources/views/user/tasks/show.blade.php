@extends('layouts.user')
<form method="POST" action="{{ route('tasks.comments.store', $task) }}" enctype="multipart/form-data">
    @csrf

    <textarea name="comment" placeholder="Write a comment..."></textarea>

    <input type="file" name="file">
    <input type="file" name="audio" accept="audio/*">

    <button type="submit">Send</button>
</form>
<h4>Task Discussion</h4>

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
