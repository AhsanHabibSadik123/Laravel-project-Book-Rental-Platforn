@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Back Button -->
        <div class="col-12 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Book Image -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow">
                @if($book->image_path)
                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                         class="card-img-top" 
                         alt="{{ $book->title }}"
                         style="height: 400px; object-fit: cover; border-radius: 15px;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 400px; border-radius: 15px;">
                        <i class="fas fa-book fa-5x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-lg-7">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <!-- Title and Author -->
                    <h1 class="h2 fw-bold text-primary mb-2">{{ $book->title }}</h1>
                    <p class="h5 text-muted mb-3">by {{ $book->author }}</p>
                    
                    <!-- Badges -->
                    <div class="mb-4">
                        <span class="badge bg-secondary me-2 fs-6">{{ $book->genre }}</span>
                        <span class="badge bg-info fs-6">{{ ucfirst(str_replace('_', ' ', $book->condition)) }}</span>
                        <span class="badge bg-success fs-6">{{ ucfirst($book->status) }}</span>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Description</h5>
                        <p class="text-muted">{{ $book->description }}</p>
                    </div>

                    <!-- Book Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">ISBN</h6>
                            <p class="text-muted">{{ $book->isbn ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Publication Year</h6>
                            <p class="text-muted">{{ $book->publication_year ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Lender Information -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-user me-2"></i>Lender Information
                            </h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $book->lender->name }}</p>
                            <p class="mb-0"><strong>Member since:</strong> {{ $book->lender->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="card border-primary mb-4">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h6 class="text-muted mb-1">Rental Price</h6>
                                    <h3 class="text-success fw-bold mb-0">
                                        ${{ number_format($book->rental_price_per_day, 2) }}
                                        <small class="text-muted fs-6">/day</small>
                                    </h3>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted mb-1">Security Deposit</h6>
                                    <h3 class="text-warning fw-bold mb-0">
                                        ${{ number_format($book->security_deposit, 2) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($book->status === 'available')
                        @if(Auth::user()->role === 'borrower')
                            <div class="d-grid gap-2 d-md-flex">
                                <button class="btn btn-success btn-lg flex-fill" data-bs-toggle="modal" data-bs-target="#rentModal">
                                    <i class="fas fa-shopping-cart me-2"></i>Rent This Book
                                </button>
                                <button class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-heart me-2"></i>Add to Wishlist
                                </button>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Only borrowers can rent books. 
                                @if(Auth::user()->role === 'lender')
                                    You are a lender.
                                @elseif(Auth::user()->role === 'admin')
                                    You are an admin.
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This book is currently not available for rent.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rent Modal -->
@if(Auth::user()->role === 'borrower' && $book->status === 'available')
<div class="modal fade" id="rentModal" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rentModalLabel">Rent "{{ $book->title }}"</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rental_days" class="form-label">Number of Days</label>
                        <input type="number" 
                               class="form-control" 
                               id="rental_days" 
                               name="rental_days" 
                               data-daily-rate="{{ $book->rental_price_per_day }}"
                               data-security-deposit="{{ $book->security_deposit }}"
                               min="1" 
                               max="30" 
                               value="7" 
                               required>
                        <div class="form-text">Minimum 1 day, maximum 30 days</div>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Rental Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Daily Rate:</span>
                                <span>${{ number_format($book->rental_price_per_day, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Days:</span>
                                <span id="days-display">7</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">${{ number_format($book->rental_price_per_day * 7, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Security Deposit:</span>
                                <span>${{ number_format($book->security_deposit, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="total">${{ number_format(($book->rental_price_per_day * 7) + $book->security_deposit, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            The security deposit will be refunded when you return the book in good condition.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Confirm Rental
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentalDaysInput = document.getElementById('rental_days');
    const daysDisplay = document.getElementById('days-display');
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    
    // Get values from data attributes to avoid Blade syntax in JS
    const dailyRate = parseFloat(rentalDaysInput?.dataset.dailyRate || '0');
    const securityDeposit = parseFloat(rentalDaysInput?.dataset.securityDeposit || '0');
    
    if (rentalDaysInput) {
        rentalDaysInput.addEventListener('input', function() {
            const days = parseInt(this.value) || 1;
            const subtotal = days * dailyRate;
            const total = subtotal + securityDeposit;
            
            daysDisplay.textContent = days;
            subtotalElement.textContent = '$' + subtotal.toFixed(2);
            totalElement.textContent = '$' + total.toFixed(2);
        });
    }
});
</script>
@endsection
@endsection
