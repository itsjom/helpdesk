<?php

namespace App\Livewire\User;

use App\Models\Ticket;
use App\Models\TicketLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class MyTickets extends Component
{
    use WithPagination;

    public ?Ticket $viewingTicket = null;

    public function viewTicket($id)
    {
        $this->viewingTicket = Ticket::with(['serviceType', 'assignedTo', 'recommendation', 'disposal', 'logs.user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $this->dispatch('open-modal', 'view-ticket-modal');
    }

    public function cancelTicket($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);

        if ($ticket->status !== 'pending') {
            session()->flash('error', 'Only pending tickets can be cancelled.');

            return;
        }

        $oldStatus = $ticket->status;
        $ticket->update(['status' => 'cancelled']);

        // Log the change
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => 'cancelled',
            'remarks' => 'Cancelled by user.',
            'created_at' => now(),
        ]);

        session()->flash('success', 'Ticket TKT-'.$ticket->ticket_no.' cancelled.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.user.my-tickets', [
            'tickets' => Ticket::with(['recommendation', 'disposal', 'assignedTo', 'serviceType'])
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    }
}
