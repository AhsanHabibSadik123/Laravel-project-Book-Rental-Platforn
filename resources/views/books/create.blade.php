<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - {{ config('app.name', 'BookStore') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .preview-image {
            max-width: 200px;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: border-color 0.3s ease;
        }
        .upload-area:hover {
            border-color: #007bff;
        }
        .upload-area.dragover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-book me-2"></i>BookStore
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('books.index') }}">
                    <i class="fas fa-arrow-left me-1"></i>My Books
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="form-container">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="h3 mb-3 fw-bold text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add New Book
                </h1>
                <p class="text-muted">List your book for rent and start earning!</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Add Book Form -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Book Image Upload -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="image" class="form-label fw-bold">
                                    <i class="fas fa-image me-2"></i>Book Cover Image
                                </label>
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="mb-2">Click to upload or drag and drop</p>
                                    <p class="text-muted small">PNG, JPG, GIF up to 2MB</p>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           style="display: none;">
                                </div>
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="previewImg" class="preview-image" src="" alt="Preview">
                                </div>
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Basic Book Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label fw-bold">
                                    <i class="fas fa-book me-2"></i>Book Title *
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required
                                       placeholder="Enter book title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="author" class="form-label fw-bold">
                                    <i class="fas fa-user-edit me-2"></i>Author *
                                </label>
                                <input type="text" 
                                       class="form-control @error('author') is-invalid @enderror" 
                                       id="author" 
                                       name="author" 
                                       value="{{ old('author') }}" 
                                       required
                                       placeholder="Enter author name">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="isbn" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-2"></i>ISBN (Optional)
                                </label>
                                <input type="text" 
                                       class="form-control @error('isbn') is-invalid @enderror" 
                                       id="isbn" 
                                       name="isbn" 
                                       value="{{ old('isbn') }}" 
                                       placeholder="Enter ISBN number">
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="genre" class="form-label fw-bold">
                                    <i class="fas fa-tags me-2"></i>Genre *
                                </label>
                                <select class="form-control @error('genre') is-invalid @enderror" 
                                        id="genre" 
                                        name="genre" 
                                        required>
                                    <option value="">Select Genre</option>
                                    <option value="Fiction" {{ old('genre') == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                                    <option value="Non-Fiction" {{ old('genre') == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
                                    <option value="Mystery" {{ old('genre') == 'Mystery' ? 'selected' : '' }}>Mystery</option>
                                    <option value="Romance" {{ old('genre') == 'Romance' ? 'selected' : '' }}>Romance</option>
                                    <option value="Science Fiction" {{ old('genre') == 'Science Fiction' ? 'selected' : '' }}>Science Fiction</option>
                                    <option value="Fantasy" {{ old('genre') == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                                    <option value="Thriller" {{ old('genre') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                                    <option value="Biography" {{ old('genre') == 'Biography' ? 'selected' : '' }}>Biography</option>
                                    <option value="History" {{ old('genre') == 'History' ? 'selected' : '' }}>History</option>
                                    <option value="Self-Help" {{ old('genre') == 'Self-Help' ? 'selected' : '' }}>Self-Help</option>
                                    <option value="Educational" {{ old('genre') == 'Educational' ? 'selected' : '' }}>Educational</option>
                                    <option value="Other" {{ old('genre') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('genre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Book Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2"></i>Description *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      placeholder="Describe the book content, condition, and any special notes...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Condition and Pricing -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="condition" class="form-label fw-bold">
                                    <i class="fas fa-star me-2"></i>Condition *
                                </label>
                                <select class="form-control @error('condition') is-invalid @enderror" 
                                        id="condition" 
                                        name="condition" 
                                        required>
                                    <option value="">Select Condition</option>
                                    <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>⭐ Excellent - Like new</option>
                                    <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>👍 Good - Minor wear</option>
                                    <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>📖 Fair - Noticeable wear</option>
                                    <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>📚 Poor - Heavy wear</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="rental_price_per_day" class="form-label fw-bold">
                                    <i class="fas fa-dollar-sign me-2"></i>Daily Rental Price *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('rental_price_per_day') is-invalid @enderror" 
                                           id="rental_price_per_day" 
                                           name="rental_price_per_day" 
                                           value="{{ old('rental_price_per_day') }}" 
                                           step="0.01"
                                           min="0.01"
                                           max="999.99"
                                           required
                                           placeholder="0.00">
                                </div>
                                @error('rental_price_per_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="security_deposit" class="form-label fw-bold">
                                    <i class="fas fa-shield-alt me-2"></i>Security Deposit *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('security_deposit') is-invalid @enderror" 
                                           id="security_deposit" 
                                           name="security_deposit" 
                                           value="{{ old('security_deposit') }}" 
                                           step="0.01"
                                           min="0"
                                           max="9999.99"
                                           required
                                           placeholder="0.00">
                                </div>
                                @error('security_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between pt-3">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Image Upload Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            // Click to upload
            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            // File selection
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0]);
                }
            });

            function handleFileSelect(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
</body>
</html>
