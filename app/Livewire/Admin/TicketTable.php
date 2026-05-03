<?php

namespace App\Livewire\Admin;

use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TicketTable extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $status = '';

    #[Url(history: true)]
    public $priority = '';

    #[Url(history: true)]
    public $service_type = '';

    /** Query filter: ticket requester (user id), from Users page link */
    #[Url(history: true)]
    public $requester = '';

    public $remarks = '';

    public function clearRequesterFilter(): void
    {
        $this->requester = '';
        $this->resetPage();
    }

    public function updatedRequester(): void
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedPriority()
    {
        $this->resetPage();
    }

    public function updatedServiceType()
    {
        $this->resetPage();
    }

    public function updateStatus($ticketId, $newStatus)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $oldStatus = $ticket->status;

        // Validation for disapproval
        if ($newStatus === 'disapproved' && empty($this->remarks)) {
            $this->dispatch('notify', message: 'Admin remarks are required for disapproval.', type: 'error');

            return;
        }

        $ticket->update([
            'status' => $newStatus,
            'admin_remarks' => $newStatus === 'disapproved' ? $this->remarks : $ticket->admin_remarks,
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'remarks' => $this->remarks ?: 'Status changed to '.str_replace('_', ' ', $newStatus).' by Admin.',
        ]);

        $this->remarks = '';
        session()->flash('success', "Ticket {$ticket->ticket_no} status updated to ".strtoupper($newStatus));
    }

    public function assignTicket($ticketId, $adminId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['assigned_to' => $adminId]);

        session()->flash('success', "Ticket {$ticket->ticket_no} assigned successfully.");
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $filterUserId = null;
        if (filled($this->requester) && is_numeric($this->requester)) {
            $uid = (int) $this->requester;
            if ($uid > 0 && User::whereKey($uid)->exists()) {
                $filterUserId = $uid;
            }
        }

        $filterUser = $filterUserId ? User::find($filterUserId) : null;

        $query = Ticket::with(['user', 'assignedTo', 'serviceType'])
            ->when($filterUserId, fn ($q) => $q->where('user_id', $filterUserId))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('ticket_no', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('username', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->priority, fn ($q) => $q->where('priority', $this->priority))
            ->when($this->service_type, fn ($q) => $q->where('service_type', $this->service_type))
            ->latest();

        $pendingCount = Ticket::query()
            ->when($filterUserId, fn ($q) => $q->where('user_id', $filterUserId))
            ->where('status', 'pending')
            ->count();
        $activeCount = Ticket::query()
            ->when($filterUserId, fn ($q) => $q->where('user_id', $filterUserId))
            ->whereIn('status', ['approved', 'in_progress'])
            ->count();

        return view('livewire.admin.ticket-table', [
            'tickets' => $query->paginate(10),
            'admins' => User::where('role', 'admin')->get(),
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'filterUser' => $filterUser,
            'serviceTypeOptions' => ServiceType::ordered()->get(),
        ]);
    }
}
