<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{   

  // ✅ List all tasks assigned to the logged-in user
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
                     ->with('project')
                     ->latest()
                     ->get();

        return view('user.tasks.index', compact('tasks'));
    }

     // ✅ Show single task
    public function show(Task $task)
    {
        // Check that logged-in user is assigned to this task
        abort_if($task->user_id !== Auth::id(), 403);

        return view('user.tasks.show', compact('task'));
    }
}

