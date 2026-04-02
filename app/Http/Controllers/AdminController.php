<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() 
    {
        return view('admin.users', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all()
        ]);
    }

    public function dashboard() 
    {
        // Define staff roles to exclude from student view
        $staffRoles = ['admin', 'superadmin', 'task-manager'];

        // 1. Get only 'Student' users (users who aren't staff)
        $students = User::whereDoesntHave('roles', function($q) use ($staffRoles) {
            $q->whereIn('slug', $staffRoles);
        })->with('tasks')->get();

        // 2. Fetch basic stats for the dashboard cards
        $stats = [
            'total_users'   => User::count(),
            'total_tasks'   => Task::count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('students', 'stats'));
    }

    public function updateRole(Request $request, User $user)
    {
        // 1. MASTER LOCK: Only Super Admins can change authority
        if (!Auth::user()->hasRole('superadmin')) {
            return back()->with('error', 'AUTHORITY DENIED: Super Admin clearance required.');
        }

        // 2. Validation for Multiple Roles
        $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        // 3. Prevent Self-Demotion (Safety Check)
        $superAdminRole = Role::where('slug', 'superadmin')->first();
        if ($user->id === Auth::id() && !in_array($superAdminRole->id, $request->role_ids)) {
            return back()->with('error', 'CRITICAL ERROR: Cannot demote your own Super Admin status.');
        }

        // 4. Update the Rank using sync for multiple roles
        $user->roles()->sync($request->role_ids);

        return back()->with('status', "RANK UPDATED: {$user->name} authority levels modified.");
    }
}