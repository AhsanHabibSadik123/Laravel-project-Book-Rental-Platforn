@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Submit Book Request</h1>
                <a href="{{ route('book-requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to My Requests
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add New Book for Rental</h5>
                    <small class="text-muted">Submit your book details for admin approval</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('book-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Book Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('author') is-invalid @enderror" 
                                           id="author" 
                                           name="author" 
                                           value="{{ old('author') }}" 
                                           required>
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="isbn" class="form-label">ISBN (Optional)</label>
                                    <input type="text" 
                                           class="form-control @error('isbn') is-invalid @enderror" 
                                           id="isbn" 
                                           name="isbn" 
                                           value="{{ old('isbn') }}" 
                                           placeholder="e.g., 978-3-16-148410-0">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                                    <select class="form-select @error('genre') is-invalid @enderror" 
                                            id="genre" 
                                            name="genre" 
                                            required>
                                        <option value="">Select a genre</option>
                                        <option value="Fiction" {{ old('genre') === 'Fiction' ? 'selected' : '' }}>Fiction</option>
                                        <option value="Non-Fiction" {{ old('genre') === 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
                                        <option value="Mystery" {{ old('genre') === 'Mystery' ? 'selected' : '' }}>Mystery</option>
                                        <option value="Romance" {{ old('genre') === 'Romance' ? 'selected' : '' }}>Romance</option>
                                        <option value="Science Fiction" {{ old('genre') === 'Science Fiction' ? 'selected' : '' }}>Science Fiction</option>
                                        <option value="Fantasy" {{ old('genre') === 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                                        <option value="Thriller" {{ old('genre') === 'Thriller' ? 'selected' : '' }}>Thriller</option>
                                        <option value="Horror" {{ old('genre') === 'Horror' ? 'selected' : '' }}>Horror</option>
                                        <option value="Biography" {{ old('genre') === 'Biography' ? 'selected' : '' }}>Biography</option>
                                        <option value="Autobiography" {{ old('genre') === 'Autobiography' ? 'selected' : '' }}>Autobiography</option>
                                        <option value="History" {{ old('genre') === 'History' ? 'selected' : '' }}>History</option>
                                        <option value="Self-Help" {{ old('genre') === 'Self-Help' ? 'selected' : '' }}>Self-Help</option>
                                        <option value="Business" {{ old('genre') === 'Business' ? 'selected' : '' }}>Business</option>
                                        <option value="Health & Fitness" {{ old('genre') === 'Health & Fitness' ? 'selected' : '' }}>Health & Fitness</option>
                                        <option value="Travel" {{ old('genre') === 'Travel' ? 'selected' : '' }}>Travel</option>
                                        <option value="Cooking" {{ old('genre') === 'Cooking' ? 'selected' : '' }}>Cooking</option>
                                        <option value="Art & Design" {{ old('genre') === 'Art & Design' ? 'selected' : '' }}>Art & Design</option>
                                        <option value="Technology" {{ old('genre') === 'Technology' ? 'selected' : '' }}>Technology</option>
                                        <option value="Education" {{ old('genre') === 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Children's Books" {{ old('genre') === "Children's Books" ? 'selected' : '' }}>Children's Books</option>
                                        <option value="Young Adult" {{ old('genre') === 'Young Adult' ? 'selected' : '' }}>Young Adult</option>
                                        <option value="Poetry" {{ old('genre') === 'Poetry' ? 'selected' : '' }}>Poetry</option>
                                        <option value="Drama" {{ old('genre') === 'Drama' ? 'selected' : '' }}>Drama</option>
                                        <option value="Comics & Graphic Novels" {{ old('genre') === 'Comics & Graphic Novels' ? 'selected' : '' }}>Comics & Graphic Novels</option>
                                        <option value="Religion & Spirituality" {{ old('genre') === 'Religion & Spirituality' ? 'selected' : '' }}>Religion & Spirituality</option>
                                        <option value="Philosophy" {{ old('genre') === 'Philosophy' ? 'selected' : '' }}>Philosophy</option>
                                        <option value="Psychology" {{ old('genre') === 'Psychology' ? 'selected' : '' }}>Psychology</option>
                                        <option value="Science" {{ old('genre') === 'Science' ? 'selected' : '' }}>Science</option>
                                        <option value="Mathematics" {{ old('genre') === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                        <option value="Other" {{ old('genre') === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Provide a detailed description of the book..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="condition" class="form-label">Condition <span class="text-danger">*</span></label>
                                    <select class="form-select @error('condition') is-invalid @enderror" 
                                            id="condition" 
                                            name="condition" 
                                            required>
                                        <option value="">Select condition</option>
                                        <option value="new" {{ old('condition') === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="like_new" {{ old('condition') === 'like_new' ? 'selected' : '' }}>Like New</option>
                                        <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="rental_price_per_day" class="form-label">Rental Price per Day <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('rental_price_per_day') is-invalid @enderror" 
                                               id="rental_price_per_day" 
                                               name="rental_price_per_day" 
                                               value="{{ old('rental_price_per_day') }}" 
                                               step="0.01" 
                                               min="0.01" 
                                               max="9999.99"
                                               placeholder="0.00"
                                               required>
                                        @error('rental_price_per_day')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="security_deposit" class="form-label">Security Deposit <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('security_deposit') is-invalid @enderror" 
                                               id="security_deposit" 
                                               name="security_deposit" 
                                               value="{{ old('security_deposit') }}" 
                                               step="0.01" 
                                               min="0" 
                                               max="99999.99"
                                               placeholder="0.00"
                                               required>
                                        @error('security_deposit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Book Image (Optional)</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            <div class="form-text">Upload a clear image of your book. Max size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Note:</strong> Your book request will be reviewed by our admin team. Once approved, your book will be listed on the platform for rent.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('book-requests.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
