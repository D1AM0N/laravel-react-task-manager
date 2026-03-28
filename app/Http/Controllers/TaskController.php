<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index() {
        return response()->json(Task::where('user_id', Auth::id())->orderBy('due_date', 'asc')->get());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255', 
            'due_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $targetId = (Auth::user()->is_admin && $request->has('user_id')) 
                    ? $request->user_id 
                    : Auth::id();

        $task = Task::create([
            'user_id' => $targetId,
            'title' => $data['title'],
            'due_date' => $data['due_date'],
            'status' => 'Pending'
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task) {
        // 🛡️ LOCK: Only the Admin can update tasks. 
        // Students are blocked even if they try to use the console/Postman.
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Action restricted to Admin only.'], 403);
        }

        $task->update($request->only(['title', 'status', 'due_date']));
        return response()->json($task);
    }

    public function destroy(Task $task) {
        // 🛡️ LOCK: Only the Admin can delete tasks.
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Action restricted to Admin only.'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Deleted']);
    }
}