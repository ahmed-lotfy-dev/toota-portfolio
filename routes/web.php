<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\LanguageController;
use App\Livewire\Dashboard;
use App\Livewire\Dashboard\Categories;
use App\Livewire\Dashboard\HeroImages;
use App\Livewire\Dashboard\Projects;
use App\Livewire\Dashboard\Testimonials;
use App\Livewire\Home;
use App\Livewire\Pages\NotFound;
use App\Livewire\Pages\ProjectDetail;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', Home::class)->name('home');
Route::get('project/{slug}', ProjectDetail::class)->name('project.detail');
Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");

// Google OAuth routes
Route::get('/login/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/login/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::get('/access-denied', [GoogleAuthController::class, 'accessDenied'])->name('access.denied');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('categories', Categories::class)->name('categories.index')->name('categories');
    Route::get('projects', Projects::class)->name('projects.index');
    Route::get('projects/{project}', \App\Livewire\Dashboard\ProjectDetail::class)->name('dashboard.project.detail');

    Route::post('upload-image', [ImageUploadController::class, 'store'])->name('image.upload');

    Route::get('profile', Profile::class)->name('profile.edit');
    Route::get('password', Password::class)->name('user-password.edit');
    Route::get('appearance', Appearance::class)->name('appearance.edit');
    Route::get('backups', \App\Livewire\Settings\Backups::class)->name('backups.index');

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

    Route::get('testimonials', Testimonials::class)->name('testimonials.index');
    Route::get('hero-images', HeroImages::class)->name('hero-images.index');
});

// Fallback route for 404
Route::fallback(NotFound::class);
