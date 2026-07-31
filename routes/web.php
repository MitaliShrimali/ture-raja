<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
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

Route::post('/login/submit', [UserController::class, 'loginSubmit'])->name('login.submit');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// ─── ADMIN ROUTES ────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login', ['type' => 'admin']);
    });
    Route::get('/signup', function () {
        return view('admin.signup', ['type' => 'admin']);
    });
    Route::post('/login/submit', [UserController::class, 'loginSubmit'])->name('admin.login.submit');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/packages/approve/{id}', [AdminController::class, 'approvePackage'])->name('admin.package.approve');
        Route::get('/packages/decline/{id}', [AdminController::class, 'declinePackage'])->name('admin.package.decline');
        Route::get('/packages/pending', [AdminController::class, 'pendingPackages'])->name('admin.packages.pending');
        Route::get('/packages/view/{id}', [AdminController::class, 'viewPackage'])->name('admin.packages.view');
        
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
    });
});

// ─── AGENT ROUTES ────────────────────────────────────────────────────────────
Route::prefix('agent')->name('agent.')->group(function () {
    // --- Auth (no middleware) ---
    Route::get('/login',  [AgentController::class, 'login'])->name('login');
    Route::post('/login', [AgentController::class, 'loginSubmit'])->name('login.submit');
    Route::get('/signup',  [AgentController::class, 'signup'])->name('signup');
    Route::post('/signup', [AgentController::class, 'signupSubmit'])->name('signup.submit');
    Route::get('/logout',  [AgentController::class, 'logout'])->name('logout');

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
        Route::post('/payment', [AgentController::class, 'upgradePlan']);
        Route::post('/payment/upgrade', [AgentController::class, 'upgradePlan'])->name('payment.upgrade');
        Route::get('/profile', function() { return redirect()->route('agent.settings'); })->name('profile');
        Route::get('/services', [AgentController::class, 'services'])->name('services');
        Route::post('/services/toggle', [AgentController::class, 'toggleAgentService'])->name('services.toggle');
        Route::post('/services/add', [AgentController::class, 'addAgentService'])->name('services.add');
        Route::get('/settings', [AgentController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [AgentController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/password', [AgentController::class, 'updatePassword'])->name('settings.password');
    });
});

Route::get('/tour/{slug}', function($slug) {
    return view('tour.show', compact('slug'));
})->name('tour.show');

Route::get('/package/{id}', [PackageController::class, 'show']);

Route::get('/packages/{slug}', function ($slug) {
    try {
        \App\Models\Package::where('slug', $slug)->increment('clicks');
    } catch (\Exception $e) {
        // ignore
    }

    $allPackages = [
        'monaco-luxury-tour' => [
            'slug'       => 'monaco-luxury-tour',
            'title'      => 'Monaco Luxury Tour Package',
            'image'      => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '2 days 3 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.96',
            'reviews'    => '672',
            'price'      => 44825,
            'oldPrice'   => 59825,
            'badge'      => 'Top Rated',
            'category'   => 'international',
            'tour_type'  => 'Cruise Package',
            'city'       => 'Monaco',
            'theme'      => 'Honeymoon',
            'activities' => ['Cable Car / Rope way', 'Nature'],
            'overview'   => 'Experience the pinnacle of luxury in the glamorous Monaco. Walk along the famous Monte Carlo Casino, enjoy the breathtaking Mediterranean coastline, and immerse yourself in Formula 1 culture at every corner of this stunning principality.',
            'highlights' => [
                'Visit the iconic Monte Carlo Casino and Royal Palace',
                'Sunset cruise along the Mediterranean coast',
                'Exclusive dining at award-winning restaurants',
                'Private guide for the entire trip',
                'Carry on from any major Indian airport',
            ],
            'included'   => ['Beverages, drinking water, hot/key tea and buffet lunch', 'Local tours', 'Hotel pickup and drop off', 'Insurance/Transfer in a private car', 'Sick drinks', 'Tour Guide'],
            'excluded'   => ['Airfare', 'Tips', 'Alcoholic Beverages'],
            'itinerary'  => [
                ['title' => 'Airport Pick Up', 'desc' => 'Arrive at Nice Côte d\'Azur Airport and transfer to your luxury hotel in Monaco.'],
                ['title' => 'Temple & River Cruise', 'desc' => 'Morning guided tour of the Royal Palace, followed by an evening Mediterranean cruise.'],
                ['title' => 'Casino & Overnight Stay', 'desc' => 'Explore Monte Carlo Casino, enjoy fine dining, and overnight at your 5-star hotel.'],
            ],
        ],
        'vietnam-tour-package' => [
            'slug'       => 'vietnam-tour-package',
            'title'      => 'Vietnam Tour Package',
            'image'      => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '3 days 3 nights',
            'groupSize'  => '2-3 guest',
            'rating'     => '4.91',
            'reviews'    => '670',
            'price'      => 17320,
            'oldPrice'   => 25320,
            'badge'      => 'Best Sale',
            'category'   => 'international',
            'tour_type'  => 'Flight Package',
            'city'       => 'Hanoi',
            'theme'      => 'Adventure',
            'activities' => ['Water Activities', 'Nature'],
            'overview'   => 'Discover the stunning Ha Long Bay, cruise through emerald waters dotted with towering limestone karsts, visit Monkey Island, and experience the vibrant local culture of Vietnam.',
            'highlights' => [
                'Ha Long Bay cruise with stunning karst landscapes',
                'Visit Monkey Island and pristine beaches',
                'Kayaking through hidden lagoons',
                'Authentic Vietnamese cooking class',
                'All-inclusive cruise package',
            ],
            'included'   => ['All meals on cruise', 'Kayaking & activities', 'Hotel transfers', 'Tour guide', 'Entry fees'],
            'excluded'   => ['International flights', 'Vietnam Visa fees', 'Personal expenses'],
            'itinerary'  => [
                ['title' => 'Arrival & Embarkation', 'desc' => 'Arrive at Hanoi, transfer to Ha Long Bay, and board your luxury cruise.'],
                ['title' => 'Ha Long Bay Exploration', 'desc' => 'Kayaking through limestone caves, visit Monkey Island and floating villages.'],
                ['title' => 'Sunrise Tai Chi & Departure', 'desc' => 'Morning Tai Chi on deck, breakfast, and transfer back to Hanoi.'],
            ],
        ],
        'char-dham-yatra' => [
            'slug'       => 'char-dham-yatra',
            'title'      => 'Char Dham Yatra Package',
            'image'      => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '7 days 6 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.86',
            'reviews'    => '656',
            'price'      => 15463,
            'oldPrice'   => 19000,
            'badge'      => '25% Off',
            'category'   => 'religious',
            'tour_type'  => 'Bus Package',
            'city'       => 'Haridwar',
            'theme'      => 'Religious',
            'activities' => ['Hill Station', 'Religious'],
            'overview'   => 'Embark on the sacred Char Dham Yatra — a spiritual journey to Yamunotri, Gangotri, Kedarnath, and Badrinath nestled in the majestic Himalayas. Experience divine serenity and breathtaking mountain scenery.',
            'highlights' => [
                'Visit all four sacred Dhams: Yamunotri, Gangotri, Kedarnath, Badrinath',
                'Helicopter option available for Kedarnath',
                'Comfortable stays at each Dham',
                'Experienced religious guide throughout',
                'Starting from Haridwar/Rishikesh',
            ],
            'included'   => ['Accommodation (6 nights)', 'Daily breakfast & dinner', 'AC vehicle', 'Guide', 'All darshan arrangements'],
            'excluded'   => ['Helicopter charges', 'Personal pooja items', 'Tips', 'Lunch'],
            'itinerary'  => [
                ['title' => 'Haridwar to Yamunotri', 'desc' => 'Depart from Haridwar. Drive to Janki Chatti and trek to Yamunotri temple.'],
                ['title' => 'Yamunotri to Gangotri', 'desc' => 'Morning aarti at Yamunotri, drive to Uttarkashi, overnight stay.'],
                ['title' => 'Gangotri Darshan', 'desc' => 'Visit the Gangotri temple at the source of the river Ganga.'],
                ['title' => 'Kedarnath Trek', 'desc' => 'Drive to Gaurikund, trek 16 km (or helicopter) to Kedarnath temple.'],
                ['title' => 'Kedarnath to Badrinath', 'desc' => 'Morning darshan at Kedarnath, drive to Badrinath via Chopta.'],
                ['title' => 'Badrinath Darshan', 'desc' => 'Early morning abhishek and darshan at Badrinath, visit Mana Village.'],
                ['title' => 'Return to Haridwar', 'desc' => 'Drive back to Haridwar with divine memories and blessings.'],
            ],
        ],
        'goa-beach-package' => [
            'slug'       => 'goa-beach-package',
            'title'      => 'Goa Beach Holiday Package',
            'image'      => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '2 days 3 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.74',
            'reviews'    => '631',
            'price'      => 14755,
            'oldPrice'   => 19825,
            'badge'      => 'Top Rated',
            'category'   => 'domestic',
            'tour_type'  => 'Flight Package',
            'city'       => 'Goa',
            'theme'      => 'Honeymoon',
            'activities' => ['Water Activities', 'Rides and Thrill'],
            'overview'   => 'Sun, sand, and surf await you in Goa! Enjoy pristine beaches, vibrant nightlife, delicious seafood, and a blend of Indian and Portuguese culture in this ultimate beach holiday.',
            'highlights' => [
                'North Goa beaches: Baga, Calangute, Anjuna',
                'South Goa tranquil beaches: Palolem, Colva',
                'Water sports: parasailing, jet ski, banana ride',
                'Old Goa heritage churches tour',
                'Sunset cruise on the Mandovi River',
            ],
            'included'   => ['Hotel stay (3 nights)', 'Breakfast daily', 'Airport transfers', 'Sightseeing by AC cab', 'Water sports (1 session)'],
            'excluded'   => ['Flights', 'Personal expenses', 'Lunch & dinner', 'Tips'],
            'itinerary'  => [
                ['title' => 'Arrival & North Goa Beach', 'desc' => 'Arrive at Goa airport, check in, relax at Calangute/Baga beach, enjoy the nightlife.'],
                ['title' => 'Sightseeing & Water Sports', 'desc' => 'Old Goa churches, spice plantation, water sports session, sunset cruise.'],
                ['title' => 'South Goa & Departure', 'desc' => 'Morning at Palolem beach, checkout, and drop to airport.'],
            ],
        ],
        'spiti-valley-adventure' => [
            'slug'       => 'spiti-valley-adventure',
            'title'      => 'Spiti Valley Package',
            'image'      => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '3 days 3 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.51',
            'reviews'    => '617',
            'price'      => 24840,
            'oldPrice'   => 31825,
            'badge'      => 'Best Sale',
            'category'   => 'adventure',
            'tour_type'  => 'Train Package',
            'city'       => 'Manali',
            'theme'      => 'Adventure',
            'activities' => ['Jeep Safari', 'Hill Station'],
            'overview'   => 'Journey into the cold desert of Spiti Valley — one of the world\'s highest inhabited regions. Experience ancient monasteries, dramatic landscapes, starry nights, and raw Himalayan adventure.',
            'highlights' => [
                'Key Monastery — oldest in Spiti',
                'Chandratal Lake at 14,100 ft',
                'Kaza market and local village walks',
                'Tabo Monastery UNESCO heritage site',
                'Off-road adventure on mountain trails',
            ],
            'included'   => ['Accommodation (3 nights)', 'All meals', '4x4 vehicle', 'Experienced local guide', 'Permits'],
            'excluded'   => ['Flights to Manali/Shimla', 'Travel insurance', 'Personal gear'],
            'itinerary'  => [
                ['title' => 'Manali to Kaza', 'desc' => 'Cross Rohtang Pass, drive through Lahaul valley to Kaza. Check in and rest.'],
                ['title' => 'Kaza Sightseeing', 'desc' => 'Visit Key Monastery, Kibber village, Chicham bridge, and Pin Valley.'],
                ['title' => 'Chandratal & Departure', 'desc' => 'Drive to Chandratal Lake, enjoy the surreal beauty, return to Manali.'],
            ],
        ],
        'swiss-paris-delight' => [
            'slug'       => 'swiss-paris-delight',
            'title'      => 'Swiss Paris Delight',
            'image'      => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '7 days 6 nights',
            'groupSize'  => '4-6 guest',
            'rating'     => '4.29',
            'reviews'    => '608',
            'price'      => 51247,
            'oldPrice'   => null,
            'badge'      => '25% Off',
            'category'   => 'international',
            'tour_type'  => 'Flight Package',
            'city'       => 'Paris',
            'theme'      => 'Family/Group',
            'activities' => ['Cable Car / Rope way', 'Nature'],
            'overview'   => 'The ultimate European double-header! Explore the romantic streets of Paris and then escape to the breathtaking Swiss Alps. Eiffel Tower, Louvre, Jungfraujoch, and Interlaken await.',
            'highlights' => [
                'Eiffel Tower visit with skip-the-line tickets',
                'Louvre Museum guided tour',
                'Swiss Alps — Jungfraujoch (Top of Europe)',
                'Interlaken adventure activities',
                'Seine River dinner cruise',
            ],
            'included'   => ['6 nights hotel (3 Paris + 3 Switzerland)', 'Breakfast daily', 'Europe Schengen visa assistance', 'All transfers', 'City tours'],
            'excluded'   => ['International flights', 'Travel insurance', 'Personal shopping'],
            'itinerary'  => [
                ['title' => 'Arrive Paris', 'desc' => 'Land at Charles de Gaulle, hotel check-in, evening Seine cruise.'],
                ['title' => 'Paris City Tour', 'desc' => 'Eiffel Tower, Louvre Museum, Notre Dame, Champs-Élysées.'],
                ['title' => 'Versailles Day Trip', 'desc' => 'Palace of Versailles and gardens tour.'],
                ['title' => 'Paris to Zurich', 'desc' => 'High-speed train to Switzerland. Check in at Interlaken.'],
                ['title' => 'Jungfraujoch Excursion', 'desc' => 'Top of Europe — snow, glaciers, and panoramic Alps views.'],
                ['title' => 'Lucerne Free Day', 'desc' => 'Chapel Bridge, Lion Monument, shopping, lake cruise.'],
                ['title' => 'Departure', 'desc' => 'Transfer to Zurich airport for your return flight.'],
            ],
        ],
    ];

    // Try to find the package in the database by matching title slugs efficiently
    $matched = DB::table('packages')->select('id', 'title')->get()->first(function($p) use ($slug) {
        return \Illuminate\Support\Str::slug($p->title) === $slug;
    });

    $dbPkg = $matched ? DB::table('packages')->where('id', $matched->id)->first() : null;

    if ($dbPkg) {
        // Track package view for logged-in users
        if (\Illuminate\Support\Facades\Auth::check()) {
            try {
                DB::table('user_viewed_packages')->updateOrInsert(
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
            'slug'       => $slug,
            'title'      => $dbPkg->title,
            'image'      => $dbPkg->image ?: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200',
            'duration'   => $dbPkg->duration,
            'groupSize'  => $dbPkg->group_size ?? '4-6 guest',
            'rating'     => $dbPkg->rating ?? '4.8',
            'reviews'    => $dbPkg->reviews ?? '10',
            'price'      => $dbPkg->price,
            'oldPrice'   => $dbPkg->old_price,
            'currency'   => property_exists($dbPkg, 'currency') && $dbPkg->currency ? $dbPkg->currency : 'INR',
            'badge'      => $dbPkg->badge,
            'category'   => $dbPkg->category,
            'tour_type'  => 'Flight Package',
            'city'       => $dbPkg->location,
            'theme'      => property_exists($dbPkg, 'theme') && $dbPkg->theme ? $dbPkg->theme : 'Adventure',
            'holiday_type'=> property_exists($dbPkg, 'holiday_type') ? $dbPkg->holiday_type : null,
            'activities' => [],
            'overview'   => "Experience the incredible beauty and culture of {$dbPkg->title}. This package offers an unforgettable journey filled with stunning landscapes, historic sites, and amazing local cuisine.",
            'highlights' => [
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
            'validity'   => property_exists($dbPkg, 'validity') ? $dbPkg->validity : null,
            'sightseeing'=> property_exists($dbPkg, 'sightseeing') ? $dbPkg->sightseeing : null,
            'hotels'     => property_exists($dbPkg, 'hotels') && json_decode($dbPkg->hotels, true) ? json_decode($dbPkg->hotels, true) : [],
            'amenities'  => json_decode($dbPkg->amenities, true) ?: [],
            'meals'      => json_decode($dbPkg->meals, true) ?: [],
            'transfers'  => json_decode($dbPkg->transfers, true) ?: [],
            'keywords'   => json_decode($dbPkg->keywords, true) ?: [],
            'editorial_itinerary' => property_exists($dbPkg, 'editorial_itinerary') ? $dbPkg->editorial_itinerary : null,
            'terms' => property_exists($dbPkg, 'terms') ? $dbPkg->terms : null,
        ];

        // Fill defaults if empty
        if (empty($package['gallery'])) {
            $package['gallery'] = [
                $package['image'],
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200',
                'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200',
                'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&q=80&w=1200',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&q=80&w=1200'
            ];
        } else {
            array_unshift($package['gallery'], $package['image']);
            $package['gallery'] = array_values(array_unique($package['gallery']));
        }
        if (empty($package['included'])) {
            $package['included'] = ['Hotel Stay', 'Daily Breakfast', 'Tour Guide', 'Transfers'];
        }
        if (empty($package['excluded'])) {
            $package['excluded'] = ['Flights', 'Personal Expenses', 'Visa Fees'];
        }
        if (empty($package['itinerary'])) {
            $package['itinerary'] = [
                ['title' => 'Arrival & Check-in', 'desc' => "Arrive at {$dbPkg->title}, transfer to your hotel and relax."],
                ['title' => 'City Exploration', 'desc' => 'Full day guided tour exploring major landmarks.'],
                ['title' => 'Leisure & Departure', 'desc' => 'Free time for shopping before transferring to the airport.'],
            ];
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
        return view('packages.show', ['package' => $package, 'agentPackages' => $agentPackages]);
    }

    if (!isset($allPackages[$slug])) {
        $title = ucwords(str_replace('-', ' ', $slug));
        $allPackages[$slug] = [
            'slug'       => $slug,
            'title'      => $title . ' Experience',
            'image'      => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=1200',
            'duration'   => '4 days 3 nights',
            'groupSize'  => '2-4 guest',
            'rating'     => '4.8',
            'reviews'    => '320',
            'price'      => rand(15000, 50000),
            'oldPrice'   => null,
            'badge'      => 'Popular',
            'category'   => 'international',
            'tour_type'  => 'Flight Package',
            'city'       => $title,
            'theme'      => 'Honeymoon',
            'activities' => ['Nature', 'Sightseeing'],
            'overview'   => "Experience the incredible beauty and culture of $title. This package offers an unforgettable journey filled with stunning landscapes, historic sites, and amazing local cuisine.",
            'highlights' => [
                "Guided city tour of $title",
                "Visit top attractions and hidden gems",
                "Authentic local dining experience",
                "Comfortable 4-star accommodation",
                "Airport transfers included",
            ],
            'included'   => ['Hotel Stay', 'Daily Breakfast', 'Tour Guide', 'Transfers'],
            'excluded'   => ['Flights', 'Personal Expenses', 'Visa Fees'],
            'terms'      => null,
            'validity'   => null,
            'sightseeing'=> null,
            'hotels'     => [],
            'amenities'  => [],
            'meals'      => [],
            'transfers'  => [],
            'keywords'   => [],
            'itinerary'  => [
                ['title' => 'Arrival & Check-in', 'desc' => "Arrive at $title, transfer to your hotel and relax."],
                ['title' => 'City Exploration', 'desc' => 'Full day guided tour exploring major landmarks.'],
                ['title' => 'Leisure & Departure', 'desc' => 'Free time for shopping before transferring to the airport.'],
            ],
        ];
    }

    try {
        $dbPkg = \App\Models\Package::where('slug', $slug)->first();
        if ($dbPkg) {
            $dbPkg->increment('clicks');
        }
    } catch (\Exception $e) {}

    return view('packages.show', ['package' => $allPackages[$slug]]);
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

