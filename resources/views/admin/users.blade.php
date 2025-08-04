@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-primary">
                    <i class="fas fa-users me-2"></i>User Management
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

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-primary bg-opacity-15 p-3 me-3">
                                    <i class="fas fa-users fa-lg text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-primary mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                                    <small class="text-muted">Total Users</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-success bg-opacity-15 p-3 me-3">
                                    <i class="fas fa-check-circle fa-lg text-success"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-success mb-0">{{ $stats['verified_users'] ?? 0 }}</h3>
                                    <small class="text-muted">Verified Users</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-info bg-opacity-15 p-3 me-3">
                                    <i class="fas fa-book fa-lg text-info"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-info mb-0">{{ $stats['lenders'] ?? 0 }}</h3>
                                    <small class="text-muted">Lenders</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle bg-warning bg-opacity-15 p-3 me-3">
                                    <i class="fas fa-user-graduate fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-warning mb-0">{{ $stats['borrowers'] ?? 0 }}</h3>
                                    <small class="text-muted">Borrowers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Filters & Search
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Users</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ $search ?? '' }}" placeholder="Name, email, or phone...">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="all" {{ ($role ?? '') === 'all' ? 'selected' : '' }}>All Roles</option>
                                <option value="lender" {{ ($role ?? '') === 'lender' ? 'selected' : '' }}>Lenders</option>
                                <option value="borrower" {{ ($role ?? '') === 'borrower' ? 'selected' : '' }}>Borrowers</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Verification Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ ($status ?? '') === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="verified" {{ ($status ?? '') === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="unverified" {{ ($status ?? '') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>All Users ({{ $users->total() }} total)
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Contact Info</th>
                                        <th>Role</th>
                                        <th>Stats</th>
                                        <th>Wallet</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $user->name }}</strong><br>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $user->email }}<br>
                                                    @if($user->phone)
                                                        <small class="text-muted">{{ $user->phone }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->role === 'lender')
                                                    <span class="badge bg-success">Lender</span>
                                                @elseif($user->role === 'borrower')
                                                    <span class="badge bg-primary">Borrower</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    @if($user->role === 'lender')
                                                        <small class="text-muted">Books: {{ $user->books_count ?? 0 }}</small><br>
                                                        <small class="text-muted">Rentals as Lender: {{ $user->rentals_as_lender_count ?? 0 }}</small>
                                                    @elseif($user->role === 'borrower')
                                                        <small class="text-muted">Rentals: {{ $user->rentals_as_borrower_count ?? 0 }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-success">${{ number_format($user->wallet_balance, 2) }}</strong>
                                            </td>
                                            <td>
                                                @if($user->is_verified)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock"></i> Unverified
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <a href="{{ route('admin.users.show', $user) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-success verify-btn" 
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}"
                                                            data-current-status="{{ $user->is_verified ? 'verified' : 'unverified' }}">
                                                        <i class="fas fa-{{ $user->is_verified ? 'times' : 'check' }} me-1"></i> 
                                                        {{ $user->is_verified ? 'Unverify' : 'Verify' }}
                                                    </button>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-warning role-btn" 
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}"
                                                            data-current-role="{{ $user->role }}">
                                                        <i class="fas fa-user-tag me-1"></i> Role
                                                    </button>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info wallet-btn" 
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}"
                                                            data-current-balance="{{ $user->wallet_balance }}">
                                                        <i class="fas fa-wallet me-1"></i> Wallet
                                                    </button>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-btn" 
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->appends(['search' => $search, 'role' => $role, 'status' => $status])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Users Found</h5>
                            <p class="text-muted">
                                @if($search || $role !== 'all' || $status !== 'all')
                                    No users match your current filters. Try adjusting your search criteria.
                                @else
                                    No users are registered yet.
                                @endif
                            </p>
                            @if($search || $role !== 'all' || $status !== 'all')
                                <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify/Unverify User Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="verifyModalLabel">
                    <i class="fas fa-user-check me-2"></i>Update User Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-user-shield fa-4x text-primary"></i>
                    </div>
                    <h4 class="text-primary fw-bold" id="verifyUserName">User Name</h4>
                    <p class="text-muted mb-0" id="verifyAction">Action message here</p>
                </div>
                
                <div class="mb-3">
                    <label for="verifyNotes" class="form-label fw-bold">Admin Notes (Optional)</label>
                    <textarea class="form-control" id="verifyNotes" rows="3" 
                              placeholder="Optional notes about this verification change..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmVerifyBtn">
                    <i class="fas fa-check me-1"></i>Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="roleModalLabel">
                    <i class="fas fa-user-tag me-2"></i>Change User Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-user-cog fa-4x text-warning"></i>
                    </div>
                    <h4 class="text-primary fw-bold" id="roleUserName">User Name</h4>
                    <p class="text-muted mb-0">Current role: <span id="currentRole" class="fw-bold"></span></p>
                </div>
                
                <div class="mb-3">
                    <label for="newRole" class="form-label fw-bold">New Role</label>
                    <select class="form-select" id="newRole" required>
                        <option value="">Select new role...</option>
                        <option value="borrower">Borrower</option>
                        <option value="lender">Lender</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="roleNotes" class="form-label fw-bold">Admin Notes (Optional)</label>
                    <textarea class="form-control" id="roleNotes" rows="3" 
                              placeholder="Optional notes about this role change..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmRoleBtn">
                    <i class="fas fa-user-tag me-1"></i>Change Role
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Wallet Modal -->
<div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="walletModalLabel">
                    <i class="fas fa-wallet me-2"></i>Update User Wallet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign fa-4x text-info"></i>
                    </div>
                    <h4 class="text-primary fw-bold" id="walletUserName">User Name</h4>
                    <p class="text-muted mb-0">Current balance: <span id="currentBalance" class="fw-bold text-success"></span></p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="walletAction" class="form-label fw-bold">Action</label>
                        <select class="form-select" id="walletAction" required>
                            <option value="">Select action...</option>
                            <option value="add">Add Money</option>
                            <option value="subtract">Subtract Money</option>
                            <option value="set">Set Balance</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="walletAmount" class="form-label fw-bold">Amount ($)</label>
                        <input type="number" class="form-control" id="walletAmount" 
                               min="0" max="99999.99" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="walletNotes" class="form-label fw-bold">Admin Notes <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="walletNotes" rows="3" 
                              placeholder="Reason for wallet adjustment (required)..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-info" id="confirmWalletBtn">
                    <i class="fas fa-dollar-sign me-1"></i>Update Wallet
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete User Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-user-times fa-4x text-danger"></i>
                    </div>
                    <h4 class="text-danger fw-bold" id="deleteUserName">User Name</h4>
                    <p class="text-muted mb-0">Are you sure you want to delete this user account?</p>
                </div>
                
                <div class="alert alert-warning">
                    <h6 class="fw-bold">⚠️ Warning:</h6>
                    <ul class="mb-0">
                        <li>This action <strong>CANNOT</strong> be undone</li>
                        <li>All user data will be permanently removed</li>
                        <li>User must have no active rentals or listed books</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Yes, Delete User
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced table styling */
.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
}

.table thead th {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: #ffffff !important;
    font-weight: 600;
    border: none;
    padding: 15px 12px;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
    background-color: #ffffff;
}

.table tbody tr:hover {
    background-color: #f8f9fa !important;
    transform: scale(1.005);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border: none;
    color: #212529;
    background-color: inherit;
}

/* Action buttons styling */
.btn-sm {
    padding: 6px 12px;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
    color: #ffffff !important;
    border: 1px solid transparent;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Statistics cards */
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

/* Modal styling */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    background-color: #ffffff;
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 20px 24px;
}

.modal-body {
    padding: 24px;
    background-color: #ffffff;
    color: #212529;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 20px 24px;
    background-color: #f8f9fa;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Badge styling */
.badge {
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 20px;
}

/* Responsive adjustments */
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentUserId = null;
    let currentUserName = null;
    let currentButton = null;
    
    // Get modal elements
    const verifyModal = new bootstrap.Modal(document.getElementById('verifyModal'));
    const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
    const walletModal = new bootstrap.Modal(document.getElementById('walletModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Verify/Unverify button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.verify-btn')) {
            const button = e.target.closest('.verify-btn');
            currentUserId = button.getAttribute('data-user-id');
            currentUserName = button.getAttribute('data-user-name');
            const currentStatus = button.getAttribute('data-current-status');
            currentButton = button;
            
            document.getElementById('verifyUserName').textContent = currentUserName;
            
            if (currentStatus === 'verified') {
                document.getElementById('verifyAction').textContent = 'Are you sure you want to unverify this user?';
                document.getElementById('confirmVerifyBtn').innerHTML = '<i class="fas fa-times me-1"></i>Unverify User';
                document.getElementById('confirmVerifyBtn').className = 'btn btn-warning';
            } else {
                document.getElementById('verifyAction').textContent = 'Are you sure you want to verify this user?';
                document.getElementById('confirmVerifyBtn').innerHTML = '<i class="fas fa-check me-1"></i>Verify User';
                document.getElementById('confirmVerifyBtn').className = 'btn btn-success';
            }
            
            document.getElementById('verifyNotes').value = '';
            verifyModal.show();
        }
        
        // Role change button click
        if (e.target.closest('.role-btn')) {
            const button = e.target.closest('.role-btn');
            currentUserId = button.getAttribute('data-user-id');
            currentUserName = button.getAttribute('data-user-name');
            const currentRole = button.getAttribute('data-current-role');
            currentButton = button;
            
            document.getElementById('roleUserName').textContent = currentUserName;
            document.getElementById('currentRole').textContent = currentRole;
            document.getElementById('newRole').value = '';
            document.getElementById('roleNotes').value = '';
            
            // Remove the current role from options
            const roleSelect = document.getElementById('newRole');
            Array.from(roleSelect.options).forEach(option => {
                option.style.display = option.value === currentRole ? 'none' : 'block';
            });
            
            roleModal.show();
        }
        
        // Wallet button click
        if (e.target.closest('.wallet-btn')) {
            const button = e.target.closest('.wallet-btn');
            currentUserId = button.getAttribute('data-user-id');
            currentUserName = button.getAttribute('data-user-name');
            const currentBalance = button.getAttribute('data-current-balance');
            currentButton = button;
            
            document.getElementById('walletUserName').textContent = currentUserName;
            document.getElementById('currentBalance').textContent = '$' + parseFloat(currentBalance).toFixed(2);
            document.getElementById('walletAction').value = '';
            document.getElementById('walletAmount').value = '';
            document.getElementById('walletNotes').value = '';
            
            walletModal.show();
        }
        
        // Delete button click
        if (e.target.closest('.delete-btn')) {
            const button = e.target.closest('.delete-btn');
            currentUserId = button.getAttribute('data-user-id');
            currentUserName = button.getAttribute('data-user-name');
            currentButton = button;
            
            document.getElementById('deleteUserName').textContent = currentUserName;
            deleteModal.show();
        }
    });
    
    // Confirm verify/unverify
    document.getElementById('confirmVerifyBtn').addEventListener('click', function() {
        if (!currentUserId) return;
        
        const isCurrentlyVerified = currentButton.getAttribute('data-current-status') === 'verified';
        const newStatus = !isCurrentlyVerified;
        const notes = document.getElementById('verifyNotes').value.trim();
        
        updateUserStatus(currentUserId, newStatus, notes);
        verifyModal.hide();
    });
    
    // Confirm role change
    document.getElementById('confirmRoleBtn').addEventListener('click', function() {
        if (!currentUserId) return;
        
        const newRole = document.getElementById('newRole').value;
        const notes = document.getElementById('roleNotes').value.trim();
        
        if (!newRole) {
            alert('Please select a new role.');
            return;
        }
        
        updateUserRole(currentUserId, newRole, notes);
        roleModal.hide();
    });
    
    // Confirm wallet update
    document.getElementById('confirmWalletBtn').addEventListener('click', function() {
        if (!currentUserId) return;
        
        const action = document.getElementById('walletAction').value;
        const amount = document.getElementById('walletAmount').value;
        const notes = document.getElementById('walletNotes').value.trim();
        
        if (!action || !amount || !notes) {
            alert('Please fill in all required fields.');
            return;
        }
        
        updateUserWallet(currentUserId, action, amount, notes);
        walletModal.hide();
    });
    
    // Confirm delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!currentUserId) return;
        
        deleteUser(currentUserId);
        deleteModal.hide();
    });
    
    // Helper functions
    function updateUserStatus(userId, isVerified, notes) {
        fetch(`/admin/users/${userId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                is_verified: isVerified,
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload(); // Reload to update the UI
            } else {
                showAlert('danger', data.message || 'Failed to update user status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while updating user status.');
        });
    }
    
    function updateUserRole(userId, role, notes) {
        fetch(`/admin/users/${userId}/role`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                role: role,
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload(); // Reload to update the UI
            } else {
                showAlert('danger', data.message || 'Failed to update user role.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while updating user role.');
        });
    }
    
    function updateUserWallet(userId, action, amount, notes) {
        fetch(`/admin/users/${userId}/wallet`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: action,
                amount: amount,
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload(); // Reload to update the UI
            } else {
                showAlert('danger', data.message || 'Failed to update user wallet.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while updating user wallet.');
        });
    }
    
    function deleteUser(userId) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload(); // Reload to update the UI
            } else {
                showAlert('danger', data.message || 'Failed to delete user.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while deleting user.');
        });
    }
    
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid .row .col-md-12');
        if (container) {
            const firstCard = container.querySelector('.card');
            if (firstCard) {
                firstCard.insertAdjacentHTML('beforebegin', alertHtml);
            }
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const newAlert = document.querySelector(`.alert-${type}`);
            if (newAlert) {
                newAlert.remove();
            }
        }, 5000);
    }
});
</script>
@endsection
