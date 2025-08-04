@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-primary">
                    <i class="fas fa-user me-2"></i>User Details: {{ $user->name }}
                </h1>
                <div>
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Users
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- User Information Card -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-id-card me-2"></i>User Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-15 p-4 d-inline-flex">
                                    <i class="fas fa-user fa-3x text-primary"></i>
                                </div>
                                <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                                <p class="text-muted mb-2">User ID: {{ $user->id }}</p>
                                @if($user->role === 'lender')
                                    <span class="badge bg-success fs-6">Lender</span>
                                @elseif($user->role === 'borrower')
                                    <span class="badge bg-primary fs-6">Borrower</span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($user->role) }}</span>
                                @endif
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <h6 class="text-muted mb-1">Email Address</h6>
                                        <p class="mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>
                                
                                @if($user->phone)
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <h6 class="text-muted mb-1">Phone Number</h6>
                                        <p class="mb-0">{{ $user->phone }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($user->address)
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <h6 class="text-muted mb-1">Address</h6>
                                        <p class="mb-0">{{ $user->address }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($user->bio)
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <h6 class="text-muted mb-1">Bio</h6>
                                        <p class="mb-0">{{ $user->bio }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics and Actions -->
                <div class="col-lg-8 mb-4">
                    <!-- Status and Wallet Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Account Status & Wallet
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @if($user->is_verified)
                                            <div class="rounded-circle bg-success bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-check-circle fa-2x text-success"></i>
                                            </div>
                                            <h6 class="text-success">Verified</h6>
                                        @else
                                            <div class="rounded-circle bg-warning bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-clock fa-2x text-warning"></i>
                                            </div>
                                            <h6 class="text-warning">Unverified</h6>
                                        @endif
                                        <small class="text-muted">Account Status</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="rounded-circle bg-success bg-opacity-15 p-3 d-inline-flex mb-2">
                                            <i class="fas fa-wallet fa-2x text-success"></i>
                                        </div>
                                        <h6 class="text-success">${{ number_format($user->wallet_balance, 2) }}</h6>
                                        <small class="text-muted">Wallet Balance</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="rounded-circle bg-info bg-opacity-15 p-3 d-inline-flex mb-2">
                                            <i class="fas fa-calendar fa-2x text-info"></i>
                                        </div>
                                        <h6 class="text-info">{{ $user->created_at->format('M d, Y') }}</h6>
                                        <small class="text-muted">Joined {{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="rounded-circle bg-primary bg-opacity-15 p-3 d-inline-flex mb-2">
                                            <i class="fas fa-activity fa-2x text-primary"></i>
                                        </div>
                                        <h6 class="text-primary">{{ $user->updated_at->diffForHumans() }}</h6>
                                        <small class="text-muted">Last Activity</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>User Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @if($user->role === 'lender')
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-success bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-books fa-2x text-success"></i>
                                            </div>
                                            <h4 class="text-success">{{ $user->books_count ?? 0 }}</h4>
                                            <small class="text-muted">Books Listed</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-info bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-handshake fa-2x text-info"></i>
                                            </div>
                                            <h4 class="text-info">{{ $user->rentals_as_lender_count ?? 0 }}</h4>
                                            <small class="text-muted">Rentals as Lender</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-warning bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                                            </div>
                                            <h4 class="text-warning">$0.00</h4>
                                            <small class="text-muted">Total Earnings</small>
                                        </div>
                                    </div>
                                @elseif($user->role === 'borrower')
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-primary bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-book-reader fa-2x text-primary"></i>
                                            </div>
                                            <h4 class="text-primary">{{ $user->rentals_as_borrower_count ?? 0 }}</h4>
                                            <small class="text-muted">Books Rented</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-success bg-opacity-15 p-3 d-inline-flex mb-2">
                                                <i class="fas fa-star fa-2x text-success"></i>
                                            </div>
                                            <h4 class="text-success">4.5</h4>
                                            <small class="text-muted">Average Rating</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tools me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-success w-100 verify-btn" 
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-current-status="{{ $user->is_verified ? 'verified' : 'unverified' }}">
                                        <i class="fas fa-{{ $user->is_verified ? 'times' : 'check' }} me-2"></i>
                                        {{ $user->is_verified ? 'Unverify User' : 'Verify User' }}
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-warning w-100 role-btn" 
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-current-role="{{ $user->role }}">
                                        <i class="fas fa-user-tag me-2"></i>Change Role
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-info w-100 wallet-btn" 
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-current-balance="{{ $user->wallet_balance }}">
                                        <i class="fas fa-wallet me-2"></i>Update Wallet
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-danger w-100 delete-btn" 
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}">
                                        <i class="fas fa-trash me-2"></i>Delete User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                @if($user->role === 'lender' && $recentBooks->count() > 0)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-books me-2"></i>Recent Books ({{ $user->books_count ?? 0 }} total)
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($recentBooks as $book)
                                <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                    @if($book->image_path)
                                        <img src="{{ asset('storage/' . $book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px; border-radius: 8px;">
                                            <i class="fas fa-book text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $book->title }}</h6>
                                        <small class="text-muted">by {{ $book->author }}</small><br>
                                        <small class="text-muted">Listed {{ $book->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div>
                                        @if($book->status === 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($book->status === 'rented')
                                            <span class="badge bg-warning">Rented</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($book->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($user->role === 'borrower' && $recentRentals->count() > 0)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-history me-2"></i>Recent Rentals ({{ $user->rentals_as_borrower_count ?? 0 }} total)
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($recentRentals as $rental)
                                <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                    @if($rental->book->image_path)
                                        <img src="{{ asset('storage/' . $rental->book->image_path) }}" 
                                             alt="{{ $rental->book->title }}" 
                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px; border-radius: 8px;">
                                            <i class="fas fa-book text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $rental->book->title }}</h6>
                                        <small class="text-muted">by {{ $rental->book->author }}</small><br>
                                        <small class="text-muted">Rented {{ $rental->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div>
                                        @if($rental->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($rental->status === 'completed')
                                            <span class="badge bg-primary">Completed</span>
                                        @elseif($rental->status === 'overdue')
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($rental->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if(($user->role === 'lender' && $recentBooks->count() === 0) || ($user->role === 'borrower' && $recentRentals->count() === 0))
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Recent Activity</h5>
                            <p class="text-muted">
                                @if($user->role === 'lender')
                                    This user hasn't listed any books yet.
                                @else
                                    This user hasn't rented any books yet.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include modals from admin.users view -->
<!-- Note: The modals would be included here, but for brevity, they should be extracted to a partial -->

<style>
/* Same styling as users.blade.php */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.badge {
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 20px;
}

.border {
    border-color: #e9ecef !important;
}

.border:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}
</style>

<script>
// Include the same JavaScript functionality as in users.blade.php for the action buttons
// This would typically be extracted to a common JS file
</script>
@endsection
