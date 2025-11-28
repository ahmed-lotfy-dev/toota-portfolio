<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\GoogleAuthController;
use App\Livewire\Dashboard;
use App\Livewire\Home;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\PresignedUrlController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', action: Home::class)->name('home');
Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");

// Google OAuth routes
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Access denied page
Route::get('access-denied', function () {
    return view('livewire.pages.access-denied');
})->name('access.denied');

Route::middleware(['auth', 'verified', 'admin'])->prefix('dashboard')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('categories', App\Livewire\Dashboard\Categories::class)->name('categories.index')->name('categories');
    Route::get('projects', App\Livewire\Dashboard\Projects::class)->name('projects.index')->name('projects');
    Route::post('presigned-url', [PresignedUrlController::class, 'store'])->name('presigned-url.store');

    Route::get('profile', Profile::class)->name('profile.edit');
    Route::get('password', Password::class)->name('user-password.edit');
    Route::get('appearance', Appearance::class)->name('appearance.edit');

    Route::get('two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('testimonials', App\Livewire\Dashboard\Testimonials::class)->name('testimonials.index');
});
