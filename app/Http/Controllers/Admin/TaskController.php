<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

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
        ]);

        Task::create($request->all());

        // return redirect()->route('tasks.index')->with('success', 'Task created');
         return redirect()->route('admin.tasks.index')
                     ->with('success', 'Task created successfully.');
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
            'project_id' => 'required',
            'user_id' => 'required',
        ]);

        $task->update($request->all());
     return redirect()->route('admin.tasks.index')->with('success', 'Task updated');
        }

        public function destroy(Task $task)
        {
            $task->delete();

            return redirect()
                ->route('admin.tasks.index')
                ->with('success', 'Task deleted successfully');
        }


}
