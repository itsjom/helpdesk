<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles & permissions first — AdminSeeder depends on the 'admin' role existing
        $this->call(RoleSeeder::class);

        // Create Admin
        $this->call(AdminSeeder::class);
    }
}
