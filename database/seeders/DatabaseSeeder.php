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
        // 1. Create Departments
        $departments = [
            'IT Support',
            'Human Resources',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Legal',
            'Customer Success',
            'Research & Development',
            'Administration',
        ];

        $deptModels = [];
        foreach ($departments as $name) {
            $deptModels[] = \App\Models\Department::create(['name' => $name]);
        }

        // 2. Create Admin
        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'password' => 'password',
            'role' => 'admin',
            'department_id' => $deptModels[0]->id, // IT Support
        ]);

        // 3. Create 300 Users
        User::factory()->count(300)->create([
            'department_id' => fn() => $deptModels[array_rand($deptModels)]->id,
        ]);

        // 4. Create 70 Tickets
        \App\Models\Ticket::factory()->count(70)->create()->each(function ($ticket) {
            // Create initial log for each ticket
            \App\Models\TicketLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => $ticket->user_id,
                'old_status' => null,
                'new_status' => 'pending',
                'remarks' => 'Ticket submitted.',
            ]);

            // If ticket is not pending, add another log for status change
            if ($ticket->status !== 'pending') {
                \App\Models\TicketLog::create([
                    'ticket_id' => $ticket->id,
                    'changed_by' => $ticket->assigned_to ?? User::where('role', 'admin')->first()->id,
                    'old_status' => 'pending',
                    'new_status' => $ticket->status,
                    'remarks' => $ticket->admin_remarks ?? 'Status updated by admin.',
                ]);
            }
        });
    }
}
