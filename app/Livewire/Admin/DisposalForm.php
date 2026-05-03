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

        if (($this->ticket->serviceType?->kind ?? '') !== ServiceType::KIND_DISPOSAL) {
            return redirect()->route('admin.tickets');
        }

        if ($this->ticket->disposal) {
            $this->cause = $this->ticket->disposal->cause_of_disposal;
        }
    }

    public function save()
    {
        $this->validate([
            'cause' => 'required|min:10',
        ]);

        Disposal::updateOrCreate(
            ['ticket_id' => $this->ticket->id],
            [
                'cause_of_disposal' => $this->cause,
                'admin_name' => auth()->user()->username,
            ]
        );

        $oldStatus = $this->ticket->status;
        $this->ticket->update(['status' => 'resolved']);

        TicketLog::create([
            'ticket_id' => $this->ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => 'resolved',
            'remarks' => 'Disposal processed: '.Str::limit($this->cause, 50),
        ]);

        session()->flash('success', 'Disposal processed and ticket resolved!');

        return $this->redirect(route('admin.tickets'), navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.disposal-form');
    }
}
