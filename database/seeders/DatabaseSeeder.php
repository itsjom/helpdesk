<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'password' => 'password', // Will be hashed by model cast
            'role' => 'admin',
            'department' => 'IT Department',
        ]);

        User::create([
            'name' => 'Juan dela Cruz',
            'username' => 'jdelacruz',
            'password' => 'password',
            'role' => 'user',
            'department' => 'Finance',
        ]);

        User::create([
            'name' => 'Maria Santos',
            'username' => 'msantos',
            'password' => 'password',
            'role' => 'user',
            'department' => 'HR',
        ]);
    }
}
