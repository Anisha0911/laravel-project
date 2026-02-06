<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\TaskCommentController;

// USER PANEL
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\UserProjectController;
use App\Http\Controllers\User\UserTaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/dashboard', [DashboardController::class, 'index']);
// });

// Admin Dashboard
Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');;
});

// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserDashboard::class, 'index'])
        ->name('user.dashboard');
});


Route::get('/redirect', function () {
    if (Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return redirect('/user/dashboard');
});


// USER PANEL ===============

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Projects list for user
    Route::get('/projects', [UserProjectController::class, 'index'])->name('projects.index');

    // New route: show all tasks for a project
    Route::get('/projects/{project}/tasks', [UserProjectController::class, 'tasks'])
        ->name('projects.tasks');

    Route::get('/tasks', [UserTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [UserTaskController::class, 'show'])->name('tasks.show');
});




// NOTIFICATION ROUTE
Route::get('/notifications', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return view('notifications.index');
})->name('notifications');



// Users
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/users', [UserController::class, 'index']);
//     Route::get('/admin/users/create', [UserController::class, 'create']);
//     Route::post('/admin/users', [UserController::class, 'store']);
//     Route::get('/admin/users/{user}/edit', [UserController::class, 'edit']);
//     Route::put('/admin/users/{user}', [UserController::class, 'update']);
//     Route::delete('/admin/users/{user}', [UserController::class, 'destroy']);
// });

// Projects 
// Users
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('tasks', TaskController::class);
    });


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])
        ->name('tasks.comments.store');
});


require __DIR__.'/auth.php';
