<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Member\BookController as MemberBookController;
use App\Http\Controllers\Member\BookingController;
use App\Http\Controllers\Member\HomeController;
use App\Http\Controllers\Member\LoanController as MemberLoanController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Member\PaymentController;

// ---------------- Public ----------------
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/payment/finish/{booking}', [PaymentController::class, 'finish'])->name('payment.finish');

// ---------------- Auth ----------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp'])->name('password.email');
    Route::get('/reset-password/otp', [PasswordResetController::class, 'showOtp'])->name('password.otp');
    Route::post('/reset-password/otp', [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('/reset-password/otp/resend', [PasswordResetController::class, 'resendOtp'])->name('password.otp.resend');
    Route::get('/reset-password', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    
    // Login dengan Google
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------------- Member (anggota) ----------------
Route::middleware(['auth', 'role:anggota'])->prefix('member')->name('member.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/books', [MemberBookController::class, 'index'])->name('books');
    Route::get('/books/{book}', [MemberBookController::class, 'show'])->name('books.show');
    Route::post('/books/{book}/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('booking.update');
    Route::get('/loans', [MemberLoanController::class, 'index'])->name('loans');
    Route::get('/booking/{booking}/pay', [BookingController::class, 'pay'])->name('booking.pay');
});

// ---------------- Admin ----------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Buku
    Route::get('/books/lookup', [BookController::class, 'lookupIsbn'])->name('books.lookup');
    Route::resource('books', BookController::class)->except('show');

    // Kategori
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Anggota
    Route::resource('users', UserController::class)->except('show');

    // Transaksi peminjaman & denda
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/history', [LoanController::class, 'history'])->name('loans.history');
    Route::post('/loans/confirm', [LoanController::class, 'confirmFromBooking'])->name('loans.confirm');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'confirmReturn'])->name('loans.return');
    Route::patch('/fines/{fine}/pay', [LoanController::class, 'payFine'])->name('fines.pay');
    Route::patch('/bookings/{booking}/reject', [LoanController::class, 'rejectBooking'])->name('bookings.reject');
});
