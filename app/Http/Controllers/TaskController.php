<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    /**
     * Get all tasks based on permissions.
     * Uses caching to bypass cloud latency for 2 minutes.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $cacheKey = "user_tasks_{$user->id}";

        // If data exists in cache, it returns it instantly (0ms)
        $tasks = Cache::remember($cacheKey, 120, function () use ($user) {
            // Eager load nested roles and permissions in ONE trip
            $user->load('roles.permissions');
            
            if ($user->hasPermission('task-view-all')) {
                // Task::with('user') handles the N+1 problem
                return Task::with('user')->orderBy('due_date', 'asc')->get();
            }

            return Task::where('user_id', $user->id)->orderBy('due_date', 'asc')->get();
        });

        return response()->json($tasks);
    }

    /**
     * Create and assign a new task.
     * Clears cache so the new task shows up immediately.
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user()->load('roles.permissions');

        $data = $request->validate([
            'title' => 'required|string|max:255', 
            'due_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $targetId = ($user->hasPermission('task-assign') && $request->filled('user_id')) 
                    ? $request->user_id 
                    : $user->id;

        $task = Task::create([
            'user_id' => $targetId,
            'title' => $data['title'],
            'due_date' => $data['due_date'],
            'status' => 'Pending'
        ]);

        // IMPORTANT: Clear the cache so the user sees the new task on refresh
        Cache::forget("user_tasks_{$user->id}");
        if ($user->hasPermission('task-view-all')) {
             // If admin, we should clear all task caches or just wait for 2 mins
             // For now, let's clear the primary cache
             Cache::flush(); 
        }

        return response()->json([
            'message' => 'Task deployed!', 
            'task' => $task->load('user')
        ], 201);
    }

    /**
     * Update an existing task.
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $user = Auth::user()->load('roles.permissions');

        if ($task->user_id !== $user->id && !$user->hasPermission('task-edit-all')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->update($request->only(['title', 'status', 'due_date']));
        
        // Clear cache so updates are visible
        Cache::forget("user_tasks_{$user->id}");
        
        return response()->json($task);
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $user = Auth::user()->load('roles.permissions');

        if (!$user->hasPermission('task-delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();
        
        // Clear cache
        Cache::forget("user_tasks_{$user->id}");
        
        return response()->json(['message' => 'Deleted']);
    }
}