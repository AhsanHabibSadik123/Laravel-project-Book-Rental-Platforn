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
    public function users(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');

        $query = User::where('role', '!=', 'admin')
            ->withCount(['books', 'rentalsAsBorrower', 'rentalsAsLender']);

        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Filter by role
        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        // Filter by verification status
        if ($status && $status !== 'all') {
            if ($status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'verified_users' => User::where('role', '!=', 'admin')->where('is_verified', true)->count(),
            'lenders' => User::where('role', 'lender')->count(),
            'borrowers' => User::where('role', 'borrower')->count(),
        ];

        return view('admin.users', compact('users', 'stats', 'search', 'role', 'status'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        // Don't allow viewing other admin users
        if ($user->role === 'admin') {
            abort(403, 'Cannot view admin user details.');
        }

        $user->loadCount(['books', 'rentalsAsBorrower', 'rentalsAsLender']);
        
        // Get user's recent activity
        $recentBooks = $user->books()->latest()->limit(5)->get();
        $recentRentals = $user->rentalsAsBorrower()->with('book')->latest()->limit(5)->get();

        return view('admin.user-details', compact('user', 'recentBooks', 'recentRentals'));
    }

    /**
     * Update user status (verify/unverify)
     */
    public function updateUserStatus(Request $request, User $user)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        // Don't allow modifying admin users
        if ($user->role === 'admin') {
            abort(403, 'Cannot modify admin users.');
        }

        $request->validate([
            'is_verified' => 'required|boolean',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $user->is_verified ? 'verified' : 'unverified';
        $newStatus = $request->is_verified ? 'verified' : 'unverified';

        $user->update([
            'is_verified' => $request->is_verified,
        ]);

        Log::info('User status updated by admin', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ]);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "User status updated to {$newStatus} successfully!",
                'new_status' => $newStatus,
                'user_id' => $user->id
            ]);
        }

        return back()->with('success', "User {$user->name} has been {$newStatus} successfully!");
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        // Don't allow modifying admin users
        if ($user->role === 'admin') {
            abort(403, 'Cannot modify admin users.');
        }

        $request->validate([
            'role' => 'required|in:borrower,lender',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldRole = $user->role;
        $newRole = $request->role;

        $user->update([
            'role' => $newRole,
        ]);

        Log::info('User role updated by admin', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'old_role' => $oldRole,
            'new_role' => $newRole,
            'admin_notes' => $request->admin_notes,
        ]);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "User role updated from {$oldRole} to {$newRole} successfully!",
                'new_role' => $newRole,
                'user_id' => $user->id
            ]);
        }

        return back()->with('success', "User {$user->name} role has been changed from {$oldRole} to {$newRole} successfully!");
    }

    /**
     * Update user wallet balance
     */
    public function updateUserWallet(Request $request, User $user)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        // Don't allow modifying admin users
        if ($user->role === 'admin') {
            abort(403, 'Cannot modify admin users.');
        }

        $request->validate([
            'action' => 'required|in:add,subtract,set',
            'amount' => 'required|numeric|min:0|max:99999.99',
            'admin_notes' => 'required|string|max:1000',
        ]);

        $oldBalance = $user->wallet_balance;
        $amount = $request->amount;

        switch ($request->action) {
            case 'add':
                $newBalance = $oldBalance + $amount;
                break;
            case 'subtract':
                $newBalance = max(0, $oldBalance - $amount); // Don't allow negative balance
                break;
            case 'set':
                $newBalance = $amount;
                break;
            default:
                return back()->with('error', 'Invalid wallet action.');
        }

        $user->update([
            'wallet_balance' => $newBalance,
        ]);

        Log::info('User wallet updated by admin', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $request->action,
            'amount' => $amount,
            'old_balance' => $oldBalance,
            'new_balance' => $newBalance,
            'admin_notes' => $request->admin_notes,
        ]);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Wallet updated successfully! New balance: $" . number_format($newBalance, 2),
                'new_balance' => $newBalance,
                'user_id' => $user->id
            ]);
        }

        return back()->with('success', "User {$user->name} wallet has been updated successfully! New balance: $" . number_format($newBalance, 2));
    }

    /**
     * Delete user account
     */
    public function deleteUser(User $user)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        // Don't allow deleting admin users
        if ($user->role === 'admin') {
            abort(403, 'Cannot delete admin users.');
        }

        // Check if user has active rentals or books
        $hasActiveRentals = $user->rentalsAsBorrower()->whereIn('status', ['active', 'overdue'])->exists();
        $hasBooks = $user->books()->exists();

        if ($hasActiveRentals) {
            return back()->with('error', 'Cannot delete user with active rentals. Please ensure all rentals are completed first.');
        }

        if ($hasBooks) {
            return back()->with('error', 'Cannot delete user who has listed books. Please remove all books first.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        Log::info('User deleted by admin', [
            'admin_id' => Auth::id(),
            'deleted_user_id' => $user->id,
            'deleted_user_name' => $userName,
            'deleted_user_email' => $userEmail,
        ]);

        $user->delete();

        return back()->with('success', "User {$userName} ({$userEmail}) has been deleted successfully!");
    }
}
