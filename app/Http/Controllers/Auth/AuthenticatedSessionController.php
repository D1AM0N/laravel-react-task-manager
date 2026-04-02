<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        
        // FORCE REFRESH: This pulls the LATEST data from the DB immediately
        $user->refresh();

        Log::debug("Login Attempt - User: {$user->email} | Verified Status: " . ($user->is_otp_verified ? 'TRUE' : 'FALSE'));

        if ($user->is_otp_verified) {
            return redirect()->intended(route('dashboard', absolute: false));
        }
        
        $otp = rand(100000, 999999);
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::raw("Your Stormbreaker login code is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Security Verification Code');
            });
        } catch (\Exception $e) {
            Log::error("SMTP Error: " . $e->getMessage()); 
        }

        return redirect()->route('otp.verify');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}