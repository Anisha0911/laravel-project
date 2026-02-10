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
use App\Http\Controllers\NotificationController;

Route::middleware('auth')->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
});

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard
Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes for admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin resources
    Route::resource('users', UserController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
});

// User Dashboard
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    // Profile routes for user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User resources
    Route::get('/projects', [UserProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}/tasks', [UserProjectController::class, 'tasks'])->name('projects.tasks');
    Route::get('/tasks', [UserTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [UserTaskController::class, 'show'])->name('tasks.show');

        // ADD THIS (AJAX filter)
Route::get('/tasks/filter/ajax', [UserTaskController::class, 'ajaxFilter'])
    ->name('tasks.ajaxFilter'); // <--- THIS NAME MUST MATCH


    Route::post('user/user/tasks/{task}/show', [UserTaskController::class, 'updateStatus'])
    ->name('user.tasks.show');
});

// Redirect after login
Route::get('/redirect', function () {
    if (Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return redirect('/user/dashboard');
});

// Profile routes for non-prefixed auth (optional)
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Other routes
Route::middleware('auth')->group(function () {
    Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');
});

require __DIR__.'/auth.php';
