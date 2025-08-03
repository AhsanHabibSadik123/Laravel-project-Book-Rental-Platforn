<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;
use App\Models\Book;
use App\Models\User;
use App\Models\ApprovedBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $pendingRequests = BookRequest::where('status', 'pending')->count();
        $totalBooks = Book::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $recentRequests = BookRequest::with('lender')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendingRequests',
            'totalBooks', 
            'totalUsers',
            'recentRequests'
        ));
    }

    /**
     * List all book requests
     */
    public function bookRequests(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $status = $request->get('status', 'all');
        
        $query = BookRequest::with(['lender', 'approvedBy']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $bookRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.book-requests', compact('bookRequests', 'status'));
    }

    /**
     * Show book request details
     */
    public function showBookRequest(BookRequest $bookRequest)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $bookRequest->load(['lender', 'approvedBy']);
        return view('admin.book-request-details', compact('bookRequest'));
    }

    /**
     * Approve book request
     */
    public function approveBookRequest(Request $request, BookRequest $bookRequest)
    {
        // Debug logging
        Log::info('Approve request received', [
            'request_id' => $bookRequest->id,
            'title' => $bookRequest->title,
            'status' => $bookRequest->status,
            'user_id' => Auth::id()
        ]);

        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            Log::error('Unauthorized approve attempt', ['user_id' => Auth::id()]);
            abort(403, 'Access denied. Admin only.');
        }

        if (!$bookRequest->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        // Create the book from the approved request
        $book = Book::create([
            'title' => $bookRequest->title,
            'author' => $bookRequest->author,
            'isbn' => $bookRequest->isbn,
            'description' => $bookRequest->description,
            'genre' => $bookRequest->genre,
            'condition' => $bookRequest->condition,
            'image_path' => $bookRequest->image_path,
            'rental_price_per_day' => $bookRequest->rental_price_per_day,
            'security_deposit' => $bookRequest->security_deposit,
            'lender_id' => $bookRequest->lender_id,
            'status' => 'available',
        ]);

        // Create entry in approved_books table for easy querying
        ApprovedBook::create([
            'book_request_id' => $bookRequest->id,
            'book_id' => $book->id,
            'lender_id' => $bookRequest->lender_id,
            'approved_by' => Auth::id(),
            'title' => $bookRequest->title,
            'author' => $bookRequest->author,
            'isbn' => $bookRequest->isbn,
            'description' => $bookRequest->description,
            'genre' => $bookRequest->genre,
            'condition' => $bookRequest->condition,
            'image_path' => $bookRequest->image_path,
            'rental_price_per_day' => $bookRequest->rental_price_per_day,
            'security_deposit' => $bookRequest->security_deposit,
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
            'book_status' => 'available',
            'is_active' => true
        ]);

        // Update the request status
        $bookRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        Log::info('Book request approved successfully', [
            'request_id' => $bookRequest->id,
            'book_id' => $book->id,
            'title' => $bookRequest->title
        ]);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '✅ Book request approved successfully! The book "' . $bookRequest->title . '" is now LIVE and visible to all borrowers on their dashboard.',
                'book_id' => $book->id,
                'request_id' => $bookRequest->id
            ]);
        }

        return back()->with('success', '✅ Book request approved successfully! The book "' . $bookRequest->title . '" is now LIVE and visible to all borrowers on their dashboard. Borrowers can now search, view, and rent this book.');
    }

    /**
     * Reject book request
     */
    public function rejectBookRequest(Request $request, BookRequest $bookRequest)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        if (!$bookRequest->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        $bookRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Book request rejected successfully.',
                'request_id' => $bookRequest->id
            ]);
        }

        return back()->with('success', 'Book request rejected.');
    }

    /**
     * View all approved books
     */
    public function approvedBooks(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $search = $request->get('search');
        $genre = $request->get('genre');
        $status = $request->get('status');

        // Build query for approved books
        $query = ApprovedBook::with(['lender', 'approvedBy', 'book']);

        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%")
                  ->orWhereHas('lender', function($lenderQuery) use ($search) {
                      $lenderQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by genre
        if ($genre) {
            $query->where('genre', $genre);
        }

        // Filter by status
        if ($status) {
            $query->where('book_status', $status);
        }

        $approvedBooks = $query->latest('approved_at')->paginate(15);

        // Get statistics
        $stats = [
            'total_approved' => ApprovedBook::count(),
            'total_available' => ApprovedBook::where('book_status', 'available')->count(),
            'total_rented' => ApprovedBook::where('book_status', 'rented')->count(),
            'total_genres' => ApprovedBook::distinct('genre')->count(),
        ];

        // Get unique genres for filter dropdown
        $genres = ApprovedBook::distinct('genre')->pluck('genre')->sort();

        return view('admin.approved-books', compact('approvedBooks', 'stats', 'genres', 'search', 'genre', 'status'));
    }

    /**
     * Update approved book status
     */
    public function updateApprovedBookStatus(Request $request, ApprovedBook $approvedBook)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $request->validate([
            'book_status' => 'required|in:available,rented,maintenance,unavailable',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $approvedBook->book_status;
        
        // Update the approved book status
        $approvedBook->update([
            'book_status' => $request->book_status,
            'admin_notes' => $request->admin_notes,
        ]);

        // Also update the corresponding book status
        if ($approvedBook->book) {
            $approvedBook->book->update([
                'status' => $request->book_status,
            ]);
        }

        Log::info('Approved book status updated', [
            'approved_book_id' => $approvedBook->id,
            'book_id' => $approvedBook->book_id,
            'title' => $approvedBook->title,
            'old_status' => $oldStatus,
            'new_status' => $request->book_status,
            'updated_by' => Auth::id()
        ]);

        return back()->with('success', "Book status updated from '{$oldStatus}' to '{$request->book_status}' successfully!");
    }

    /**
     * List all users
     */
    public function users()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $users = User::where('role', '!=', 'admin')
            ->withCount(['books', 'rentalsAsBorrower', 'rentalsAsLender'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users', compact('users'));
    }
}
