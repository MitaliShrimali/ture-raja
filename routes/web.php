<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC / USER ROUTES ────────────────────────────────────────────────────

Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/listing', [ListingController::class, 'index']);
Route::get('/listing/holiday-list', [ListingController::class, 'index'])->name('listing.holiday-list');
Route::get('/discover', [ListingController::class, 'index'])->name('discover');

// Static pages (keep original behaviour)
Route::get('/about', function () { return view('about'); });
Route::get('/career', [UserController::class, 'careers'])->name('career');
Route::post('/career/submit', [UserController::class, 'submitCareer'])->name('career.submit');
Route::get('/contact', function () { return view('contact'); });
Route::get('/privacy-policy', function () { return view('privacy-policy'); });
Route::get('/privacy-policy-careers', function () { return view('privacy-policy-careers'); });
Route::get('/terms-and-conditions', function () { return view('terms'); });
Route::get('/page/{slug}', [UserController::class, 'showCmsPage'])->name('page.show');
// Search from hero bar → redirect to listing
Route::get('/search', [UserController::class, 'search'])->name('search');
Route::get('/api/search-suggestions', [UserController::class, 'suggestions']);

// Ad click tracking
Route::get('/ad/click/{id}', [UserController::class, 'trackAdClick'])->name('ad.click');

// Newsletter subscription
Route::post('/newsletter/subscribe', [UserController::class, 'subscribe'])->name('newsletter.subscribe');

// Contact form submission (stores in user_inquiries + contacts for admin)
Route::post('/contact/submit', [UserController::class, 'submitContact'])->name('contact.submit');

// Package booking request
Route::post('/package/book', [UserController::class, 'bookPackage'])->name('package.book');

// Public feedback submission
Route::post('/package/feedback/store', [UserController::class, 'storePackageFeedback'])->name('package.feedback.store');

// Wishlist toggle (AJAX + form fallback)
Route::post('/wishlist/toggle', [UserController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::get('/wishlist/remove/{packageId}', [UserController::class, 'removeWishlist'])->name('wishlist.remove');

// User profile & dashboard
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [UserController::class, 'changePassword'])->name('profile.password');
    Route::get('/profile/cancel-booking/{id}', [UserController::class, 'cancelBooking'])->name('booking.cancel');
    Route::get('/profile/notification/read/{id}', [UserController::class, 'markNotificationRead'])->name('notification.read');
    Route::post('/profile/review', [UserController::class, 'submitReview'])->name('review.submit');
});

// ─── LOGIN & SIGNUP ROUTES ─────────────────────────────────────────────────────────────

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/signup', [UserController::class, 'signup'])->name('signup');

Route::post('/signup/submit', [UserController::class, 'signupSubmit'])->name('signup.submit');
Route::post('/signup/resend-otp', [UserController::class, 'resendSignupOtp'])->name('signup.resend-otp');

Route::get('/api/check-email', [UserController::class, 'checkEmail']);
Route::get('/api/check-mobile', [UserController::class, 'checkMobile']);

Route::post('/api/otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::post('/api/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
// Forgot & Reset Password
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [UserController::class, 'forgotPasswordSubmit']);
Route::get('/reset-password/{token}', [UserController::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password', [UserController::class, 'resetPasswordSubmit']);

Route::post('/login/submit', [UserController::class, 'loginSubmit'])->name('login.submit');
Route::match(['GET', 'POST'], '/logout', [UserController::class, 'logout'])->name('logout');

// ─── ADMIN ROUTES ────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $role = strtoupper(\Illuminate\Support\Facades\Auth::guard('admin')->user()->role ?? '');
            if (in_array($role, ['SUPER ADMIN', 'ADMIN', 'MANAGER', 'EDITOR', 'EMPLOYEE'])) {
                return redirect('/admin/dashboard');
            }
        }
        return response()->view('admin.login', ['type' => 'admin'])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    });
    Route::get('/signup', function () {
        return view('admin.signup', ['type' => 'admin']);
    });
    Route::post('/login/submit', [UserController::class, 'loginSubmit'])->name('admin.login.submit');
    Route::match(['GET', 'POST'], '/logout', [UserController::class, 'logout'])->name('admin.logout');

    Route::middleware(['admin.permission'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/packages/approve/{id}', [AdminController::class, 'approvePackage'])->name('admin.package.approve');
        Route::get('/packages/decline/{id}', [AdminController::class, 'declinePackage'])->name('admin.package.decline');
        Route::get('/packages/pending', [AdminController::class, 'pendingPackages'])->name('admin.packages.pending');
        Route::get('/packages/view/{id}', [AdminController::class, 'viewPackage'])->name('admin.packages.view');
        
        // Payment Pricing Settings
        Route::get('/payment-pricing', [AdminController::class, 'paymentPricing'])->name('admin.payment-pricing');
        Route::post('/payment-pricing/store', [AdminController::class, 'storeAddon'])->name('admin.payment-pricing.store');
        Route::post('/payment-pricing/update/{id}', [AdminController::class, 'updateAddon'])->name('admin.payment-pricing.update');
        Route::get('/payment-pricing/delete/{id}', [AdminController::class, 'deleteAddon'])->name('admin.payment-pricing.delete');
        
        // Inventory & Stays
        Route::get('/packages', [AdminController::class, 'packages'])->name('admin.packages');
        Route::get('/packages/international', [AdminController::class, 'internationalPackages']);
        Route::get('/packages/domestic', [AdminController::class, 'domesticPackages']);
        Route::get('/packages/create', [AdminController::class, 'createPackage']);
        Route::get('/hotels', [AdminController::class, 'hotels']);

        // Legacy redirects for old paths (keep for backward compat)
        Route::get('/amenities', fn() => redirect('/admin/settings/preferences/amenities'));
        Route::get('/holiday-types', fn() => redirect('/admin/settings/preferences/holiday-types'));
        Route::get('/activities', fn() => redirect('/admin/settings/preferences/activities'));
        Route::get('/transits', fn() => redirect('/admin/settings/preferences/transits'));
        Route::get('/durations', fn() => redirect('/admin/settings/preferences/durations'));

        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/create', [AdminController::class, 'createAdminUser']);
        Route::get('/users/edit/{id}', [AdminController::class, 'editAdminUser']);
        Route::get('/customers', [AdminController::class, 'customers']);
        Route::get('/customers/delete/{id}', [AdminController::class, 'deleteCustomer']);
        Route::get('/agents', [AdminController::class, 'agents']);
        Route::get('/agents/profile/{id}', [AdminController::class, 'agentProfile']);
        Route::get('/registered-agents', [AdminController::class, 'registeredAgents']);
        Route::get('/leads', [AdminController::class, 'leads']);
        Route::get('/careers', [AdminController::class, 'careers']);
        Route::get('/careers/delete/{id}', [AdminController::class, 'deleteCareer']);
        Route::post('/careers/positions/store', [AdminController::class, 'storePosition'])->name('admin.careers.positions.store');
        Route::get('/careers/positions/delete/{id}', [AdminController::class, 'deletePosition'])->name('admin.careers.positions.delete');
        Route::post('/careers/settings/update', [AdminController::class, 'updateCareerSettings'])->name('admin.careers.settings.update');
        Route::post('/careers/departments/store', [AdminController::class, 'storeDepartment'])->name('admin.careers.departments.store');
        Route::post('/careers/locations/store', [AdminController::class, 'storeLocation'])->name('admin.careers.locations.store');
        Route::match(['GET','POST','DELETE'], '/careers/departments/delete/{id}', [AdminController::class, 'deleteDepartment'])->name('admin.careers.departments.delete');
        Route::match(['GET','POST','DELETE'], '/careers/locations/delete/{id}', [AdminController::class, 'deleteLocation'])->name('admin.careers.locations.delete');

        // Subscription Oversight
        Route::get('/paid-users', [AdminController::class, 'paidUsers']);
        Route::get('/paid-users/create', [AdminController::class, 'createPaidUser']);
        Route::get('/payments', [AdminController::class, 'payments']);
        Route::get('/payment-pricing', [AdminController::class, 'paymentPricing']);
        Route::get('/ads', [AdminController::class, 'ads']);
        Route::get('/plans', [AdminController::class, 'plans']);
        Route::get('/reviews', [AdminController::class, 'reviews']);
        Route::post('/reviews/store', [AdminController::class, 'storeReview']);
        Route::post('/reviews/update', [AdminController::class, 'updateReview']);
        Route::get('/reviews/delete/{id}'   , [AdminController::class, 'deleteReview']);
        Route::get('/reviews/toggle/{id}', [AdminController::class, 'toggleReview']);
    // Platform Settings
    Route::get('/home-editor', [AdminController::class, 'homeEditor']);
    Route::get('/notifications', [AdminController::class, 'notifications']);
    Route::get('/cms', [AdminController::class, 'cms']);
    Route::get('/cms/edit/{id}', [AdminController::class, 'editCmsPage']);
    Route::get('/reports', [AdminController::class, 'reports']);
    Route::get('/reports/inquiries/download', [AdminController::class, 'downloadInquiryReport']);
    Route::get('/reports/leads/download', [AdminController::class, 'downloadLeadsReport']);
    Route::get('/reports/payments/download', [AdminController::class, 'downloadPaymentsReport']);
    Route::get('/contact', [AdminController::class, 'contact']);
    Route::get('/subscribers', [AdminController::class, 'subscribers']);
    Route::get('/settings', [AdminController::class, 'settings']);

    // CRUD Routes
    Route::post('/users/store', [AdminController::class, 'storeUser']);
    Route::post('/users/update', [AdminController::class, 'updateUser']);
    Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/users/toggle/{id}', [AdminController::class, 'toggleUser']);
    Route::post('/roles/store', [AdminController::class, 'storeRole']);
    Route::post('/roles/delete/{id}', [AdminController::class, 'deleteRole']);

    Route::post('/agents/store', [AdminController::class, 'storeAgent']);
    Route::get('/agents/edit/{id}', [AdminController::class, 'editAgent']);
    Route::post('/agents/update', [AdminController::class, 'updateAgent']);
    Route::get('/agents/delete/{id}', [AdminController::class, 'deleteAgent']);
    Route::get('/agents/toggle/{id}', [AdminController::class, 'toggleAgent']);

    Route::post('/leads/store', [AdminController::class, 'storeLead']);
    Route::post('/leads/update', [AdminController::class, 'updateLead']);
    Route::get('/leads/delete/{id}', [AdminController::class, 'deleteLead']);

    Route::post('/contact/update', [AdminController::class, 'updateContact']);

    Route::post('/hotels/store', [AdminController::class, 'storeHotel']);
    Route::post('/hotels/update', [AdminController::class, 'updateHotel']);
    Route::get('/hotels/delete/{id}', [AdminController::class, 'deleteHotel']);
    Route::get('/hotels/toggle/{id}', [AdminController::class, 'toggleHotel']);

    Route::post('/packages/store', [AdminController::class, 'storePackage']);
    Route::post('/packages/update', [AdminController::class, 'updatePackage']);
    Route::get('/packages/edit/{id}', [AdminController::class, 'editPackage']);
    Route::get('/packages/delete/{id}', [AdminController::class, 'deletePackage']);
    Route::get('/packages/toggle/{id}', [AdminController::class, 'togglePackage']);

    Route::post('/home-packages/store', [AdminController::class, 'storeHomePackage']);
    Route::post('/home-packages/update', [AdminController::class, 'updateHomePackage']);
    Route::get('/home-packages/delete/{id}', [AdminController::class, 'deleteHomePackage']);
    Route::get('/home-packages/toggle/{id}', [AdminController::class, 'toggleHomePackage']);

    // All Settings sub-routes under /settings/ prefix
    Route::prefix('/settings')->group(function () {
        Route::get('/preferences', [AdminController::class, 'preferences']);
        Route::get('/mail-setup', [AdminController::class, 'mailSetup']);
        Route::post('/mail-setup/update', [AdminController::class, 'updateMailSetup']);
        Route::post('/mail-setup/test', [AdminController::class, 'sendTestEmail'])->name('settings.mail-setup.test');
        Route::get('/payment-setup', [AdminController::class, 'paymentSetup']);
        Route::post('/payment-setup/update', [AdminController::class, 'updatePaymentSetup']);
        Route::get('/whatsapp-template', [AdminController::class, 'whatsappTemplate']);
        Route::post('/whatsapp-template/update', [AdminController::class, 'updateWhatsappTemplate']);
        Route::get('/email-template', [AdminController::class, 'emailTemplate']);
        Route::post('/email-template/update', [AdminController::class, 'updateEmailTemplate']);
        Route::get('/package-reminder', [AdminController::class, 'packageReminder'])->name('settings.package-reminder');
        Route::post('/send-reminder', [AdminController::class, 'sendPackageReminder'])->name('settings.send-reminder');

        // Redirects from old settings prefix directly to nested preferences prefix
        Route::get('/amenities', fn() => redirect('/admin/settings/preferences/amenities'));
        Route::get('/holiday-types', fn() => redirect('/admin/settings/preferences/holiday-types'));
        Route::get('/activities', fn() => redirect('/admin/settings/preferences/activities'));
        Route::get('/transits', fn() => redirect('/admin/settings/preferences/transits'));
        Route::get('/durations', fn() => redirect('/admin/settings/preferences/durations'));
        Route::get('/hotel-categories', fn() => redirect('/admin/settings/preferences/hotel-categories'));
        Route::get('/themes', fn() => redirect('/admin/settings/preferences/themes'));

        Route::prefix('/preferences')->group(function () {
            // Themes
            Route::get('/themes', [AdminController::class, 'themes']);
            Route::post('/themes/store', [AdminController::class, 'storeTheme']);
            Route::post('/themes/update', [AdminController::class, 'updateTheme']);
            Route::get('/themes/toggle/{id}', [AdminController::class, 'toggleTheme']);
            Route::get('/themes/delete/{id}', [AdminController::class, 'deleteTheme']);

            // Amenities
            Route::get('/amenities', [AdminController::class, 'amenities']);
            Route::post('/amenities/store', [AdminController::class, 'storeAmenity']);
            Route::post('/amenities/update', [AdminController::class, 'updateAmenity']);
            Route::get('/amenities/delete/{id}', [AdminController::class, 'deleteAmenity']);
            Route::get('/amenities/toggle/{id}', [AdminController::class, 'toggleAmenity']);

            // Holiday Types
            Route::get('/holiday-types', [AdminController::class, 'holidayTypes']);
            Route::post('/holiday-types/store', [AdminController::class, 'storeHolidayType']);
            Route::post('/holiday-types/update', [AdminController::class, 'updateHolidayType']);
            Route::get('/holiday-types/delete/{id}', [AdminController::class, 'deleteHolidayType']);
            Route::get('/holiday-types/toggle/{id}', [AdminController::class, 'toggleHolidayType']);

            // Activities
            Route::get('/activities', [AdminController::class, 'activities']);
            Route::post('/activities/store', [AdminController::class, 'storeActivity']);
            Route::post('/activities/update', [AdminController::class, 'updateActivity']);
            Route::get('/activities/delete/{id}', [AdminController::class, 'deleteActivity']);
            Route::get('/activities/toggle/{id}', [AdminController::class, 'toggleActivity']);

            // Transits
            Route::get('/transits', [AdminController::class, 'transits']);
            Route::post('/transits/store', [AdminController::class, 'storeTransit']);
            Route::post('/transits/update', [AdminController::class, 'updateTransit']);
            Route::get('/transits/delete/{id}', [AdminController::class, 'deleteTransit']);
            Route::get('/transits/toggle/{id}', [AdminController::class, 'toggleTransit']);
            Route::post('/transits/reorder', [AdminController::class, 'reorderTransits']);

            // Durations
            Route::get('/durations', [AdminController::class, 'durations']);
            Route::post('/durations/store', [AdminController::class, 'storeDuration']);
            Route::post('/durations/update', [AdminController::class, 'updateDuration']);
            Route::get('/durations/toggle/{id}', [AdminController::class, 'toggleDuration']);
            Route::get('/durations/delete/{id}', [AdminController::class, 'deleteDuration']);

            // Hotel Categories
            Route::get('/hotel-categories', [AdminController::class, 'hotelCategories']);
            Route::post('/hotel-categories/store', [AdminController::class, 'storeHotelCategory']);
            Route::post('/hotel-categories/update', [AdminController::class, 'updateHotelCategory']);
            Route::get('/hotel-categories/toggle/{id}', [AdminController::class, 'toggleHotelCategory']);
            Route::get('/hotel-categories/delete/{id}', [AdminController::class, 'deleteHotelCategory']);

            // Countries
            Route::get('/countries', [AdminController::class, 'countries']);
            Route::post('/countries/store', [AdminController::class, 'storeCountry']);
            Route::post('/countries/update', [AdminController::class, 'updateCountry']);
            Route::get('/countries/toggle/{id}', [AdminController::class, 'toggleCountry']);
            Route::get('/countries/delete/{id}', [AdminController::class, 'deleteCountry']);

            // States
            Route::get('/states', [AdminController::class, 'states']);
            Route::post('/states/store', [AdminController::class, 'storeState']);
            Route::post('/states/update', [AdminController::class, 'updateState']);
            Route::get('/states/toggle/{id}', [AdminController::class, 'toggleState']);
            Route::get('/states/delete/{id}', [AdminController::class, 'deleteState']);

            // Cities
            Route::get('/cities', [AdminController::class, 'cities']);
            Route::post('/cities/store', [AdminController::class, 'storeCity']);
            Route::post('/cities/update', [AdminController::class, 'updateCity']);
            Route::get('/cities/toggle/{id}', [AdminController::class, 'toggleCity']);
            Route::get('/cities/delete/{id}', [AdminController::class, 'deleteCity']);
        });
    });


    Route::post('/paid-users/store', [AdminController::class, 'storePaidUser']);
    Route::post('/paid-users/update', [AdminController::class, 'updatePaidUser']);
    Route::get('/paid-users/delete/{id}', [AdminController::class, 'deletePaidUser']);
    Route::get('/paid-users/toggle/{id}', [AdminController::class, 'togglePaidUser']);

    Route::post('/payments/store', [AdminController::class, 'storePayment']);
    Route::post('/payments/update', [AdminController::class, 'updatePayment']);
    Route::get('/payments/delete/{id}', [AdminController::class, 'deletePayment']);
    Route::get('/payments/print', [AdminController::class, 'printPayments']);
    Route::get('/payments/invoice', function() { return redirect('/admin/payments'); });
    Route::get('/payments/invoice/{id}', [AdminController::class, 'paymentInvoice']);
    Route::post('/payments/invoice/update', [AdminController::class, 'updatePaymentInvoice']);

    Route::post('/ads/store', [AdminController::class, 'storeAd']);
    Route::post('/ads/update', [AdminController::class, 'updateAd']);
    Route::get('/ads/delete/{id}', [AdminController::class, 'deleteAd']);
    Route::get('/ads/toggle/{id}', [AdminController::class, 'toggleAd']);

    Route::get('/plans/create', [AdminController::class, 'createPlan']);
    Route::get('/plans/edit/{id}', [AdminController::class, 'editPlan']);
    Route::get('/plans/preview/{id}', [AdminController::class, 'previewPlan']);
    Route::get('/plans/preview/{id}/export', [AdminController::class, 'exportPreviewPlan']);
    Route::get('/plans/duplicate/{id}', [AdminController::class, 'duplicatePlan']);
    Route::get('/plans/export', [AdminController::class, 'exportPlans']);
    Route::post('/plans/store', [AdminController::class, 'storePlan']);
    Route::post('/plans/update', [AdminController::class, 'updatePlan']);
    Route::get('/plans/delete/{id}', [AdminController::class, 'deletePlan']);
    Route::get('/plans/toggle/{id}', [AdminController::class, 'togglePlan']);

    Route::post('/banners/store', [AdminController::class, 'storeBanner']);
    Route::post('/banners/update', [AdminController::class, 'updateBanner']);
    Route::get('/banners/delete/{id}', [AdminController::class, 'deleteBanner']);
    Route::get('/banners/toggle/{id}', [AdminController::class, 'toggleBanner']);

    Route::post('/home-editor/store', [AdminController::class, 'storeBanner']);
    Route::post('/home-editor/update', [AdminController::class, 'updateBanner']);
    Route::post('/home-editor/upload-music', [AdminController::class, 'uploadMusic']);
    Route::get('/home-editor/delete/{id}', [AdminController::class, 'deleteBanner']);
    Route::get('/home-editor/toggle/{id}', [AdminController::class, 'toggleBanner']);
    Route::post('/home-editor/transit-music/store', [AdminController::class, 'storeTransitMusic'])->name('admin.transit-music.store');
    Route::get('/home-editor/transit-music/delete/{id}', [AdminController::class, 'deleteTransitMusic'])->name('admin.transit-music.delete');

    Route::post('/notifications/store', [AdminController::class, 'storeNotification']);
    Route::get('/notifications/delete/{id}', [AdminController::class, 'deleteNotification']);

    Route::post('/cms/store', [AdminController::class, 'storeCmsPage']);
    Route::post('/cms/update', [AdminController::class, 'updateCmsPage']);
    Route::get('/cms/delete/{id}', [AdminController::class, 'deleteCmsPage']);
    Route::get('/cms/toggle/{id}', [AdminController::class, 'toggleCmsPage']);

    Route::post('/contact/store', [AdminController::class, 'storeContact']);
    Route::get('/contact/delete/{id}', [AdminController::class, 'deleteContact']);
    Route::get('/contact/toggle/{id}', [AdminController::class, 'toggleContact']);

    Route::post('/subscribers/store', [AdminController::class, 'storeSubscriber']);
    Route::get('/subscribers/delete/{id}', [AdminController::class, 'deleteSubscriber']);
    Route::get('/subscribers/toggle/{id}', [AdminController::class, 'toggleSubscriber']);

    Route::post('/settings/update', [AdminController::class, 'updateSettings']);
    Route::post('/profile/change-password', [AdminController::class, 'changePassword']);
    Route::get('/settings/activity-logs', [AdminController::class, 'activityLogs']);


    // Durations legacy redirect
    Route::get('/durations', fn() => redirect('/admin/settings/preferences/durations'));
    Route::get('/durations/toggle/{id}', [AdminController::class, 'toggleDuration']);
    Route::get('/durations/delete/{id}', [AdminController::class, 'deleteDuration']);
    Route::get('/profile', [AdminController::class, 'adminProfile']);
    Route::post('/profile/update', [AdminController::class, 'updateProfile']);

    // Offer Stickers
        Route::get('/offer-stickers', [AdminController::class, 'offerStickers']);
        Route::post('/offer-stickers/store', [AdminController::class, 'storeOfferSticker']);
        Route::post('/offer-stickers/update', [AdminController::class, 'updateOfferSticker']);
        Route::get('/offer-stickers/delete/{id}', [AdminController::class, 'deleteOfferSticker']);
        Route::get('/offer-stickers/toggle/{id}', [AdminController::class, 'toggleOfferSticker']);

        // Gallery
        Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');
        Route::get('/api/gallery', [AdminController::class, 'apiGallery'])->name('admin.api.gallery');
        Route::post('/gallery/upload', [AdminController::class, 'uploadMedia'])->name('admin.gallery.upload');
        Route::post('/gallery/create-folder', [AdminController::class, 'createFolder'])->name('admin.gallery.create-folder');
        Route::post('/gallery/move', [AdminController::class, 'moveMedia'])->name('admin.gallery.move');
        Route::post('/gallery/delete', [AdminController::class, 'deleteMedia'])->name('admin.gallery.delete');
    });
});

// ─── AGENT ROUTES ────────────────────────────────────────────────────────────
Route::prefix('agent')->name('agent.')->group(function () {
    // --- Auth (no middleware) ---
    Route::get('/login',  [AgentController::class, 'login'])->name('login');
    Route::post('/login', [AgentController::class, 'loginSubmit'])->name('login.submit');
    Route::get('/signup',  [AgentController::class, 'signup'])->name('signup');
    Route::post('/signup', [AgentController::class, 'signupSubmit'])->name('signup.submit');
    Route::post('/signup/resend-otp', [AgentController::class, 'resendSignupOtp'])->name('signup.resend-otp');
    
    Route::match(['GET', 'POST'], '/logout',  [AgentController::class, 'logout'])->name('logout');

    Route::get('/forgot-password', [AgentController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AgentController::class, 'forgotPasswordSubmit'])->name('forgot-password.submit');
    Route::get('/reset-password/{token}', [AgentController::class, 'resetPassword'])->name('reset-password');
    Route::post('/reset-password', [AgentController::class, 'resetPasswordSubmit'])->name('reset-password.submit');

    // --- Protected agent pages ---
    Route::middleware(['agent.auth', 'agent.profile_complete'])->group(function () {
        Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
        Route::get('/about', [AgentController::class, 'about'])->name('about');
        Route::get('/add-branch', [AgentController::class, 'addBranch'])->name('add-branch');
        Route::get('/edit-branch/{id}', [AgentController::class, 'editBranch'])->name('edit-branch');
        Route::get('/add-hotel', [AgentController::class, 'addHotel'])->name('add-hotel');
        Route::post('/branch/store', [AgentController::class, 'storeBranch'])->name('branch.store');
        Route::post('/branch/update/{id}', [AgentController::class, 'updateBranch'])->name('branch.update');
        Route::get('/branch/delete/{id}', [AgentController::class, 'deleteBranch'])->name('branch.delete');
        Route::get('/branch', [AgentController::class, 'branch'])->name('branch');
        Route::get('/edit-images', [AgentController::class, 'editImages'])->name('edit-images');
        Route::get('/edit-itinerary', [AgentController::class, 'editItinerary'])->name('edit-itinerary');
        Route::get('/packages/create', [AgentController::class, 'createPackage'])->name('packages.create');
        Route::get('/packages/edit/{id}', [AgentController::class, 'editPackage'])->name('packages.edit');
        Route::post('/packages/store', [AgentController::class, 'storePackage'])->name('packages.store');
        Route::post('/packages/update', [AgentController::class, 'updatePackage'])->name('packages.update');
        Route::get('/packages/toggle/{id}', [AgentController::class, 'togglePackage'])->name('packages.toggle');
        Route::get('/feedback', [AgentController::class, 'feedback'])->name('feedback');
        Route::post('/feedback/store', [AgentController::class, 'storeFeedback'])->name('feedback.store');
        Route::post('/feedback/update/{id}', [AgentController::class, 'updateFeedback'])->name('feedback.update');
        Route::get('/feedback/delete/{id}', [AgentController::class, 'deleteFeedback'])->name('feedback.delete');
        Route::get('/gallery', [AgentController::class, 'gallery'])->name('gallery');
        Route::get('/api/gallery', [AgentController::class, 'apiGallery'])->name('api.gallery');
        Route::post('/gallery/upload', [AgentController::class, 'uploadMedia'])->name('gallery.upload');
        Route::post('/gallery/create-folder', [AgentController::class, 'createFolder'])->name('gallery.create-folder');
        Route::post('/gallery/move', [AgentController::class, 'moveMedia'])->name('gallery.move');
        Route::post('/gallery/delete', [AgentController::class, 'deleteMedia'])->name('gallery.delete');
        Route::get('/hotels', [AgentController::class, 'hotels'])->name('hotels');
        Route::post('/hotels/store', [AgentController::class, 'storeHotel'])->name('hotels.store');
        Route::post('/hotels/update', [AgentController::class, 'updateHotel'])->name('hotels.update');
        Route::post('/hotels/delete/{id}', [AgentController::class, 'deleteHotel'])->name('hotels.delete');
        Route::get('/invoice', [AgentController::class, 'invoice'])->name('invoice');
        Route::get('/invoice/{id}/download', [AgentController::class, 'downloadInvoice'])->name('invoice.download');
        Route::get('/leads', [AgentController::class, 'leads'])->name('leads');
        Route::post('/leads/update', [AgentController::class, 'updateLead'])->name('leads.update');
        Route::post('/leads/delete/{id}', [AgentController::class, 'deleteLead'])->name('leads.delete');
        Route::get('/contact', function() { return redirect()->route('agent.leads'); })->name('contact');
        Route::post('/contact/update', [AgentController::class, 'updateContact'])->name('contact.update');
        Route::get('/my-packages', [AgentController::class, 'myPackages'])->name('my-packages');
        Route::get('/notifications', [AgentController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [AgentController::class, 'markNotificationsRead'])->name('notifications.mark-read');
        Route::get('/payment', [AgentController::class, 'payment'])->name('payment');
        Route::get('/checkout', [AgentController::class, 'checkout'])->name('checkout');
        Route::post('/checkout/process', [AgentController::class, 'processCheckout'])->name('checkout.process');
        Route::post('/payment/payu-success', [AgentController::class, 'payuSuccess'])->name('payment.payu-success');
        Route::post('/payment/payu-failure', [AgentController::class, 'payuFailure'])->name('payment.payu-failure');
        Route::post('/payment', [AgentController::class, 'upgradePlan']);
        Route::post('/payment/upgrade', [AgentController::class, 'upgradePlan'])->name('payment.upgrade');
        Route::get('/profile', function() { return redirect()->route('agent.settings'); })->name('profile');
        Route::get('/services', [AgentController::class, 'services'])->name('services');
        Route::post('/services/toggle', [AgentController::class, 'toggleAgentService'])->name('services.toggle');
        Route::post('/services/add', [AgentController::class, 'addAgentService'])->name('services.add');
        Route::get('/settings', [AgentController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [AgentController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/password', [AgentController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/password/verify', [AgentController::class, 'verifyPasswordOtp'])->name('settings.password.verify');
        Route::get('/settings/profile-images', [AgentController::class, 'profileImages'])->name('profile-images');
        Route::post('/settings/profile-images', [AgentController::class, 'storeProfileImage'])->name('profile-images.store');
        Route::get('/settings/profile-images/{id}/delete', [AgentController::class, 'deleteProfileImage'])->name('profile-images.delete');
    });
});

Route::get('/tour/{slug}', function($slug) {
    return view('tour.show', compact('slug'));
})->name('tour.show');

Route::get('/package/{id}', [PackageController::class, 'show']);

Route::get('/packages/{slug}', function ($slug) {
    try {
        $dbPkg = \App\Models\Package::find($slug);
        if (!$dbPkg && !is_numeric($slug)) {
            $dbPkg = \App\Models\Package::where('title', 'LIKE', str_replace('-', ' ', $slug))->first();
        }
        if ($dbPkg) {
            $dbPkg->increment('clicks');
            
            if (\Illuminate\Support\Facades\Auth::check()) {
                try {
                    \Illuminate\Support\Facades\DB::table('user_viewed_packages')->updateOrInsert(
                        ['user_id' => \Illuminate\Support\Facades\Auth::id(), 'package_id' => $dbPkg->id],
                        ['viewed_at' => now(), 'updated_at' => now()]
                    );
                } catch (\Exception $e) {}
            }
            
            $gallery = [];
            if ($dbPkg->gallery) {
                $gallery = json_decode($dbPkg->gallery, true) ?: [];
            }
            $included = [];
            if ($dbPkg->included) {
                $included = json_decode($dbPkg->included, true) ?: [];
            }
            $excluded = [];
            if ($dbPkg->excluded) {
                $excluded = json_decode($dbPkg->excluded, true) ?: [];
            }
            $itinerary = [];
            if ($dbPkg->itinerary) {
                $itinerary = json_decode($dbPkg->itinerary, true) ?: [];
            }
            
            $package = [
                'id'         => $dbPkg->id,
                'slug'       => $slug,
                'title'      => $dbPkg->title,
                'image'      => $dbPkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200',
                'duration'   => $dbPkg->duration,
                'groupSize'  => $dbPkg->group_size ?? '4-6 guest',
                'rating'     => $dbPkg->rating ?? '4.8',
                'reviews'    => $dbPkg->reviews ?? '10',
                'price'      => $dbPkg->price,
                'oldPrice'   => $dbPkg->old_price,
                'currency'   => $dbPkg->currency ?? 'INR',
                'badge'      => $dbPkg->badge,
                'category'   => $dbPkg->category,
                'tour_type'  => $dbPkg->group_size ?? null,
                'city'       => $dbPkg->location,
                'theme'      => $dbPkg->theme ?? null,
                'holiday_type'=> $dbPkg->holiday_type ?? null,
                'departure_city' => $dbPkg->departure_city ?? null,
                'departure_state'=> $dbPkg->departure_state ?? null,
                'activities' => [],
                'overview'   => $dbPkg->overview ?? "Experience the incredible beauty and culture of {$dbPkg->title}. This package offers an unforgettable journey filled with stunning landscapes, historic sites, and amazing local cuisine.",
                'highlights' => !empty($dbPkg->highlights) ? json_decode($dbPkg->highlights, true) : [
                    "Guided city tour of {$dbPkg->title}",
                    "Visit top attractions and hidden gems",
                    "Authentic local dining experience",
                    "Comfortable 4-star accommodation",
                    "Airport transfers included",
                ],
                'gallery'    => $gallery,
                'brochure'   => $dbPkg->brochure,
                'included'   => $included,
                'excluded'   => $excluded,
                'itinerary'  => $itinerary,
                'agent'      => is_string($dbPkg->agent) ? json_decode($dbPkg->agent, true) : ($dbPkg->agent ?? 'Miths Holidays'),
                'validity'   => $dbPkg->validity ?? null,
                'sightseeing'=> $dbPkg->sightseeing ?? null,
                'sightseeing_list'=> json_decode($dbPkg->sightseeing_list ?? '[]', true) ?: [],
                'hotels'     => json_decode($dbPkg->hotels ?? '[]', true) ?: [],
                'amenities'  => json_decode($dbPkg->amenities ?? '[]', true) ?: [],
                'meals'      => json_decode($dbPkg->meals ?? '[]', true) ?: [],
                'transfers'  => json_decode($dbPkg->transfers ?? '[]', true) ?: [],
                'keywords'   => json_decode($dbPkg->keywords ?? '[]', true) ?: [],
                'editorial_itinerary' => $dbPkg->editorial_itinerary ?? null,
                'about_tours' => $dbPkg->about_tours ?? null,
                'terms' => $dbPkg->terms ?? null,
            ];

            if (empty($package['gallery'])) {
                $package['gallery'] = [$package['image']];
            } else {
                array_unshift($package['gallery'], $package['image']);
                $package['gallery'] = array_values(array_unique($package['gallery']));
            }

            $agentPackages = [];
            $agentId = null;
            $agentName = null;
            if ($dbPkg->agent) {
                $parsedAgent = is_string($dbPkg->agent) ? json_decode($dbPkg->agent, true) : $dbPkg->agent;
                if (is_array($parsedAgent)) {
                    $agentId = $parsedAgent['id'] ?? null;
                    $agentName = $parsedAgent['name'] ?? null;
                }
            }

            if ($agentId || $agentName) {
                $agentPackages = \App\Models\Package::where('status', 'Active')
                    ->where('id', '!=', $dbPkg->id)
                    ->get()
                    ->filter(function($p) use ($agentId, $agentName) {
                        $pAgent = is_string($p->agent) ? json_decode($p->agent, true) : $p->agent;
                        if (!is_array($pAgent)) return false;
                        if ($agentId && isset($pAgent['id']) && $pAgent['id'] == $agentId) return true;
                        if ($agentName && isset($pAgent['name']) && strtolower(trim($pAgent['name'])) == strtolower(trim($agentName))) return true;
                        return false;
                    })
                    ->take(8)
                    ->values()
                    ->toArray();
            }

            if (empty($agentPackages)) {
                $agentPackages = \App\Models\Package::where('status', 'Active')
                    ->where('id', '!=', $dbPkg->id)
                    ->inRandomOrder()
                    ->take(8)
                    ->get()
                    ->toArray();
            }
            
            $similarPackages = [];
            
            $hasFilters = false;
            $similarPackagesQuery = \App\Models\Package::where('status', 'Active')
                ->where('id', '!=', $dbPkg->id);
                
            $similarPackagesQuery->where(function($query) use ($dbPkg, &$hasFilters) {
                if (!empty($dbPkg->title)) {
                    $words = array_filter(explode(' ', str_replace(['-','_'], ' ', $dbPkg->title)), function($word) {
                        return strlen(trim($word)) > 2 && !in_array(strtolower(trim($word)), ['tour', 'package', 'trip', 'holiday', 'with', 'from', 'for']);
                    });
                    foreach ($words as $word) {
                        $query->orWhere('title', 'like', '%' . trim($word) . '%');
                        $hasFilters = true;
                    }
                }
            });
            
            if ($hasFilters) {
                $similarPackages = $similarPackagesQuery->inRandomOrder()->take(8)->get()->toArray();
            }

            return view('packages.show', ['package' => $package, 'agentPackages' => $agentPackages, 'similarPackages' => $similarPackages]);
        }
    } catch (\Exception $e) {}
    
    abort(404);
})->name('packages.show');

Route::get('/audio/bg_music.mp3', function () { return response()->file(public_path('audio/bg_music.mp3')); });

Route::get('/uploads/{path}', function ($path) {
    $fullPath = base_path('uploads/' . $path);
    if (file_exists($fullPath)) {
        return response()->file($fullPath);
    }
    abort(404);
})->where('path', '.*');

Route::get('/resume/view/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (file_exists($fullPath)) {
        return response()->file($fullPath);
    }
    abort(404);
})->where('path', '.*');




