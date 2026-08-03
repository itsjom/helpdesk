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
use Livewire\WithFileUploads;

class TicketTable extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $status = '';

    #[Url(history: true)]
    public $priority = '';

    #[Url(history: true)]
    public $service_type = '';

    public $remarks = '';

    public $attachedFile;
    public $uploadingTicketId = null;

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

        $updateData = [
            'status' => $newStatus,
            'admin_remarks' => !empty($this->remarks) ? $this->remarks : $ticket->admin_remarks,
        ];

        // Auto-assign to the admin who approves the ticket
        if ($newStatus === 'approved') {
            $updateData['assigned_to'] = 1;
        }

        $ticket->update($updateData);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'changed_by' => 1,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'remarks' => $this->remarks ?: 'Status changed to '.str_replace('_', ' ', $newStatus).' by Admin.',
        ]);

        $this->remarks = '';
        session()->flash('success', "Ticket {$ticket->ticket_no} status updated to ".strtoupper($newStatus));
    }

    public function updateRemarks($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        
        $ticket->update([
            'admin_remarks' => $this->remarks
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'changed_by' => 1,
            'old_status' => $ticket->status,
            'new_status' => $ticket->status,
            'remarks' => "Admin added/updated remarks: " . ($this->remarks ?: 'Cleared remarks'),
        ]);

        $this->remarks = '';
        $this->dispatch('notify', message: 'Remarks updated successfully.', type: 'success');
    }

    public function updatePriority($ticketId, $newPriority)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $oldPriority = $ticket->priority;

        $dueDate = match (strtolower($newPriority)) {
            'high' => $ticket->created_at->addHours(4),
            'medium' => $ticket->created_at->addDay(),
            'low' => $ticket->created_at->addDays(3),
            default => null,
        };

        $ticket->update([
            'priority' => $newPriority ?: null,
            'due_date' => $dueDate,
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'changed_by' => 1,
            'old_status' => $ticket->status,
            'new_status' => $ticket->status,
            'remarks' => "Priority updated from {$oldPriority} to {$newPriority} by Admin.",
        ]);

        session()->flash('success', "Ticket {$ticket->ticket_no} priority updated to ".strtoupper($newPriority));
    }

    public function uploadFile($ticketId)
    {
        $this->validate([
            'attachedFile' => 'required|mimes:pdf,doc,docx,png|max:10240', // 10MB max
        ]);

        $ticket = Ticket::findOrFail($ticketId);
        $path = $this->attachedFile->store('tickets/files', 'public');

        if ($ticket->serviceType?->kind === 'recommendation') {
            $ticket->recommendation()->updateOrCreate(
                ['ticket_id' => $ticket->id],
                ['file_path' => $path, 'specs' => $ticket->description]
            );
        } elseif ($ticket->serviceType?->kind === 'disposal') {
            $ticket->disposal()->updateOrCreate(
                ['ticket_id' => $ticket->id],
                ['file_path' => $path, 'cause_of_disposal' => $ticket->description]
            );
        }

        $this->updateStatus($ticketId, 'in_progress');

        $this->attachedFile = null;
        $this->uploadingTicketId = null;
        $this->dispatch('notify', message: 'File uploaded and ticket started.', type: 'success');
    }


    #[Layout('layouts.app')]
    public function render()
    {
        $query = Ticket::with(['user', 'assignedTo', 'serviceType'])
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
            ->where('status', 'pending')
            ->count();
        $activeCount = Ticket::query()
            ->whereIn('status', ['approved', 'in_progress'])
            ->count();

        return view('livewire.admin.ticket-table', [
            'tickets' => $query->paginate(10),
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'serviceTypeOptions' => ServiceType::ordered()->get(),
        ]);
    }
}
