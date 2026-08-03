<?php

namespace App\Livewire\User;

use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TicketForm extends Component
{
    public $service_type = '';


    public $description = '';

    protected function rules()
    {
        return [
            'service_type' => [
                'required',
                Rule::exists('service_types', 'code')->where('is_active', true),
            ],
            'description' => 'required|min:10',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $ticket = Ticket::create([
                'user_id' => 1,
                'service_type' => $this->service_type,
                'description' => $this->description,
            ]);

            // Log the creation
            TicketLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => 1,
                'old_status' => null,
                'new_status' => 'pending',
                'remarks' => 'Ticket submitted by user.',
                'created_at' => now(),
            ]);

            session()->flash('success', 'Ticket submitted successfully!');

            return $this->redirect(route('user.tickets'), navigate: true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Ticket creation failed: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while submitting the ticket. Please try again later.');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.user.ticket-form', [
            'serviceTypes' => ServiceType::active()->ordered()->get(),
        ]);
    }
}
