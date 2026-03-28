<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        return view('admin.dashboard');
    }

    public function getSummary() {
        try {
            return response()->json([
                // Your personal tasks (Student-style view)
                'myTasks' => Task::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get(),
                // Students for the management panel
                'students' => User::where('is_admin', false)->with('tasks')->get(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}