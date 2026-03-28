<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () { return view('welcome'); });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    if (!Auth::user()->is_admin) return redirect('/dashboard');
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    // API Routes
    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::post('/api/tasks', [TaskController::class, 'store']);
    Route::put('/api/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/api/tasks/{task}', [TaskController::class, 'destroy']);
    
    Route::get('/api/admin/summary', function() {
        if (!Auth::user()->is_admin) return response()->json(['error' => 'Unauthorized'], 403);
        // We fetch all users who are NOT admins, including their tasks
        return response()->json([
            'students' => User::where('is_admin', false)->with('tasks')->get()
        ]);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';