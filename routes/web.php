<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;

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
});
