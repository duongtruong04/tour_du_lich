<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\TourController as PublicTourController;
use App\Http\Controllers\Public\BookingController as PublicBookingController;
use App\Http\Controllers\Public\NewsController as PublicNewsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Tour Routes
Route::get('tours', [PublicTourController::class, 'index'])->name('public.tours.index');
Route::get('tours/{tour:slug}', [PublicTourController::class, 'show'])->name('public.tours.show');
Route::post('tours/{tour}/reviews', [\App\Http\Controllers\Public\ReviewController::class, 'store'])->name('public.reviews.store');

// Public Destination Routes
Route::get('destinations', [\App\Http\Controllers\Public\DestinationController::class, 'index'])->name('public.destinations.index');
Route::get('destinations/{destination:id}', [\App\Http\Controllers\Public\DestinationController::class, 'show'])->name('public.destinations.show');

// Public News Routes
Route::get('news', [PublicNewsController::class, 'index'])->name('public.news.index');
Route::get('news/{news:slug}', [PublicNewsController::class, 'show'])->name('public.news.show');

// Public Promotions Routes
Route::get('khuyen-mai', [\App\Http\Controllers\Public\PromotionController::class, 'index'])->name('public.promotions.index');

// Public Booking & Account Routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('booking/checkout/{departure}', [PublicBookingController::class, 'checkout'])->name('public.bookings.checkout');
    Route::post('booking/process', [PublicBookingController::class, 'process'])->name('public.bookings.process');
    Route::get('booking/success/{code}', [PublicBookingController::class, 'success'])->name('public.bookings.success');
    Route::get('booking/vnpay/return', [PublicBookingController::class, 'vnpayReturn'])->name('public.bookings.vnpayReturn');
    Route::post('booking/confirm-transfer/{code}', [PublicBookingController::class, 'confirmTransfer'])->name('public.bookings.confirmTransfer');
    
    Route::prefix('account')->name('public.account.')->group(function() {
        Route::get('history', [PublicBookingController::class, 'history'])->name('bookings.history');
        Route::get('profile', [\App\Http\Controllers\Public\AccountController::class, 'profile'])->name('profile');
        Route::post('profile', [\App\Http\Controllers\Public\AccountController::class, 'updateProfile'])->name('update_profile');
        Route::post('password', [\App\Http\Controllers\Public\AccountController::class, 'updatePassword'])->name('update_password');
    });
    
    // Legacy route alias to prevent 500 errors
    Route::get('account/history/legacy', [PublicBookingController::class, 'history'])->name('public.bookings.history');

    // E-Tickets
    Route::get('booking/{booking}/tickets', [\App\Http\Controllers\Public\TicketController::class, 'showTickets'])->name('public.tickets.show');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Customer Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// AI Chatbot Route
Route::post('chat/ask', [\App\Http\Controllers\Customer\CustomerChatgptController::class, 'ask'])->name('public.chat.ask');
Route::get('chat/history', [\App\Http\Controllers\Customer\CustomerChatgptController::class, 'history'])->name('public.chat.history');

// Admin Login (dedicated route)
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('tours', TourController::class);
    Route::delete('tours/images/{image}', [TourController::class, 'deleteImage'])->name('tours.delete_image');
    Route::resource('bookings', BookingController::class);
    Route::resource('destinations', DestinationController::class);
    Route::resource('news', NewsController::class);
    Route::resource('news_categories', NewsCategoryController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);
    
    // User & Role Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Payments & Chats
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::get('chats', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chats.index');
    Route::delete('chats/{chat}', [\App\Http\Controllers\Admin\ChatController::class, 'destroy'])->name('chats.destroy');

    // Global Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // Admin Profile
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Tickets
    Route::get('tickets/verify/{ticket_code}', [\App\Http\Controllers\Admin\TicketController::class, 'verify'])->name('tickets.verify');
    Route::post('tickets/checkin/{ticket_code}', [\App\Http\Controllers\Admin\TicketController::class, 'checkIn'])->name('tickets.checkin');
});
