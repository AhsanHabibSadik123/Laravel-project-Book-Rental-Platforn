<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's statistics based on role
        $stats = [];
        
        if ($user->role === 'lender') {
            $stats = [
                'total_books' => Book::where('lender_id', $user->id)->count(),
                'rented_books' => Book::where('lender_id', $user->id)->where('status', 'rented')->count(),
                'available_books' => Book::where('lender_id', $user->id)->where('status', 'available')->count(),
                'total_earned' => $user->wallet_balance ?? 0,
            ];
        } else {
            // For borrowers or other roles
            $stats = [
                'available_books' => Book::where('status', 'available')->count(),
                'rented_books' => 5, // This should be actual user rentals
                'due_soon' => 2, // This should be actual due soon rentals
                'total_spent' => 156, // This should be actual total spent
            ];
        }
        
        return view('dashboard', compact('stats'));
    }
}
