<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::where('name', 'IT Support')->first() ?? Department::first();

        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'password' => 'password',
            'role' => 'admin',
            'department_id' => $department?->id,
        ]);
    }
}
