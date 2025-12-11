<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');

    Route::get('register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('register', [AuthController::class, 'storeRegister'])->name('register.store');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

    // Student Routes
    Route::middleware('role:mahasiswa')->name('student.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        
        // Smart Logbook (Scan Masuk)
        Route::get('/logbook', [\App\Http\Controllers\Student\ScanController::class, 'index'])->name('logbook.index');
        Route::post('/logbook', [\App\Http\Controllers\Student\ScanController::class, 'store'])->name('logbook.store');

        // Self-Service Borrowing
        Route::get('/borrow', [\App\Http\Controllers\Student\LoanController::class, 'create'])->name('borrow.index');
        Route::post('/borrow/lookup', [\App\Http\Controllers\Student\LoanController::class, 'lookup'])->name('borrow.lookup');
        Route::post('/borrow', [\App\Http\Controllers\Student\LoanController::class, 'store'])->name('borrow.store');

        // Exit Pass (Ticket)
        Route::get('/ticket/{loan}', [\App\Http\Controllers\Student\LoanController::class, 'showTicket'])->name('ticket.show');
        Route::get('/ticket/{loan}/status', [\App\Http\Controllers\Student\LoanController::class, 'checkStatus'])->name('ticket.status');

        // My Collection
        Route::get('/collection', [\App\Http\Controllers\Student\LoanController::class, 'collection'])->name('collection.index');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');

        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\Student\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{id}/read', [\App\Http\Controllers\Student\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Student\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Book Management
        Route::resource('books', \App\Http\Controllers\Admin\BookController::class);

        // Circulation Management
        Route::get('/loans/search-user', [\App\Http\Controllers\Admin\LoanController::class, 'searchUser'])->name('loans.searchUser');
        Route::get('/loans/search-book', [\App\Http\Controllers\Admin\LoanController::class, 'searchBook'])->name('loans.searchBook');
        Route::resource('loans', \App\Http\Controllers\Admin\LoanController::class)->except(['show', 'destroy']);
        Route::put('loans/{loan}/return', [\App\Http\Controllers\Admin\LoanController::class, 'returnBook'])->name('loans.return');
        Route::put('loans/{loan}/approve', [\App\Http\Controllers\Admin\LoanController::class, 'approve'])->name('loans.approve');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Reports
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    });
});
