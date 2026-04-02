<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Error 1: Missing this import
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND is an admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // If not an admin, send them to the regular dashboard
        return redirect()->route('dashboard');
    }
}