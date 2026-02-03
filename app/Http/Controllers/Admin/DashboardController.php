<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
   public function index()
    {
    $users = User::all();
    $projects = Project::all();
    $task = Task::all();
    $completedTasks = Task::where('status', 'completed')->count();
    $pendingTasks   = Task::where('status', 'pending')->count();

    return view('admin.dashboard', compact(
        'users',
        'projects',
        'task',
        'completedTasks',
        'pendingTasks'
    ));



    //return view('admin.dashboard', compact('users', 'projects'));
    }
}





