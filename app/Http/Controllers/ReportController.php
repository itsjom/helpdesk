<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $period = $request->query('period', 'month');
        $query = Ticket::query();

        $title = 'Report Overview';
        if ($period === 'day') {
            $query->whereDate('created_at', Carbon::today());
            $title = 'Daily Report Overview ('.Carbon::today()->format('Y-m-d').')';
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $title = 'Weekly Report Overview ('.Carbon::now()->startOfWeek()->format('M d').' - '.Carbon::now()->endOfWeek()->format('M d, Y').')';
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
            $title = 'Monthly Report Overview ('.Carbon::now()->format('F Y').')';
        } elseif ($period === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
            $title = 'Yearly Report Overview ('.Carbon::now()->year.')';
        }

        $tickets = $query->with('serviceType')->get();

        $byCode = $tickets->groupBy('service_type')->map->count();
        $byService = [];
        foreach ($byCode as $code => $count) {
            $byService[] = [
                'label' => ServiceType::labelForCode((string) $code),
                'count' => $count,
            ];
        }

        $stats = [
            'total' => $tickets->count(),
            'pending' => $tickets->where('status', 'pending')->count(),
            'approved' => $tickets->where('status', 'approved')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
            'disapproved' => $tickets->where('status', 'disapproved')->count(),
            'cancelled' => $tickets->where('status', 'cancelled')->count(),
            'by_service' => $byService,
        ];

        $pdf = Pdf::loadView('reports.pdf', compact('stats', 'title', 'tickets'));

        return $pdf->download('it-helpdesk-report-'.$period.'.pdf');
    }
}
