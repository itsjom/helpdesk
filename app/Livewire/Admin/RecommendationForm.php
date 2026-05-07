<?php

namespace App\Livewire\Admin;

use App\Models\Recommendation;
use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\TicketLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RecommendationForm extends Component
{
    public $ticket;

    public $specs = '';

    public function mount($ticketId)
    {
        $this->ticket = Ticket::with(['user', 'serviceType'])->findOrFail($ticketId);



        if ($this->ticket->recommendation) {
            $this->specs = $this->ticket->recommendation->specs;
        }
    }

    public function generate()
    {
        $this->validate([
            'specs' => 'required|min:10',
        ]);

        // 1. Save Recommendation
        $recommendation = Recommendation::updateOrCreate(
            ['ticket_id' => $this->ticket->id],
            ['specs' => $this->specs]
        );

        // 2. Generate PDF
        $pdf = Pdf::loadView('pdf.recommendation', [
            'ticket' => $this->ticket,
            'specs' => $this->specs,
            'date' => now()->format('M d, Y'),
        ]);

        $fileName = 'REC-'.$this->ticket->ticket_no.'.pdf';
        $filePath = 'recommendations/'.$fileName;

        // Ensure directory exists
        if (! Storage::disk('public')->exists('recommendations')) {
            Storage::disk('public')->makeDirectory('recommendations');
        }

        Storage::disk('public')->put($filePath, $pdf->output());

        // 3. Update Recommendation with path
        $recommendation->update(['file_path' => $filePath]);

        // 4. Resolve Ticket
        $oldStatus = $this->ticket->status;
        $this->ticket->update(['status' => 'resolved']);

        // 5. Log activity
        TicketLog::create([
            'ticket_id' => $this->ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => 'resolved',
            'remarks' => 'Hardware recommendation generated and PDF saved.',
        ]);

        session()->flash('success', 'Recommendation generated and ticket resolved!');

        return $this->redirect(route('admin.tickets'), navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.recommendation-form');
    }
}
