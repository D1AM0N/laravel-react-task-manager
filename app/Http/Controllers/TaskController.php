<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->hasPermission('task-view-all')) {
            $tasks = Task::with('user')->orderBy('due_date', 'asc')->get();
        } else {
            $tasks = Task::where('user_id', $user->id)->orderBy('due_date', 'asc')->get();
        }

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255', 
            'due_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $targetId = (Auth::user()->hasPermission('task-assign') && $request->filled('user_id')) 
                    ? $request->user_id 
                    : Auth::id();

        $task = Task::create([
            'user_id' => $targetId,
            'title' => $data['title'],
            'due_date' => $data['due_date'],
            'status' => 'Pending'
        ]);

        return response()->json(['message' => 'Task deployed!', 'task' => $task->load('user')], 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id() && !Auth::user()->hasPermission('task-edit-all')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->update($request->only(['title', 'status', 'due_date']));
        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        if (!Auth::user()->hasPermission('task-delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Deleted']);
    }
}