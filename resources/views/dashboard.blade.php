@extends('layouts.app')

@section('title', 'Dashboard - BookStore')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 fw-bold">Welcome, {{ Auth::user()->name }}!</h1>
                            <p class="lead text-muted">
                                @if(Auth::user()->role === 'lender')
                                    Ready to manage your book collection and earn money from rentals?
                                @elseif(Auth::user()->role === 'borrower')
                                    Ready to explore our vast collection of books?
                                @else
                                    Welcome to your BookStore dashboard!
                                @endif
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="fas fa-user-tag me-2"></i>{{ ucfirst(Auth::user()->role ?? 'Member') }}
                                </span>
                                @if(Auth::user()->role === 'lender')
                                    <a href="{{ route('books.create') }}" class="btn btn-success btn-lg ms-3">
                                        <i class="fas fa-plus me-2"></i>Add New Book
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <i class="fas fa-book-open fa-5x text-primary opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'lender' && isset($stats) && $stats['total_books'] == 0)
    <!-- Get Started Section for New Lenders -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-gradient" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-2">
                                <i class="fas fa-rocket me-2"></i>Get Started as a Lender!
                            </h3>
                            <p class="mb-3 opacity-90">
                                Start earning money by adding your first book to our platform. 
                                It only takes a few minutes to list your book and start receiving rental requests.
                            </p>
                            <div class="d-flex gap-3">
                                <a href="#tips" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-lightbulb me-2"></i>View Tips
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-book-open" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        @if(Auth::user()->role === 'lender')
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">My Books</h5>
                                <h2 class="display-6">{{ $stats['total_books'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-books fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Rented Out</h5>
                                <h2 class="display-6">{{ $stats['rented_books'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-handshake fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Available</h5>
                                <h2 class="display-6">{{ $stats['available_books'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Earned</h5>
                                <h2 class="display-6">${{ number_format($stats['total_earned'] ?? 0, 0) }}</h2>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Available Books</h5>
                                <h2 class="display-6">1,250</h2>
                            </div>
                            <i class="fas fa-books fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Rented Books</h5>
                                <h2 class="display-6">5</h2>
                            </div>
                            <i class="fas fa-bookmark fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Due Soon</h5>
                                <h2 class="display-6">2</h2>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Spent</h5>
                                <h2 class="display-6">$156</h2>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        @if(Auth::user()->role === 'lender')
                            Recent Book Activity
                        @else
                            Recent Rentals
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if(Auth::user()->role === 'lender')
                        <div class="text-center py-4">
                            <i class="fas fa-books fa-3x text-muted mb-3"></i>
                            <h5>Start Adding Your Books!</h5>
                            <p class="text-muted mb-3">Add your first book to start earning money from rentals.</p>
                            <p class="text-muted">Use the "Add New Book" button above to get started.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Rental Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>The Great Gatsby</td>
                                        <td>2025-07-25</td>
                                        <td>2025-08-10</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>To Kill a Mockingbird</td>
                                        <td>2025-07-20</td>
                                        <td>2025-08-05</td>
                                        <td><span class="badge bg-warning">Due Soon</span></td>
                                    </tr>
                                    <tr>
                                        <td>1984</td>
                                        <td>2025-07-15</td>
                                        <td>2025-07-30</td>
                                        <td><span class="badge bg-secondary">Returned</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(Auth::user()->role === 'lender')
                            <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-books me-2"></i>My Books
                            </a>
                            <a href="/rentals" class="btn btn-outline-warning">
                                <i class="fas fa-handshake me-2"></i>Rental Requests
                            </a>
                            <a href="/earnings" class="btn btn-outline-success">
                                <i class="fas fa-dollar-sign me-2"></i>My Earnings
                            </a>
                        @elseif(Auth::user()->role === 'borrower')
                            <a href="{{ route('books.browse') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Browse Books
                            </a>
                            <a href="/my-rentals" class="btn btn-outline-success">
                                <i class="fas fa-list me-2"></i>My Rentals
                            </a>
                            <a href="/wishlist" class="btn btn-outline-info">
                                <i class="fas fa-heart me-2"></i>My Wishlist
                            </a>
                            <a href="/rental-history" class="btn btn-outline-secondary">
                                <i class="fas fa-history me-2"></i>Rental History
                            </a>
                        @else
                            <a href="{{ route('books.browse') }}" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Browse Books
                            </a>
                            <a href="/rentals" class="btn btn-outline-success">
                                <i class="fas fa-list me-2"></i>My Rentals
                            </a>
                        @endif
                        <a href="/profile" class="btn btn-outline-info">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        @if(Auth::user()->role === 'lender')
                            Lender Tips
                        @else
                            Recommended Books
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if(Auth::user()->role === 'lender')
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lightbulb text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Price Competitively</h6>
                                        <small class="text-muted">Research similar books before setting your rental price.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-camera text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Add Quality Photos</h6>
                                        <small class="text-muted">Clear photos increase rental chances by 70%.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Maintain Good Condition</h6>
                                        <small class="text-muted">Well-maintained books get more rentals.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Pride and Prejudice</h6>
                                    <small>★★★★☆</small>
                                </div>
                                <p class="mb-1">Jane Austen</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">The Catcher in the Rye</h6>
                                    <small>★★★★★</small>
                                </div>
                                <p class="mb-1">J.D. Salinger</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
