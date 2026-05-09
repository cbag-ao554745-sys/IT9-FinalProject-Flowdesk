<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskActivityController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/',       [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile',          [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile',          [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/',               [ProjectController::class, 'index'])->name('index');
        Route::get('/create',         [ProjectController::class, 'create'])->name('create');
        Route::post('/',              [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}',      [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}',      [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}',   [ProjectController::class, 'destroy'])->name('destroy');
    });

    // Tasks
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/',                  [TaskController::class, 'index'])->name('index');
        Route::get('/create',            [TaskController::class, 'create'])->name('create');
        Route::post('/',                 [TaskController::class, 'store'])->name('store');
        Route::get('/{task}',            [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit',       [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}',            [TaskController::class, 'update'])->name('update');
        Route::patch('/{task}/status',   [TaskController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{task}',         [TaskController::class, 'destroy'])->name('destroy');
        Route::get('/{task}/activities', [TaskActivityController::class, 'index'])->name('activities');
    });

    // Task Comments
    Route::prefix('task-comments')->name('task-comments.')->group(function () {
        Route::post('/',                [TaskCommentController::class, 'store'])->name('store');
        Route::delete('/{taskComment}', [TaskCommentController::class, 'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
    });
});