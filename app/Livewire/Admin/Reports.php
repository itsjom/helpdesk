<?php

namespace App\Livewire\Admin;

use App\Models\ServiceType;
use App\Models\Ticket;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reports extends Component
{
    public $filterType = 'month'; // day, week, month, year
    public $filterValue = '';

    public function mount()
    {
        $this->filterValue = date('Y-m');
    }

    public function updatedFilterType()
    {
        $this->filterValue = match($this->filterType) {
            'day' => date('Y-m-d'),
            'week' => date('Y-\WW'),
            'month' => date('Y-m'),
            'year' => date('Y'),
            default => date('Y-m'),
        };
    }

    public function getStats()
    {
        $query = Ticket::query();

        if (!empty($this->filterValue)) {
            if ($this->filterType === 'day') {
                try {
                    $query->whereDate('created_at', Carbon::parse($this->filterValue));
                } catch (\Exception $e) {}
            } elseif ($this->filterType === 'week') {
                $parts = explode('-W', $this->filterValue);
                if (count($parts) === 2) {
                    $start = Carbon::now()->setISODate($parts[0], $parts[1])->startOfWeek();
                    $end = Carbon::now()->setISODate($parts[0], $parts[1])->endOfWeek();
                    $query->whereBetween('created_at', [$start, $end]);
                }
            } elseif ($this->filterType === 'month') {
                try {
                    $date = Carbon::createFromFormat('Y-m', $this->filterValue);
                    $query->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year);
                } catch (\Exception $e) {}
            } elseif ($this->filterType === 'year') {
                $query->whereYear('created_at', $this->filterValue);
            }
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
        return redirect()->route('admin.reports.pdf', ['filterType' => $this->filterType, 'filterValue' => $this->filterValue]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.reports', [
            'stats' => $this->getStats(),
        ]);
    }
}
