<?php

namespace App\Livewire\Admin;

use App\Models\ServiceType;
use App\Models\Ticket;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reports extends Component
{
    public $period = 'month'; // day, week, month, year

    public function getStats()
    {
        $query = Ticket::query();

        if ($this->period === 'day') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->period === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($this->period === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $tickets = $query->get();

        $byCode = $tickets->groupBy('service_type')->map->count();
        $byService = [];
        foreach ($byCode as $code => $count) {
            $byService[] = [
                'label' => ServiceType::labelForCode((string) $code),
                'count' => $count,
            ];
        }

        return [
            'total' => $tickets->count(),
            'pending' => $tickets->where('status', 'pending')->count(),
            'approved' => $tickets->where('status', 'approved')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
            'disapproved' => $tickets->where('status', 'disapproved')->count(),
            'cancelled' => $tickets->where('status', 'cancelled')->count(),
            'by_service' => $byService,
        ];
    }

    public function downloadPdf()
    {
        return redirect()->route('admin.reports.pdf', ['period' => $this->period]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.reports', [
            'stats' => $this->getStats(),
        ]);
    }
}
