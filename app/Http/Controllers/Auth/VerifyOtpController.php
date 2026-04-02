<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    /**
     * Show the OTP verification screen.
     */
    public function show(): View
    {
        return view('auth.verify-otp');
    }

    /**
     * Handle the OTP submission.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'numeric'],
        ]);

        $user = Auth::user();

        // Check if the code matches and isn't expired
        if ($user->otp_code == $request->otp_code) {
            
            $user->update([
                'is_otp_verified' => 1,
                'otp_code' => null, // Clear code after use
                'otp_expires_at' => null,
            ]);

            return redirect()->route('dashboard')->with('success', 'Account verified! Welcome to the portal.');
        }

        return back()->withErrors([
            'otp_code' => 'The verification code is incorrect or has expired.',
        ]);
    }
}