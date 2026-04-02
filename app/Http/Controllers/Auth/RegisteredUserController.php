<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Explicitly imported for safety
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate the code
        $otp = rand(100000, 999999);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
            'is_otp_verified' => 0, 
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Send the Mailtrap Email
        try {
            Mail::raw("Welcome new user, your verification code is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Verify Your Account');
            });
        } catch (\Exception $e) {
            Log::error("Mail Error: " . $e->getMessage());
        }

        return redirect()->route('otp.verify');
    }
}