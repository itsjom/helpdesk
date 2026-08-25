<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * How long dashboard stats stay cached before being recomputed.
     * Keeps the page fast under repeated views/polling while staying
     * reasonably fresh for an internal admin dashboard.
     */
    private const CACHE_TTL_SECONDS = 30;

    #[Layout('layouts.app')]
    public function render()
    {
        $data = Cache::remember('dashboard.metrics', self::CACHE_TTL_SECONDS, function () {
            return [
                'stats' => $this->getStats(),
                'types' => $this->getTypeDistribution(),
                'recentActivity' => $this->getRecentActivity(),
                'departments' => $this->getDepartmentSummary(),
            ];
        });

        return view('livewire.admin.dashboard', $data);
    }

    /**
     * One aggregate query instead of four separate COUNT queries.
     */
    private function getStats(): array
    {
        $row = Ticket::selectRaw(
            "COUNT(*) as total,
             SUM(CASE WHEN status IN ('pending', 'approved', 'in_progress') THEN 1 ELSE 0 END) as open,
             SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
             SUM(CASE WHEN status = 'disapproved' THEN 1 ELSE 0 END) as disapproved"
        )->first();

        return [
            'total' => (int) $row->total,
            'open' => (int) $row->open,
            'resolved' => (int) $row->resolved,
            'disapproved' => (int) $row->disapproved,
        ];
    }

    private function getTypeDistribution(): array
    {
        $typeCounts = Ticket::selectRaw('service_type, count(*) as count')
            ->groupBy('service_type')
            ->pluck('count', 'service_type');

        $total = $typeCounts->sum() ?: 1;

        return ServiceType::ordered()->get()->map(function ($serviceType) use ($typeCounts, $total) {
            $count = $typeCounts[$serviceType->code] ?? 0;

            return [
                'label' => $serviceType->name,
                'count' => $count,
                'percent' => round(($count / $total) * 100),
            ];
        })->all();
    }

    private function getRecentActivity()
    {
        return TicketLog::with(['ticket', 'user'])
            ->latest()
            ->take(6)
            ->get();
    }

    /**
     * For the Network Chart - departments with summary stats and ONLY users with active tickets.
     */
    private function getDepartmentSummary()
    {
        return Department::withCount(['users as total_users_count' => function ($query) {
            $query->where('role', 'user');
        }])->with(['users' => function ($uq) {
            $uq->where('role', 'user')
               ->whereHas('tickets', function ($q) {
                   $q->whereNotIn('status', ['resolved', 'disapproved', 'cancelled']);
               })
               ->withCount(['tickets as active_tickets_count' => function ($query) {
                   $query->whereNotIn('status', ['resolved', 'disapproved', 'cancelled']);
               }]);
        }])->get();
    }
}
