<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
  public function index()
{
    $users = User::all();
    $projects = Project::with('tasks')->get();
    $tasks = Task::with(['project','user'])->latest()->limit(10)->get();

    $completedTasks = Task::where('status', 'completed')->count();
    $pendingTasks   = Task::where('status', 'pending')->count();
    $inProgressTasks = Task::where('status', 'in_progress')->count();

    // Project Overview Stats
    $openProjects = Project::whereHas('tasks', fn($q)=>$q->where('status','!=','completed'))->count();
    $completedProjects = Project::whereDoesntHave('tasks', fn($q)=>$q->where('status','!=','completed'))->count();

    // Projects assigned to user (for table display)
$myProjects = Project::with(['tasks.user'])->get();

    // Timeline
    $timeline = Task::with('user')->latest()->limit(8)->get();

    return view('admin.dashboard', compact(
        'users','projects','tasks',
        'completedTasks','pendingTasks','inProgressTasks',
        'openProjects','completedProjects','timeline','myProjects'
        ));
}
    
}





