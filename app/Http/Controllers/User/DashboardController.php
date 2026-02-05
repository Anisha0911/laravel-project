<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('user.dashboard', [
            'projects' => Project::where('user_id', $user->id)->count(),
            'tasks' => Task::where('user_id', $user->id)->count(),
            'completedTasks' => Task::where('user_id', $user->id)
                                    ->where('status', 'completed')
                                    ->count(),
        ]);
    }

    
}
