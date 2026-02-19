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

// Projects assigned to user (for table display)
        $myProjects = Project::whereHas('tasks', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        
        // Total projects count for card
        $projectsCount = $myProjects->count();

//fetching tasks for logged-in user
// $tasks = Task::where('user_id', auth()->id())->get();
// Fetch all tasks
$tasks_status = Task::all(); // ✅ returns a collection of Task objects

        // Total tasks assigned to user
        $tasksCount = Task::where('user_id', $user->id)->count();

        // Completed tasks assigned to user
        $completedTasksCount = Task::where('user_id', $user->id)
                                   ->where('status', 'completed')
                                   ->count();



        return view('user.dashboard', [
            'projects' => $projectsCount,
            'tasks' => $tasksCount,
            'completedTasks' => $completedTasksCount,
            'myProjects' => $myProjects, // <--- Added this
            'tasks_status' => $tasks_status,
             ]);
    }

        public function updateStatus(Request $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $task->update([
            'status' => $request->status
        ]);
        return back();
    }


    
}
