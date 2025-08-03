<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approved_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_request_id')->constrained('book_requests')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('users')->onDelete('cascade');
            
            // Book details (duplicated from book_requests for easy access)
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->text('description')->nullable();
            $table->string('genre');
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor']);
            $table->string('image_path')->nullable();
            $table->decimal('rental_price_per_day', 8, 2);
            $table->decimal('security_deposit', 8, 2);
            
            // Approval details
            $table->timestamp('approved_at');
            $table->text('admin_notes')->nullable();
            
            // Status tracking
            $table->enum('book_status', ['available', 'rented', 'maintenance', 'unavailable'])->default('available');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['lender_id', 'book_status']);
            $table->index(['book_status', 'is_active']);
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_books');
    }
};
