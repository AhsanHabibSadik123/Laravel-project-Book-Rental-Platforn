@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">User Management</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Users</h5>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Users Found</h5>
                            <p class="text-muted">There are no users registered yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
