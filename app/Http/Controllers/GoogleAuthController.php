<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if the user is the admin
            if (strtolower($googleUser->getEmail()) !== strtolower(config('services.admin.email'))) {
                return redirect()->route('access.denied');
            }

            // Find or create user
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update existing user with Google ID if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(24)), // Random password for OAuth users
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            
            return redirect()->route('access.denied');
        }
    }

    /**
     * Show access denied page
     */
    public function accessDenied()
    {
        return view('livewire.pages.access-denied');
    }
}
