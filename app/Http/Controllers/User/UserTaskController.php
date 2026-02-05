<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{
    public function show(Task $task)
    {
        // Check that logged-in user is assigned to this task
        abort_if($task->user_id !== Auth::id(), 403);

        return view('user.tasks.show', compact('task'));
    }
}
