<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BookStore') }} - Your Book Rental Marketplace</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .stats-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="fas fa-book me-2"></i>BookStore
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Stats</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Join Now
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('books.index') }}">My Books</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Your Book Rental Marketplace
                    </h1>
                    <p class="lead mb-4">
                        Rent books from fellow readers or earn money by lending your collection. 
                        Join our community of book lovers today!
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                        <a href="#how-it-works" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-play me-2"></i>Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-books display-1" style="font-size: 8rem; opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-3">Why Choose BookStore?</h2>
                    <p class="text-muted">Connect with book lovers in your community</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-book-reader text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-center">For Borrowers</h4>
                        <p class="text-muted text-center">
                            Access thousands of books at affordable rates. 
                            Read more, spend less!
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Browse local collections</li>
                            <li><i class="fas fa-check text-success me-2"></i>Affordable daily rates</li>
                            <li><i class="fas fa-check text-success me-2"></i>Easy return process</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-hand-holding-usd text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-center">For Lenders</h4>
                        <p class="text-muted text-center">
                            Turn your book collection into a source of income. 
                            Help others while earning!
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Earn from your books</li>
                            <li><i class="fas fa-check text-success me-2"></i>Set your own rates</li>
                            <li><i class="fas fa-check text-success me-2"></i>Secure transactions</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-shield-alt text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-center">Safe & Secure</h4>
                        <p class="text-muted text-center">
                            Our platform ensures secure transactions and 
                            protects both lenders and borrowers.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Verified users</li>
                            <li><i class="fas fa-check text-success me-2"></i>Security deposits</li>
                            <li><i class="fas fa-check text-success me-2"></i>Rating system</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-3">How It Works</h2>
                    <p class="text-muted">Get started in just a few simple steps</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <span class="fw-bold">1</span>
                        </div>
                    </div>
                    <h5>Sign Up</h5>
                    <p class="text-muted">Create your account as a borrower, lender, or both</p>
                </div>
                
                <div class="col-md-3 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <span class="fw-bold">2</span>
                        </div>
                    </div>
                    <h5>Browse or List</h5>
                    <p class="text-muted">Find books to rent or list your own collection</p>
                </div>
                
                <div class="col-md-3 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <span class="fw-bold">3</span>
                        </div>
                    </div>
                    <h5>Connect</h5>
                    <p class="text-muted">Request rentals or approve rental requests</p>
                </div>
                
                <div class="col-md-3 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <span class="fw-bold">4</span>
                        </div>
                    </div>
                    <h5>Enjoy</h5>
                    <p class="text-muted">Read great books or earn from your collection</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 stat-item mb-4">
                    <div class="stat-number">1000+</div>
                    <h5>Books Available</h5>
                    <p class="text-muted">Wide variety of genres</p>
                </div>
                <div class="col-md-3 stat-item mb-4">
                    <div class="stat-number">500+</div>
                    <h5>Happy Users</h5>
                    <p class="text-muted">Growing community</p>
                </div>
                <div class="col-md-3 stat-item mb-4">
                    <div class="stat-number">2000+</div>
                    <h5>Successful Rentals</h5>
                    <p class="text-muted">Trusted platform</p>
                </div>
                <div class="col-md-3 stat-item mb-4">
                    <div class="stat-number">$50k+</div>
                    <h5>Earned by Lenders</h5>
                    <p class="text-muted">Turn books into income</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Ready to Start Your Reading Journey?</h2>
            <p class="lead mb-4">Join thousands of book lovers in our community</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="fas fa-rocket me-2"></i>Get Started Today
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-book me-2"></i>BookStore</h5>
                    <p class="text-muted">Your trusted book rental marketplace</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} BookStore. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
