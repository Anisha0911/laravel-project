<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $data = $request->validate([
            'comment' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'audio' => 'nullable|file|mimes:mp3,wav,m4a|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('task-files', 'public');
        }

        if ($request->hasFile('audio')) {
            $data['audio_path'] = $request->file('audio')->store('task-audio', 'public');
        }

        $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $data['comment'] ?? null,
            'file_path' => $data['file_path'] ?? null,
            'audio_path' => $data['audio_path'] ?? null,
        ]);

        return back();
    }
}
