<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'is_verified',
        'wallet_balance',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is lender
     */
    public function isLender(): bool
    {
        return $this->role === 'lender';
    }

    /**
     * Check if user is borrower
     */
    public function isBorrower(): bool
    {
        return $this->role === 'borrower';
    }

    /**
     * Books owned by this lender
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'lender_id');
    }

    /**
     * Rentals as borrower
     */
    public function borrowedRentals()
    {
        return $this->hasMany(Rental::class, 'borrower_id');
    }

    /**
     * Rentals as lender
     */
    public function lentRentals()
    {
        return $this->hasMany(Rental::class, 'lender_id');
    }
}
