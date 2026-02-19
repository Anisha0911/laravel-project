<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class UserProjectController extends Controller
{
    public function index(Request $request)
    {
        // Start query: only projects that have tasks assigned to this user
        $query = Project::whereHas('tasks', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->with(['tasks' => function($q) {
            $q->where('user_id', Auth::id());
        }]);

        // -----------------------------
        // SEARCH
        // -----------------------------
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // -----------------------------
        // STATUS FILTER
        // -----------------------------
   if ($request->status) {
    if ($request->status === 'active') {
        // Projects that have at least one task not completed
        $query->whereHas('tasks', function($q) {
            $q->where('user_id', Auth::id())
              ->where('status', '!=', 'completed');
        });
    } elseif ($request->status === 'completed') {

    $query->whereHas('tasks', function ($q) {
        $q->where('user_id', Auth::id());
    });

    $query->whereDoesntHave('tasks', function ($q) {
        $q->where('user_id', Auth::id())
          ->where('status', '!=', 'completed');
    });
}

}


        // -----------------------------
        // PAGINATION
        // -----------------------------
        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('user.projects.index', compact('projects'));
    }
      
    public function tasks(Project $project)
    {
        // Get all tasks in this project assigned to the logged-in user
        $tasks = $project->tasks()->where('user_id', Auth::id())->get();

        // Optional: abort if no tasks assigned
        abort_if($tasks->isEmpty(), 403, 'No tasks assigned to you in this project.');

        return view('user.projects.tasks', compact('project', 'tasks'));
    }

}
