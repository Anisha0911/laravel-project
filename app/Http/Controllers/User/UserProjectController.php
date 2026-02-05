<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class UserProjectController extends Controller
{
    public function index()
    {  // Only projects which have tasks assigned to this user
        // Get projects that have at least one task assigned to the logged-in user
        // Also eager-load only tasks assigned to this user
        $projects = Project::whereHas('tasks', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->with(['tasks' => function($q) {
            $q->where('user_id', Auth::id());
        }])
        ->get();

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
