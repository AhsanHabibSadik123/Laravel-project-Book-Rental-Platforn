<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books - {{ config('app.name', 'BookStore') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        .book-image {
            height: 200px;
            object-fit: cover;
            background-color: #f8f9fa;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .price-tag {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
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
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 fw-bold text-primary">
                    <i class="fas fa-books me-2"></i>My Books
                </h1>
                <p class="text-muted mb-0">Manage your book collection and track rentals</p>
            </div>
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

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-book fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $books->total() }}</h5>
                        <p class="card-text small">Total Books</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $books->where('status', 'available')->count() }}</h5>
                        <p class="card-text small">Available</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <i class="fas fa-handshake fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $books->where('status', 'rented')->count() }}</h5>
                        <p class="card-text small">Rented Out</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-tools fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $books->where('status', 'maintenance')->count() }}</h5>
                        <p class="card-text small">Maintenance</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books Grid -->
        @if($books->count() > 0)
            <div class="row">
                @foreach($books as $book)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card book-card h-100">
                            <div class="position-relative">
                                @if($book->image_path)
                                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                                         class="card-img-top book-image" 
                                         alt="{{ $book->title }}">
                                @else
                                    <div class="card-img-top book-image d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <span class="badge status-badge
                                    @if($book->status === 'available') bg-success
                                    @elseif($book->status === 'rented') bg-warning
                                    @elseif($book->status === 'maintenance') bg-info
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold mb-2">{{ Str::limit($book->title, 30) }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-user me-1"></i>{{ $book->author }}
                                </p>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-tags me-1"></i>{{ $book->genre }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="price-tag">
                                        ${{ number_format($book->rental_price_per_day, 2) }}/day
                                    </span>
                                    <small class="text-muted">
                                        <i class="fas fa-star me-1"></i>{{ ucfirst($book->condition) }}
                                    </small>
                                </div>
                                
                                <p class="card-text small text-muted flex-grow-1">
                                    {{ Str::limit($book->description, 80) }}
                                </p>
                                
                                <!-- Action Buttons -->
                                <div class="mt-auto">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('books.show', $book) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $book->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $book->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Book</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete "<strong>{{ $book->title }}</strong>"?</p>
                                    <p class="text-danger small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        This action cannot be undone.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form method="POST" action="{{ route('books.destroy', $book) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Book</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-book-open fa-5x mb-4 text-muted"></i>
                <h4>No Books Yet</h4>
                <p class="mb-4">Start building your book collection and earn money by renting them out!</p>
                <a href="{{ route('books.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Add Your First Book
                </a>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
