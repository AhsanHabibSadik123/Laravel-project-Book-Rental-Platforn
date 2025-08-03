@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center py-5">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-book-open me-3"></i>Welcome to BookStore
                    </h1>
                    <p class="lead mb-4">Discover amazing books from our community of lenders</p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form action="{{ route('books.home') }}" method="GET" class="d-flex">
                                <input type="text" 
                                       class="form-control form-control-lg me-2" 
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Search books by title, author, or genre...">
                                <button type="submit" class="btn btn-light btn-lg px-4">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    @if($search)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-search me-2"></i>
                    Showing {{ $books->total() }} result(s) for "<strong>{{ $search }}</strong>"
                    <a href="{{ route('books.home') }}" class="btn btn-sm btn-outline-primary ms-2">
                        <i class="fas fa-times"></i> Clear Search
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Bar -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                    <h4 class="fw-bold">{{ $totalBooks }}</h4>
                    <p class="text-muted mb-0">Available Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h4 class="fw-bold">{{ $books->count() }}</h4>
                    <p class="text-muted mb-0">{{ $search ? 'Search Results' : 'Showing Now' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h4 class="fw-bold">5.0</h4>
                    <p class="text-muted mb-0">Average Rating</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 book-card">
                        @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" 
                                 class="card-img-top" 
                                 alt="{{ $book->title }}"
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 250px;">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h5>
                            <p class="text-muted mb-2">by {{ $book->author }}</p>
                            
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $book->genre }}</span>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $book->condition)) }}</span>
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($book->description, 80) }}
                            </p>
                            
                            <div class="border-top pt-3 mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong class="text-success h5 mb-0">
                                            ${{ number_format($book->rental_price_per_day, 2) }}/day
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            Deposit: ${{ number_format($book->security_deposit, 2) }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            by {{ $book->lender->name }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('books.show', $book) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                    <button class="btn btn-outline-success btn-sm">
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
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        @if($search)
                            <h4 class="text-muted">No books found for "{{ $search }}"</h4>
                            <p class="text-muted">Try searching with different keywords or browse all available books.</p>
                            <a href="{{ route('books.home') }}" class="btn btn-primary">
                                <i class="fas fa-books me-1"></i> View All Books
                            </a>
                        @else
                            <h4 class="text-muted">No books available yet</h4>
                            <p class="text-muted">Check back later for new books from our lenders!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.book-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
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
</style>
@endsection
