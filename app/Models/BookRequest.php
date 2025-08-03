<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
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
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'rental_price_per_day' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Book request belongs to a lender (user)
     */
    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    /**
     * Book request approved by admin (user)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
