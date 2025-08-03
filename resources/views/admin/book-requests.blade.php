@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-primary">
                    <i class="fas fa-clipboard-list me-2"></i>Book Requests Management
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                               href="{{ route('admin.book-requests', ['status' => 'all']) }}">
                                All Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                               href="{{ route('admin.book-requests', ['status' => 'pending']) }}">
                                Pending
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                               href="{{ route('admin.book-requests', ['status' => 'approved']) }}">
                                Approved
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                               href="{{ route('admin.book-requests', ['status' => 'rejected']) }}">
                                Rejected
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Book Requests Table -->
            <div class="card">
                <div class="card-body">
                    @if($bookRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Book Details</th>
                                        <th>Lender</th>
                                        <th>Pricing</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookRequests as $request)
                                        <tr>
                                            <td>
                                                @if($request->image_path)
                                                    <img src="{{ asset('storage/' . $request->image_path) }}" 
                                                         alt="{{ $request->title }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-book text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $request->title }}</strong><br>
                                                    <small class="text-muted">by {{ $request->author }}</small><br>
                                                    <small class="text-muted">Genre: {{ $request->genre }}</small><br>
                                                    <small class="text-muted">Condition: {{ ucfirst($request->condition) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $request->lender->name }}<br>
                                                    <small class="text-muted">{{ $request->lender->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>${{ number_format($request->rental_price_per_day, 2) }}/day</strong><br>
                                                    <small class="text-muted">Deposit: ${{ number_format($request->security_deposit, 2) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($request->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $request->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $request->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <a href="{{ route('admin.book-requests.show', $request) }}" 
                                                       class="btn btn-sm btn-outline-primary w-100">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                    
                                                    @if($request->isPending())
                                        <button type="button" 
                                                class="btn btn-sm btn-success w-100 approve-btn-direct" 
                                                data-request-id="{{ $request->id }}"
                                                data-request-title="{{ $request->title }}">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-danger w-100 reject-btn-direct" 
                                                data-request-id="{{ $request->id }}"
                                                data-request-title="{{ $request->title }}">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                                    @elseif($request->status === 'approved')
                                                        <small class="text-success text-center">
                                                            <i class="fas fa-check-circle me-1"></i> Approved
                                                        </small>
                                                    @elseif($request->status === 'rejected')
                                                        <small class="text-danger text-center">
                                                            <i class="fas fa-times-circle me-1"></i> Rejected
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookRequests->appends(['status' => $status])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No book requests found</h5>
                            <p class="text-muted">
                                @if($status === 'pending')
                                    There are no pending book requests at the moment.
                                @elseif($status === 'approved')
                                    No book requests have been approved yet.
                                @elseif($status === 'rejected')
                                    No book requests have been rejected yet.
                                @else
                                    No book requests have been submitted yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="approveModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Approve Book Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-question-circle fa-4x text-warning"></i>
                    </div>
                    <h4 class="text-primary fw-bold" id="approveBookTitle">Book Title</h4>
                    <p class="text-muted mb-0">Are you sure you want to approve this book?</p>
                </div>
                
                <div class="alert alert-info">
                    <h6 class="fw-bold">✅ After approval:</h6>
                    <ul class="mb-0">
                        <li>The book will be <strong>LIVE</strong> and visible to all borrowers</li>
                        <li>Borrowers can search, view, and rent this book</li>
                        <li>This action cannot be undone</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmApproveBtn">
                    <i class="fas fa-check me-1"></i>Yes, Approve Book
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="rejectModalLabel">
                    <i class="fas fa-times-circle me-2"></i>Reject Book Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                    </div>
                    <h4 class="text-danger fw-bold" id="rejectBookTitle">Book Title</h4>
                    <p class="text-muted mb-0">Are you sure you want to reject this book?</p>
                </div>
                
                <div class="mb-3">
                    <label for="rejectReason" class="form-label fw-bold">
                        Reason for Rejection <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" 
                              id="rejectReason" 
                              rows="4" 
                              placeholder="Please provide a detailed reason for rejecting this book request..."
                              required></textarea>
                    <div class="form-text">This reason will be sent to the lender.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmRejectBtn">
                    <i class="fas fa-ban me-1"></i>Yes, Reject Book
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Table Styling */
.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table thead th {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    font-weight: 600;
    border: none;
    padding: 15px 12px;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border: none;
}

/* Action Buttons Styling */
.btn-sm {
    padding: 6px 12px;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Badge Styling */
.badge {
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 20px;
}

/* Card Styling */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
}

/* Nav Tabs Styling */
.nav-tabs .nav-link {
    border: none;
    border-radius: 10px 10px 0 0;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    background-color: #e9ecef;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-color: transparent;
}

/* Image Styling */
.img-thumbnail {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.img-thumbnail:hover {
    transform: scale(1.1);
    border-color: #007bff;
}

/* Empty State Styling */
.text-center.py-5 {
    padding: 3rem 1rem !important;
}

.text-center.py-5 i {
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-responsive {
        border-radius: 10px;
    }
    
    .btn-sm {
        font-size: 0.7rem;
        padding: 4px 8px;
    }
    
    .d-flex.flex-column.gap-1 {
        gap: 0.25rem !important;
    }
}

/* Custom Modal Styling */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 20px 24px;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 20px 24px;
    background-color: #f8f9fa;
}

.modal-title {
    font-size: 1.2rem;
    font-weight: 600;
}

/* Custom form styling in modals */
.modal .form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.modal .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.modal .alert {
    border-radius: 10px;
    border: none;
    font-size: 0.9rem;
}

.modal .btn {
    padding: 10px 20px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, setting up event listeners...');
    
    let currentRequestId = null;
    let currentBookTitle = null;
    let currentButton = null;
    
    // Get modal elements
    const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    
    // Event delegation for approve buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.approve-btn-direct')) {
            const button = e.target.closest('.approve-btn-direct');
            currentRequestId = button.getAttribute('data-request-id');
            currentBookTitle = button.getAttribute('data-request-title');
            currentButton = button;
            
            console.log('Approve button clicked:', currentRequestId, currentBookTitle);
            
            // Update modal content
            document.getElementById('approveBookTitle').textContent = currentBookTitle;
            
            // Show modal
            approveModal.show();
        }
        
        if (e.target.closest('.reject-btn-direct')) {
            const button = e.target.closest('.reject-btn-direct');
            currentRequestId = button.getAttribute('data-request-id');
            currentBookTitle = button.getAttribute('data-request-title');
            currentButton = button;
            
            console.log('Reject button clicked:', currentRequestId, currentBookTitle);
            
            // Update modal content and clear previous reason
            document.getElementById('rejectBookTitle').textContent = currentBookTitle;
            document.getElementById('rejectReason').value = '';
            
            // Show modal
            rejectModal.show();
        }
    });
    
    // Handle approve confirmation
    document.getElementById('confirmApproveBtn').addEventListener('click', function() {
        if (!currentRequestId || !currentButton) return;
        
        console.log('User confirmed approval, processing...');
        
        // Store original button state
        const originalHTML = currentButton.innerHTML;
        
        // Add loading state to button
        currentButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Approving...';
        currentButton.disabled = true;
        currentButton.classList.add('btn-secondary');
        currentButton.classList.remove('btn-success');
        
        // Hide modal
        approveModal.hide();
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!csrfToken) {
            alert('Error: CSRF token not found. Please refresh the page and try again.');
            // Reset button on error
            currentButton.innerHTML = originalHTML;
            currentButton.disabled = false;
            currentButton.classList.remove('btn-secondary');
            currentButton.classList.add('btn-success');
            return;
        }
        
        // Make AJAX request
        fetch(`/admin/book-requests/${currentRequestId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'PATCH'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessAlert(data.message);
                
                // Remove the table row with animation
                const tableRow = currentButton.closest('tr');
                if (tableRow) {
                    tableRow.style.transition = 'all 0.5s ease';
                    tableRow.style.backgroundColor = '#d4edda';
                    tableRow.style.transform = 'scale(0.95)';
                    tableRow.style.opacity = '0.7';
                    
                    setTimeout(() => {
                        tableRow.style.height = '0px';
                        tableRow.style.padding = '0px';
                        tableRow.style.margin = '0px';
                        tableRow.style.overflow = 'hidden';
                        
                        setTimeout(() => {
                            tableRow.remove();
                            
                            // Check if table is empty and show empty state
                            const tbody = document.querySelector('.table tbody');
                            if (tbody && tbody.children.length === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        }, 500);
                    }, 1000);
                }
                
                console.log('Book approved successfully and removed from table');
            } else {
                // Show error message
                showErrorAlert(data.message || 'Failed to approve book request.');
                
                // Reset button on error
                currentButton.innerHTML = originalHTML;
                currentButton.disabled = false;
                currentButton.classList.remove('btn-secondary');
                currentButton.classList.add('btn-success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('An error occurred while approving the book request.');
            
            // Reset button on error
            currentButton.innerHTML = originalHTML;
            currentButton.disabled = false;
            currentButton.classList.remove('btn-secondary');
            currentButton.classList.add('btn-success');
        });
    });
    
    // Handle reject confirmation
    document.getElementById('confirmRejectBtn').addEventListener('click', function() {
        if (!currentRequestId || !currentButton) return;
        
        const reason = document.getElementById('rejectReason').value.trim();
        if (!reason) {
            alert('Please provide a reason for rejection.');
            document.getElementById('rejectReason').focus();
            return;
        }
        
        console.log('User confirmed rejection, processing...');
        
        // Store original button state
        const originalHTML = currentButton.innerHTML;
        
        // Add loading state to button
        currentButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Rejecting...';
        currentButton.disabled = true;
        currentButton.classList.add('btn-secondary');
        currentButton.classList.remove('btn-danger');
        
        // Hide modal
        rejectModal.hide();
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!csrfToken) {
            alert('Error: CSRF token not found. Please refresh the page and try again.');
            // Reset button on error
            currentButton.innerHTML = originalHTML;
            currentButton.disabled = false;
            currentButton.classList.remove('btn-secondary');
            currentButton.classList.add('btn-danger');
            return;
        }
        
        // Make AJAX request
        fetch(`/admin/book-requests/${currentRequestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'PATCH',
                admin_notes: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessAlert(data.message);
                
                // Remove the table row with animation
                const tableRow = currentButton.closest('tr');
                if (tableRow) {
                    tableRow.style.transition = 'all 0.5s ease';
                    tableRow.style.backgroundColor = '#f8d7da';
                    tableRow.style.transform = 'scale(0.95)';
                    tableRow.style.opacity = '0.7';
                    
                    setTimeout(() => {
                        tableRow.style.height = '0px';
                        tableRow.style.padding = '0px';
                        tableRow.style.margin = '0px';
                        tableRow.style.overflow = 'hidden';
                        
                        setTimeout(() => {
                            tableRow.remove();
                            
                            // Check if table is empty and show empty state
                            const tbody = document.querySelector('.table tbody');
                            if (tbody && tbody.children.length === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        }, 500);
                    }, 1000);
                }
                
                console.log('Book rejected successfully and removed from table');
            } else {
                // Show error message
                showErrorAlert(data.message || 'Failed to reject book request.');
                
                // Reset button on error
                currentButton.innerHTML = originalHTML;
                currentButton.disabled = false;
                currentButton.classList.remove('btn-secondary');
                currentButton.classList.add('btn-danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('An error occurred while rejecting the book request.');
            
            // Reset button on error
            currentButton.innerHTML = originalHTML;
            currentButton.disabled = false;
            currentButton.classList.remove('btn-secondary');
            currentButton.classList.add('btn-danger');
        });
    });
    
    // Helper function to show success alerts
    function showSuccessAlert(message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-success');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insert at top of container
        const container = document.querySelector('.container-fluid .row .col-md-12');
        if (container) {
            const firstCard = container.querySelector('.card');
            if (firstCard) {
                firstCard.insertAdjacentHTML('beforebegin', alertHtml);
            }
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const newAlert = document.querySelector('.alert-success');
            if (newAlert) {
                newAlert.remove();
            }
        }, 5000);
    }
    
    // Helper function to show error alerts
    function showErrorAlert(message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-danger');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insert at top of container
        const container = document.querySelector('.container-fluid .row .col-md-12');
        if (container) {
            const firstCard = container.querySelector('.card');
            if (firstCard) {
                firstCard.insertAdjacentHTML('beforebegin', alertHtml);
            }
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const newAlert = document.querySelector('.alert-danger');
            if (newAlert) {
                newAlert.remove();
            }
        }, 5000);
    }
    
    console.log('Event listeners set up successfully');
});
</script>
@endsection
