@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Book Request Details</h1>
                <a href="{{ route('book-requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to My Requests
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Book Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($bookRequest->image_path)
                                    <div class="col-md-4 text-center mb-3">
                                        <img src="{{ asset('storage/' . $bookRequest->image_path) }}" 
                                             alt="{{ $bookRequest->title }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 300px;">
                                    </div>
                                @endif
                                
                                <div class="col-md-{{ $bookRequest->image_path ? '8' : '12' }}">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Title:</th>
                                            <td>{{ $bookRequest->title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Author:</th>
                                            <td>{{ $bookRequest->author }}</td>
                                        </tr>
                                        <tr>
                                            <th>Genre:</th>
                                            <td>{{ $bookRequest->genre }}</td>
                                        </tr>
                                        @if($bookRequest->isbn)
                                        <tr>
                                            <th>ISBN:</th>
                                            <td>{{ $bookRequest->isbn }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Condition:</th>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $bookRequest->condition)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Rental Price:</th>
                                            <td><strong class="text-success">${{ number_format($bookRequest->rental_price_per_day, 2) }}/day</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Security Deposit:</th>
                                            <td><strong class="text-warning">${{ number_format($bookRequest->security_deposit, 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <h6>Description:</h6>
                                <p class="text-muted">{{ $bookRequest->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Request Status</h6>
                        </div>
                        <div class="card-body text-center">
                            @if($bookRequest->status === 'pending')
                                <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                                <h5 class="text-warning">Pending Review</h5>
                                <p class="text-muted">Your request is being reviewed by our admin team.</p>
                            @elseif($bookRequest->status === 'approved')
                                <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                <h5 class="text-success">Approved</h5>
                                <p class="text-muted">Your book has been approved and is now available for rent!</p>
                            @else
                                <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                                <h5 class="text-danger">Rejected</h5>
                                <p class="text-muted">Unfortunately, your book request was not approved.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Request Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Request Submitted</h6>
                                        <p class="timeline-text">{{ $bookRequest->created_at->format('M d, Y \a\t h:i A') }}</p>
                                    </div>
                                </div>

                                @if($bookRequest->approved_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $bookRequest->status === 'approved' ? 'bg-success' : 'bg-danger' }}"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                {{ $bookRequest->status === 'approved' ? 'Request Approved' : 'Request Rejected' }}
                                            </h6>
                                            <p class="timeline-text">{{ $bookRequest->approved_at->format('M d, Y \a\t h:i A') }}</p>
                                            @if($bookRequest->approvedBy)
                                                <small class="text-muted">by {{ $bookRequest->approvedBy->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($bookRequest->admin_notes)
                                <div class="mt-3">
                                    <h6>Admin Notes:</h6>
                                    <div class="alert alert-info">
                                        {{ $bookRequest->admin_notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -26px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 12px;
    width: 2px;
    height: calc(100% + 8px);
    background-color: #dee2e6;
}

.timeline-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0;
}
</style>
@endsection
