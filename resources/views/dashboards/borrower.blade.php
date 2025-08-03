@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center py-4">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-book-reader me-3"></i>Welcome back, {{ Auth::user()->name }}!
                    </h1>
                    <p class="lead mb-3">Discover your next great read from our book collection</p>
                    
                    <!-- Search Bar -->
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                                <input type="text" 
                                       class="form-control form-control-lg me-2" 
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Search for books...">
                                <button type="submit" class="btn btn-light btn-lg px-4">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-book fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $stats['total_available'] }}</h3>
                    <p class="text-muted mb-0">Available Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-bookmark fa-2x text-success"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success">{{ $stats['active_rentals'] }}</h3>
                    <p class="text-muted mb-0">Active Rentals</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info">{{ $stats['books_read'] }}</h3>
                    <p class="text-muted mb-0">Books Completed</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning">${{ number_format($stats['total_spent'], 2) }}</h3>
                    <p class="text-muted mb-0">Total Spent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    @if($search)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-search me-2"></i>
                            Showing {{ $books->total() }} result(s) for "<strong>{{ $search }}</strong>"
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-times me-1"></i> Clear Search
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Section Header -->
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="fw-bold text-dark">
                {{ $search ? 'Search Results' : 'Available Books' }}
            </h2>
            <p class="text-muted">Discover amazing books from our community of lenders</p>
        </div>
    </div>

    <!-- Books Grid -->
    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 book-card">
                        @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" 
                                 class="card-img-top" 
                                 alt="{{ $book->title }}"
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate mb-2" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h5>
                            <p class="text-muted mb-2 small">by {{ $book->author }}</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-secondary me-1">{{ $book->genre }}</span>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $book->condition)) }}</span>
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1 mb-3">
                                {{ Str::limit($book->description, 70) }}
                            </p>
                            
                            <div class="border-top pt-3 mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <div class="fw-bold text-success h6 mb-0">
                                            ${{ number_format($book->rental_price_per_day, 2) }}/day
                                        </div>
                                        <small class="text-muted">
                                            Deposit: ${{ number_format($book->security_deposit, 2) }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">by</small><br>
                                        <small class="fw-medium">{{ $book->lender->name }}</small>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('books.show', $book->book_id) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                    <button class="btn btn-outline-success btn-sm rent-book-btn" 
                                            data-book-id="{{ $book->book_id }}"
                                            data-book-title="{{ $book->title }}">
                                        <i class="fas fa-shopping-cart me-1"></i> Rent Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $books->appends(['search' => $search])->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        @if($search)
                            <i class="fas fa-search fa-4x text-muted mb-4"></i>
                            <h3 class="text-muted mb-3">No books found for "{{ $search }}"</h3>
                            <p class="text-muted mb-4">Try searching with different keywords or browse all available books.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-book me-2"></i> View All Books
                            </a>
                        @else
                            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                            <h3 class="text-muted mb-3">No books available yet</h3>
                            <p class="text-muted mb-4">Check back later for new books from our lenders!</p>
                            <a href="{{ route('books.browse') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-refresh me-2"></i> Refresh
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.book-card {
    transition: all 0.3s ease-in-out;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.card {
    border-radius: 15px;
}

.btn {
    border-radius: 10px;
}

.rounded-circle {
    width: 60px;
    height: 60px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for rent book buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.rent-book-btn')) {
            const button = e.target.closest('.rent-book-btn');
            const bookId = button.dataset.bookId;
            const bookTitle = button.dataset.bookTitle;
            
            // TODO: Implement rent book functionality
            alert(`Rent book functionality will be implemented soon!\nBook: ${bookTitle} (ID: ${bookId})`);
        }
    });
});
</script>
@endsection
