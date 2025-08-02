<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'genre',
        'condition',
        'image_path',
        'rental_price_per_day',
        'security_deposit',
        'lender_id',
        'status',
    ];

    protected $casts = [
        'rental_price_per_day' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    /**
     * Book belongs to a lender (user)
     */
    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    /**
     * Book can have many rentals
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get current active rental
     */
    public function currentRental()
    {
        return $this->hasOne(Rental::class)->where('status', 'active');
    }

    /**
     * Check if book is currently rented
     */
    public function isRented(): bool
    {
        return $this->currentRental()->exists();
    }

    /**
     * Scope for available books
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('status', 'approved');
    }

    /**
     * Scope for approved books
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
