<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create an Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_otp_verified' => true, // Skipping OTP for your main test account
        ]);

        // Create a Student
        User::create([
            'name' => 'Student User',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_otp_verified' => true,
        ]);
    }
}