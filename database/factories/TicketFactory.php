<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $priority = fake()->randomElement(['low', 'medium', 'high']);
        $status = fake()->randomElement(['pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled']);
        
        $serviceTypeCode = ServiceType::inRandomOrder()->first()?->code ?? 'other';

        return [
            'user_id' => User::where('role', 'user')->inRandomOrder()->first()?->id ?? User::factory(),
            'service_type' => $serviceTypeCode,
            'description' => fake()->paragraph(),
            'priority' => $priority,
            'status' => $status,
            'assigned_to' => in_array($status, ['approved', 'in_progress', 'resolved']) 
                ? User::where('role', 'admin')->inRandomOrder()->first()?->id 
                : null,
            'admin_remarks' => in_array($status, ['resolved', 'disapproved', 'cancelled']) ? fake()->sentence() : null,
        ];
    }
}
