@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">My Book Requests</h1>
                <a href="{{ route('book-requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Submit New Request
                </a>
            </div>

            @if($bookRequests->count() > 0)
                <div class="row">
                    @foreach($bookRequests as $request)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                @if($request->image_path)
                                    <img src="{{ asset('storage/' . $request->image_path) }}" 
                                         class="card-img-top" 
                                         alt="{{ $request->title }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $request->title }}</h5>
                                    <p class="card-text text-muted mb-2">by {{ $request->author }}</p>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Genre: {{ $request->genre }}</small><br>
                                        <small class="text-muted">Condition: {{ ucfirst(str_replace('_', ' ', $request->condition)) }}</small>
                                    </div>

                                    <div class="mb-2">
                                        <strong class="text-primary">${{ number_format($request->rental_price_per_day, 2) }}/day</strong><br>
                                        <small class="text-muted">Deposit: ${{ number_format($request->security_deposit, 2) }}</small>
                                    </div>

                                    <div class="mb-3">
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending Review
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-auto">
                                        <small class="text-muted">
                                            Submitted: {{ $request->created_at->format('M d, Y') }}
                                        </small>
                                        @if($request->approved_at)
                                            <br><small class="text-muted">
                                                {{ $request->status === 'approved' ? 'Approved' : 'Rejected' }}: {{ $request->approved_at->format('M d, Y') }}
                                            </small>
                                        @endif
                                    </div>

                                    @if($request->admin_notes)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Admin Notes:</strong> {{ $request->admin_notes }}
                                            </small>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('book-requests.show', $request) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookRequests->links() }}
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Book Requests Yet</h5>
                        <p class="text-muted">You haven't submitted any book requests. Start by submitting your first book for approval.</p>
                        <a href="{{ route('book-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Submit Your First Book Request
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
