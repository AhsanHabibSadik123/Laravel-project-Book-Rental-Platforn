@extends('layouts.app')

@section('title', 'Approved Books - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">📚 Approved Books</h1>
                    <p class="text-muted mb-0">Manage all approved books in the system</p>
                </div>
                <div>
                    <a href="{{ route('admin.book-requests') }}" class="btn btn-outline-primary">
                        <i class="fas fa-clock me-1"></i> Pending Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success">{{ number_format($stats['total_approved']) }}</h3>
                    <p class="text-muted mb-0">Total Approved</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-book-open fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary">{{ number_format($stats['total_available']) }}</h3>
                    <p class="text-muted mb-0">Available</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-handshake fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning">{{ number_format($stats['total_rented']) }}</h3>
                    <p class="text-muted mb-0">Currently Rented</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-tags fa-2x text-info"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info">{{ number_format($stats['total_genres']) }}</h3>
                    <p class="text-muted mb-0">Unique Genres</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.approved-books') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search Books</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ $search }}"
                                       placeholder="Search by title, author, ISBN, or lender...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Genre</label>
                            <select name="genre" class="form-select">
                                <option value="">All Genres</option>
                                @foreach($genres as $genreOption)
                                    <option value="{{ $genreOption }}" {{ $genre == $genreOption ? 'selected' : '' }}>
                                        {{ ucfirst($genreOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="rented" {{ $status == 'rented' ? 'selected' : '' }}>Rented</option>
                                <option value="maintenance" {{ $status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="unavailable" {{ $status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    @if($search || $genre || $status)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-info-circle me-2"></i>
                            Showing {{ $approvedBooks->total() }} result(s)
                            @if($search) for "<strong>{{ $search }}</strong>" @endif
                            @if($genre) in <strong>{{ ucfirst($genre) }}</strong> genre @endif
                            @if($status) with <strong>{{ ucfirst($status) }}</strong> status @endif
                        </div>
                        <a href="{{ route('admin.approved-books') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Books Table -->
    <div class="row">
        <div class="col-12">
            @if($approvedBooks->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Book Details</th>
                                        <th scope="col">Lender</th>
                                        <th scope="col">Pricing</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Approved</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedBooks as $approvedBook)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($approvedBook->image_path)
                                                        <img src="{{ asset('storage/' . $approvedBook->image_path) }}" 
                                                             alt="{{ $approvedBook->title }}"
                                                             class="rounded me-3"
                                                             style="width: 50px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 50px; height: 70px;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $approvedBook->title }}</h6>
                                                        <p class="mb-1 text-muted small">by {{ $approvedBook->author }}</p>
                                                        <div class="d-flex gap-2">
                                                            <span class="badge bg-secondary">{{ ucfirst($approvedBook->genre) }}</span>
                                                            <span class="badge bg-outline-secondary">{{ ucfirst($approvedBook->condition) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-medium">{{ $approvedBook->lender->name }}</div>
                                                    <small class="text-muted">{{ $approvedBook->lender->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold text-success">${{ number_format($approvedBook->rental_price_per_day, 2) }}/day</div>
                                                    <small class="text-muted">Deposit: ${{ number_format($approvedBook->security_deposit, 2) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @switch($approvedBook->book_status)
                                                    @case('available')
                                                        <span class="badge bg-success">Available</span>
                                                        @break
                                                    @case('rented')
                                                        <span class="badge bg-warning">Rented</span>
                                                        @break
                                                    @case('maintenance')
                                                        <span class="badge bg-info">Maintenance</span>
                                                        @break
                                                    @case('unavailable')
                                                        <span class="badge bg-secondary">Unavailable</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-dark">Unknown</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="small">{{ $approvedBook->approved_at->format('M j, Y') }}</div>
                                                    <small class="text-muted">by {{ $approvedBook->approvedBy->name }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('books.show', $approvedBook->book_id) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-secondary edit-status-btn" 
                                                            title="Edit Status"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editStatusModal"
                                                            data-book-id="{{ $approvedBook->id }}"
                                                            data-book-title="{{ htmlspecialchars($approvedBook->title, ENT_QUOTES) }}"
                                                            data-book-status="{{ $approvedBook->book_status }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $approvedBooks->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-books fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted">No Approved Books Found</h4>
                        <p class="text-muted mb-4">
                            @if($search || $genre || $status)
                                No books match your current filters. Try adjusting your search criteria.
                            @else
                                No books have been approved yet. Check the pending requests to approve some books.
                            @endif
                        </p>
                        @if($search || $genre || $status)
                            <a href="{{ route('admin.approved-books') }}" class="btn btn-primary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        @else
                            <a href="{{ route('admin.book-requests') }}" class="btn btn-primary">
                                <i class="fas fa-clock me-1"></i> View Pending Requests
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStatusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Edit Book Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Book</label>
                        <input type="text" id="editBookTitle" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editBookStatus" class="form-label">Status</label>
                        <select id="editBookStatus" name="book_status" class="form-select" required>
                            <option value="available">Available</option>
                            <option value="rented">Rented</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes (Optional)</label>
                        <textarea id="adminNotes" name="admin_notes" class="form-control" rows="3" 
                                  placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit status button clicks
    document.querySelectorAll('.edit-status-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            const title = this.getAttribute('data-book-title');
            const currentStatus = this.getAttribute('data-book-status');
            
            document.getElementById('editBookTitle').value = title;
            document.getElementById('editBookStatus').value = currentStatus;
            document.getElementById('editStatusForm').action = `/admin/approved-books/${bookId}/status`;
        });
    });
});

function editBookStatus(bookId, title, currentStatus) {
    document.getElementById('editBookTitle').value = title;
    document.getElementById('editBookStatus').value = currentStatus;
    document.getElementById('editStatusForm').action = `/admin/approved-books/${bookId}/status`;
}
</script>
@endsection
