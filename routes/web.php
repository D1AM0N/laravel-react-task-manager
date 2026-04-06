<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Mail\OtpMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Authentication Routes (Laravel Breeze defaults)
require __DIR__.'/auth.php';

// 2. Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 3. OTP Verification Core Logic
Route::middleware(['auth'])->group(function () {

    // Show the Verification Screen
    Route::get('/verify-otp', function () {
        if (Auth::user()->is_otp_verified) {
            return redirect()->route('dashboard');
        }
        return view('auth.verify-otp');
    })->name('otp.verify');

    // Process the Submitted Code
    Route::post('/verify-otp', function (Request $request) {
        $request->validate([
            'otp_code' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();

        if ($user->otp_code == $request->otp_code && now()->lt($user->otp_expires_at)) {
            $user->update([
                'is_otp_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null
            ]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['otp_code' => 'The code is invalid or has expired.']);
    })->name('otp.submit');

    // Resend OTP
    Route::post('/resend-otp', function () {
        $user = Auth::user();

        $newOtp = rand(100000, 999999);

        $user->update([
            'otp_code' => $newOtp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($newOtp));
        } catch (\Exception $e) {
            Log::error("Mail Error: " . $e->getMessage());
        }

        return back()->with('status', 'A fresh verification code has been dispatched.');
    })->name('otp.resend');

    // HELPER: Catch accidental GET requests to resend-otp
    Route::get('/resend-otp', function () {
        return redirect()->route('otp.verify');
    });
});

// 4. Secured Application Routes (Requires Authentication AND Verified OTP)
Route::middleware(['auth', 'otp.verified'])->group(function () {

    // User Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /* --- Admin Command Center --- */
    Route::middleware('can:access-admin')->group(function () {

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.updateRole');

        // Admin Summary API
        Route::get('/api/admin/summary', function () {
            $staffRoles = ['admin', 'superadmin', 'task-manager'];
            return response()->json([
                'students' => User::whereDoesntHave('roles', function ($q) use ($staffRoles) {
                    $q->whereIn('slug', $staffRoles);
                })->with('tasks')->get()
            ]);
        });
    });

    /* --- Task Management API --- */
    Route::prefix('api')->group(function () {
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    });

    /* --- Profile Management --- */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});