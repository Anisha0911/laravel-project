<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TaskNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'user'])->latest()->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('admin.tasks.create', [
            'projects' => Project::all(),
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
            'due_date' => 'nullable|date',
        ]);

        // Create task first
        $task = Task::create($request->all());

        // 🔹 Notify assigned user
        $user = User::find($task->user_id);
        if ($user) {
            $user->notify(new TaskNotification(
                'A new task has been assigned to you',
                $task->id
            ));
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task Created Successfully!');
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.edit', [
            'task' => $task,
            'projects' => Project::all(),
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
            'due_date' => 'nullable|date',
        ]);

        // 🔹 Check if assigned user changed
        if ($task->user_id != $request->user_id) {
            $user = User::find($request->user_id);
            if ($user) {
                $user->notify(new TaskNotification(
                    'A task has been assigned to you',
                    $task->id
                ));
            }
        }

        // 🔹 Check if status changed
        if ($task->status != $request->status) {
            $user = User::find($request->user_id);
            if ($user) {
                $user->notify(new TaskNotification(
                    'Task status changed to '.$request->status,
                    $task->id
                ));
            }
        }

        // Update task
        $task->update($request->all());

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task Updated Successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task Deleted Successfully!');
    }

    // 🔹 ADD THIS SHOW METHOD TO FIX NOTIFICATION LINKS
    public function show(Task $task)
    {
        $task->load('project', 'user', 'comments');

        return view('admin.tasks.show', compact('task'));
    }
}
