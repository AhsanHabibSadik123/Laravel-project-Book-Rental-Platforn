<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user if doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@bookstore.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@bookstore.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_verified' => true,
                'phone' => '123-456-7890',
                'address' => 'Admin Office',
                'wallet_balance' => 0.00,
                'bio' => 'System Administrator'
            ]
        );

        // Create a test lender user
        User::firstOrCreate(
            ['email' => 'lender@bookstore.com'],
            [
                'name' => 'Test Lender',
                'email' => 'lender@bookstore.com',
                'password' => Hash::make('lender123'),
                'role' => 'lender',
                'is_verified' => true,
                'phone' => '123-456-7891',
                'address' => '123 Lender Street',
                'wallet_balance' => 100.00,
                'bio' => 'I love sharing books!'
            ]
        );

        // Create a test borrower user
        User::firstOrCreate(
            ['email' => 'borrower@bookstore.com'],
            [
                'name' => 'Test Borrower',
                'email' => 'borrower@bookstore.com',
                'password' => Hash::make('borrower123'),
                'role' => 'borrower',
                'is_verified' => true,
                'phone' => '123-456-7892',
                'address' => '456 Borrower Avenue',
                'wallet_balance' => 50.00,
                'bio' => 'Book enthusiast and reader'
            ]
        );
    }
}
