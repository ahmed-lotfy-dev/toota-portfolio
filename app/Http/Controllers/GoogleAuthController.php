<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Get admin email from config
            $adminEmail = config('app.admin_email');
            
            // Security check: Only allow admin email
            if ($googleUser->getEmail() !== $adminEmail) {
                // Redirect to custom access denied page
                return redirect()->route('access.denied')->with('attempted_email', $googleUser->getEmail());
            }
            
            // Find or create user
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update existing user with Google ID and avatar
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Create new user (should not happen since registration is disabled, but just in case)
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }
            
            // Log the user in
            Auth::login($user, true);
            
            // Redirect to dashboard
            return redirect()->intended(config('fortify.home'));
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to sign in with Google. Please try again or use email/password login.',
            ]);
        }
    }
}
