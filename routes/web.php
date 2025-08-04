<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookRequestController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-specific dashboard routes
    Route::get('/borrower-dashboard', [DashboardController::class, 'borrowerDashboard'])->name('borrower.dashboard');
    Route::get('/lender-dashboard', [DashboardController::class, 'lenderDashboard'])->name('lender.dashboard');
    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    // Debug route to check user role
    Route::get('/debug-user', function () {
        $user = Auth::user();
        return response()->json([
            'authenticated' => Auth::check(),
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : null,
            'user_email' => $user ? $user->email : null,
            'user_role' => $user ? $user->role : null,
        ]);
    });
    
    // Book Routes
    Route::resource('books', BookController::class);
    Route::get('/browse-books', [BookController::class, 'browse'])->name('books.browse');
    Route::get('/home', [BookController::class, 'home'])->name('books.home');
    
    // Book Request Routes (for lenders)
    Route::resource('book-requests', BookRequestController::class)->only([
        'index', 'create', 'store', 'show'
    ]);
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/book-requests', [AdminController::class, 'bookRequests'])->name('book-requests');
        Route::get('/book-requests/{bookRequest}', [AdminController::class, 'showBookRequest'])->name('book-requests.show');
        Route::post('/book-requests/{bookRequest}/approve', [AdminController::class, 'approveBookRequest'])->name('book-requests.approve');
        Route::post('/book-requests/{bookRequest}/reject', [AdminController::class, 'rejectBookRequest'])->name('book-requests.reject');
        Route::get('/approved-books', [AdminController::class, 'approvedBooks'])->name('approved-books');
        Route::patch('/approved-books/{approvedBook}/status', [AdminController::class, 'updateApprovedBookStatus'])->name('approved-books.update-status');
        
        // User Management Routes
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.update-status');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
        Route::patch('/users/{user}/wallet', [AdminController::class, 'updateUserWallet'])->name('users.update-wallet');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    });
});
