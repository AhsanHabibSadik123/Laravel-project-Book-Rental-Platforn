@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body text-center py-4">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-store me-3"></i>Welcome back, {{ Auth::user()->name }}!
                    </h1>
                    <p class="lead mb-3">Submit book requests and track your collection</p>
                    <div class="mt-3">
                        <a href="{{ route('book-requests.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Submit Book for Approval
                        </a>
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
                            <i class="fas fa-books fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $stats['total_books'] }}</h3>
                    <p class="text-muted mb-0">Total Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success">{{ $stats['available_books'] }}</h3>
                    <p class="text-muted mb-0">Available for Rent</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-handshake fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning">{{ $stats['rented_books'] }}</h3>
                    <p class="text-muted mb-0">Currently Rented</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-dollar-sign fa-2x text-info"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info">${{ number_format($stats['total_earned'], 2) }}</h3>
                    <p class="text-muted mb-0">Total Earned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg quick-actions-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-15 p-3 me-3">
                            <i class="fas fa-bolt fa-lg text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold text-primary mb-1">Quick Actions</h5>
                            <p class="text-muted mb-0 small">Submit books for approval and manage requests</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('book-requests.create') }}" class="btn btn-success btn-lg w-100 action-btn">
                                <i class="fas fa-paper-plane me-2"></i>Submit Book for Approval
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('book-requests.index') }}" class="btn btn-outline-primary btn-lg w-100 action-btn">
                                <i class="fas fa-list me-2"></i>View My Requests
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Books Section -->
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-check-circle me-2"></i>My Approved Books
            </h2>
            <p class="text-muted">Books that have been approved by admin and are available for rent</p>
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
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
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
                                @if($book->status === 'available')
                                    <span class="badge bg-success">Available</span>
                                @elseif($book->status === 'rented')
                                    <span class="badge bg-warning">Rented</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($book->status) }}</span>
                                @endif
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1 mb-3">
                                {{ Str::limit($book->description, 60) }}
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
                                        <small class="text-muted">Condition:</small><br>
                                        <small class="fw-medium">{{ ucfirst(str_replace('_', ' ', $book->condition)) }}</small>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('books.show', $book) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('books.edit', $book) }}" 
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('books.destroy', $book) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this book?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
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
                {{ $books->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">No approved books yet</h3>
                        <p class="text-muted mb-4">Submit book requests to admin for approval and start earning!</p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('book-requests.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Submit Your First Book
                            </a>
                            <a href="{{ route('book-requests.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-list me-2"></i> View My Requests
                            </a>
                        </div>
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
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
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

/* Quick Actions Styling */
.quick-actions-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid rgba(0,123,255,0.1);
    transition: all 0.3s ease-in-out;
}

.quick-actions-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0,123,255,0.1);
}

.action-btn {
    padding: 12px 24px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.action-btn:hover::before {
    left: 100%;
}

.btn-success.action-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-outline-primary.action-btn {
    border: 2px solid #007bff;
    color: #007bff;
    background: transparent;
}

.btn-outline-primary.action-btn:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-color: #007bff;
    color: white;
}
</style>
@endsection
