<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/listing', [ListingController::class, 'index']);

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/profile', [HomeController::class, 'profile']);

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    });
    Route::get('/signup', function () {
        return view('admin.signup');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
    // Inventory & Stays
    Route::get('/packages', [AdminController::class, 'packages']);
    Route::get('/hotels', [AdminController::class, 'hotels']);
    Route::get('/amenities', [AdminController::class, 'amenities']);
    Route::get('/holiday-types', [AdminController::class, 'holidayTypes']);
    Route::get('/activities', [AdminController::class, 'activities']);

    // Admin Central
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/agents', [AdminController::class, 'agents']);
    Route::get('/leads', [AdminController::class, 'leads']);

    // Subscription Oversight
    Route::get('/paid-users', [AdminController::class, 'paidUsers']);
    Route::get('/user-plans', [AdminController::class, 'userPlans']);
    Route::get('/payments', [AdminController::class, 'payments']);
    Route::get('/ads', [AdminController::class, 'ads']);
    Route::get('/plans', [AdminController::class, 'plans']);

    // Platform Settings
    Route::get('/home-editor', [AdminController::class, 'homeEditor']);
    Route::get('/notifications', [AdminController::class, 'notifications']);
    Route::get('/cms', [AdminController::class, 'cms']);
    Route::get('/contact', [AdminController::class, 'contact']);
    Route::get('/subscribers', [AdminController::class, 'subscribers']);
    Route::get('/settings', [AdminController::class, 'settings']);
});

Route::get('/discover', [ListingController::class, 'index'])->name('discover');
Route::get('/tour/{slug}', function($slug) {
    return view('tour.show', compact('slug'));
})->name('tour.show');
Route::get('/login', function() {
    return view('admin.login');
})->name('login');

Route::get('/package/{id}', [PackageController::class, 'show']);
