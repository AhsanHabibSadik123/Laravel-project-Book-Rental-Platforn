<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the user's books.
     */
    public function index()
    {
        $books = Book::where('lender_id', Auth::id())->latest()->paginate(12);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        // Check if user is a lender
        if (Auth::user()->role !== 'lender') {
            return redirect()->route('dashboard')->with('error', 'Only lenders can add books.');
        }
        
        return view('books.create');
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        // Check if user is a lender
        if (Auth::user()->role !== 'lender') {
            return redirect()->route('dashboard')->with('error', 'Only lenders can add books.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'genre' => 'required|string|max:100',
            'description' => 'required|string',
            'condition' => 'required|in:excellent,good,fair,poor',
            'rental_price_per_day' => 'required|numeric|min:0.01|max:999.99',
            'security_deposit' => 'required|numeric|min:0|max:9999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Add lender_id and set status
        $validated['lender_id'] = Auth::id();
        $validated['status'] = 'available';

        $book = Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        // Check if user owns this book
        if ($book->lender_id !== Auth::id()) {
            return redirect()->route('books.index')->with('error', 'You can only edit your own books.');
        }

        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        // Check if user owns this book
        if ($book->lender_id !== Auth::id()) {
            return redirect()->route('books.index')->with('error', 'You can only edit your own books.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'genre' => 'required|string|max:100',
            'description' => 'required|string',
            'condition' => 'required|in:excellent,good,fair,poor',
            'rental_price_per_day' => 'required|numeric|min:0.01|max:999.99',
            'security_deposit' => 'required|numeric|min:0|max:9999.99',
            'status' => 'required|in:available,rented,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            $imagePath = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $imagePath;
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        // Check if user owns this book
        if ($book->lender_id !== Auth::id()) {
            return redirect()->route('books.index')->with('error', 'You can only delete your own books.');
        }

        // Check if book is currently rented
        if ($book->status === 'rented') {
            return redirect()->route('books.index')->with('error', 'Cannot delete a book that is currently rented.');
        }

        // Delete image if exists
        if ($book->image_path) {
            Storage::disk('public')->delete($book->image_path);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    /**
     * Display available books for browsing (for borrowers)
     */
    public function browse()
    {
        $books = Book::where('status', 'available')
                    ->where('lender_id', '!=', Auth::id())
                    ->with('lender')
                    ->latest()
                    ->paginate(12);
                    
        return view('books.browse', compact('books'));
    }
}
