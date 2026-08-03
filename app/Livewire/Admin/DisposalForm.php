<?php

namespace App\Livewire\Admin;

use App\Models\Disposal;
use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DisposalForm extends Component
{
    public $ticket;

    public $cause = '';

    public function mount($ticketId)
    {
        $this->ticket = Ticket::with(['user', 'serviceType'])->findOrFail($ticketId);



        if ($this->ticket->disposal) {
            $this->cause = $this->ticket->disposal->cause_of_disposal;
        }
    }

    public function save()
    {
        $this->validate([
            'cause' => 'required|min:10',
        ]);

        try {
            Disposal::updateOrCreate(
                ['ticket_id' => $this->ticket->id],
                [
                    'cause_of_disposal' => $this->cause,
                    'admin_name' => 'System Admin',
                ]
            );

            $oldStatus = $this->ticket->status;
            $this->ticket->update(['status' => 'resolved']);

            TicketLog::create([
                'ticket_id' => $this->ticket->id,
                'changed_by' => 1,
                'old_status' => $oldStatus,
                'new_status' => 'resolved',
                'remarks' => 'Disposal processed: '.Str::limit($this->cause, 50),
            ]);

            session()->flash('success', 'Disposal processed and ticket resolved!');

            return $this->redirect(route('admin.tickets'), navigate: true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Disposal processing failed: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while processing the disposal: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.disposal-form');
    }
}
