<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // If verified, proceed to the requested page
        if ($user->is_otp_verified) {
            return $next($request);
        }

        // Allow these specific routes so we don't loop
        if ($request->routeIs('otp.verify') || $request->routeIs('otp.post') || $request->routeIs('logout')) {
            return $next($request);
        }

        Log::debug("Middleware Blocked User: {$user->email} - Redirecting to OTP");
        return redirect()->route('otp.verify');
    }
}