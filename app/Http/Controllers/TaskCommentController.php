<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User; // ✅ ADD THIS
use App\Notifications\TaskNotification; // ✅ ADD THIS


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

        // Notify opposite role
        $task = $task;

// 🔔 Notify assigned user or admin(s)
if (Auth::user()->role === 'admin') {
    // Admin commented → notify assigned user
    if ($task->user_id && $task->user_id !== Auth::id()) {
        $recipient = User::find($task->user_id);
        if ($recipient) {
            $recipient->notify(new TaskNotification(
                Auth::user()->name . ' commented on a task',
                $task->id
            ));
        }
    }
} else {
    // User commented → notify all admins (except commenter)
    $admins = User::where('role', 'admin')
                  ->where('id', '!=', Auth::id())
                  ->get();

    foreach ($admins as $admin) {
        $admin->notify(new TaskNotification(
            Auth::user()->name . ' commented on a task',
            $task->id
        ));
    }
}
        return back();
    }
}

