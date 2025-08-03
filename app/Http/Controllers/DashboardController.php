<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Rental;
use App\Models\ApprovedBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard - redirect to role-specific dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'lender':
                return $this->lenderDashboard();
            case 'borrower':
                return $this->borrowerDashboard($request);
            default:
                return redirect()->route('login');
        }
    }

    /**
     * Borrower Dashboard - Show all available books for rent
     */
    public function borrowerDashboard(Request $request)
    {
        $search = $request->get('search');
        
        // Use ApprovedBook for better performance and data organization
        $query = ApprovedBook::available()->with(['lender', 'book']);
        
        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('genre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $books = $query->latest()->paginate(12);
        
        // Get borrower statistics
        $stats = [
            'total_available' => ApprovedBook::available()->count(),
            'active_rentals' => 0, // TODO: Implement when Rental model is used
            'total_spent' => 0, // TODO: Implement when payment system is added
            'books_read' => 0, // TODO: Implement when rental history is tracked
        ];
        
        return view('dashboards.borrower', compact('books', 'search', 'stats'));
    }

    /**
     * Lender Dashboard - Show lender's books and statistics
     */
    public function lenderDashboard()
    {
        $user = Auth::user();
        
        // Get lender's books
        $books = Book::where('lender_id', $user->id)->latest()->paginate(8);
        
        // Get lender statistics
        $stats = [
            'total_books' => Book::where('lender_id', $user->id)->count(),
            'available_books' => Book::where('lender_id', $user->id)->where('status', 'available')->count(),
            'rented_books' => Book::where('lender_id', $user->id)->where('status', 'rented')->count(),
            'total_earned' => 0, // TODO: Implement when payment system is added
        ];
        
        return view('dashboards.lender', compact('books', 'stats'));
    }
}
