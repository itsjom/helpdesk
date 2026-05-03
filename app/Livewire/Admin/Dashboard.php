<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::whereIn('status', ['pending', 'approved', 'in_progress'])->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'disapproved' => Ticket::where('status', 'disapproved')->count(),
        ];

        $typeDistribution = Ticket::select('service_type', DB::raw('count(*) as count'))
            ->groupBy('service_type')
            ->get()
            ->pluck('count', 'service_type')
            ->toArray();

        $formattedTypes = [];
        $totalForPercent = $stats['total'] ?: 1;
        foreach (ServiceType::ordered()->get() as $serviceTypeRow) {
            $count = $typeDistribution[$serviceTypeRow->code] ?? 0;
            $formattedTypes[] = [
                'label' => $serviceTypeRow->name,
                'count' => $count,
                'percent' => round(($count / $totalForPercent) * 100),
            ];
        }

        // Recent activity from logs
        $recentActivity = TicketLog::with(['ticket', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // For the Network Chart
        $departments = Department::with(['users' => function ($uq) {
            $uq->where('role', 'user')->withCount(['tickets as active_tickets_count' => function ($query) {
                $query->whereNotIn('status', ['resolved', 'disapproved', 'cancelled']);
            }]);
        }])->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'types' => $formattedTypes,
            'recentActivity' => $recentActivity,
            'departments' => $departments,
        ]);
    }
}
