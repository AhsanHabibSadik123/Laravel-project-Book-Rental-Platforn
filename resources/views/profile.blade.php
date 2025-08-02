@extends('layouts.app')

@section('title', 'Profile - BookStore')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>My Profile
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ Auth::user()->name }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ Auth::user()->phone }}">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" 
                                       value="{{ ucfirst(Auth::user()->role) }}" readonly>
                                <small class="text-muted">Contact admin to change your role</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ Auth::user()->address }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                      placeholder="Tell us about yourself...">{{ Auth::user()->bio }}</textarea>
                        </div>
                        
                        @if(Auth::user()->role === 'lender')
                        <div class="mb-3">
                            <label for="wallet_balance" class="form-label">Wallet Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" 
                                       value="{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}" readonly>
                            </div>
                            <small class="text-muted">Earnings from book rentals</small>
                        </div>
                        @endif
                        
                        <hr>
                        
                        <h5 class="mb-3">Change Password</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Account Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Account Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h4 class="text-primary">{{ \Carbon\Carbon::parse(Auth::user()->created_at)->diffForHumans() }}</h4>
                            <small class="text-muted">Member Since</small>
                        </div>
                        
                        @if(Auth::user()->role === 'lender')
                            <div class="col-md-3 text-center">
                                <h4 class="text-success">{{ App\Models\Book::where('lender_id', Auth::id())->count() }}</h4>
                                <small class="text-muted">Books Listed</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="text-warning">{{ App\Models\Book::where('lender_id', Auth::id())->where('status', 'rented')->count() }}</h4>
                                <small class="text-muted">Currently Rented</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="text-info">${{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</h4>
                                <small class="text-muted">Total Earned</small>
                            </div>
                        @else
                            <div class="col-md-3 text-center">
                                <h4 class="text-success">5</h4>
                                <small class="text-muted">Books Rented</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="text-warning">2</h4>
                                <small class="text-muted">Active Rentals</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="text-info">$156</h4>
                                <small class="text-muted">Total Spent</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
