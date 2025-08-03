<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookRequestController extends Controller
{
    /**
     * Display lender's book requests
     */
    public function index()
    {
        $bookRequests = BookRequest::where('lender_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('book-requests.index', compact('bookRequests'));
    }

    /**
     * Show the form for creating a new book request
     */
    public function create()
    {
        // Check if user is a lender
        if (Auth::user()->role !== 'lender') {
            return redirect()->route('dashboard')->with('error', 'Only lenders can submit book requests.');
        }
        
        return view('book-requests.create');
    }

    /**
     * Store a newly created book request
     */
    public function store(Request $request)
    {
        // Check if user is a lender
        if (Auth::user()->role !== 'lender') {
            return redirect()->route('dashboard')->with('error', 'Only lenders can submit book requests.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'description' => 'required|string|max:2000',
            'genre' => 'required|string|max:100',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'rental_price_per_day' => 'required|numeric|min:0.01|max:9999.99',
            'security_deposit' => 'required|numeric|min:0|max:99999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('book-requests', 'public');
        }

        // Create book request
        BookRequest::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'],
            'description' => $validated['description'],
            'genre' => $validated['genre'],
            'condition' => $validated['condition'],
            'rental_price_per_day' => $validated['rental_price_per_day'],
            'security_deposit' => $validated['security_deposit'],
            'image_path' => $imagePath,
            'lender_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('book-requests.index')
            ->with('success', 'Book request submitted successfully! Please wait for admin approval.');
    }

    /**
     * Display the specified book request
     */
    public function show(BookRequest $bookRequest)
    {
        // Check if user owns this request
        if ($bookRequest->lender_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('book-requests.show', compact('bookRequest'));
    }
}
