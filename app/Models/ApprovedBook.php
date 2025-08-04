<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_request_id',
        'book_id',
        'lender_id',
        'approved_by',
        'title',
        'author',
        'isbn',
        'description',
        'genre',
        'condition',
        'image_path',
        'rental_price_per_day',
        'security_deposit',
        'approved_at',
        'admin_notes',
        'book_status',
        'is_active'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rental_price_per_day' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function bookRequest()
    {
        return $this->belongsTo(BookRequest::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('book_status', 'available')
                    ->where('is_active', true);
    }

    public function scopeByLender($query, $lenderId)
    {
        return $query->where('lender_id', $lenderId);
    }

    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }

    // Accessors
    public function getFormattedRentalPriceAttribute()
    {
        return '$' . number_format($this->rental_price_per_day, 2);
    }

    public function getFormattedSecurityDepositAttribute()
    {
        return '$' . number_format($this->security_deposit, 2);
    }
}
