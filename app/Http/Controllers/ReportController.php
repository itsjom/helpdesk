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
        $filterType = $request->query('filterType', 'month');
        $filterValue = $request->query('filterValue', date('Y-m'));
        $query = Ticket::query();

        $title = 'Report Overview';
        if (!empty($filterValue)) {
            if ($filterType === 'day') {
                try {
                    $query->whereDate('created_at', Carbon::parse($filterValue));
                    $title = 'Daily Report Overview ('.Carbon::parse($filterValue)->format('M d, Y').')';
                } catch (\Exception $e) {}
            } elseif ($filterType === 'week') {
                $parts = explode('-W', $filterValue);
                if (count($parts) === 2) {
                    $start = Carbon::now()->setISODate($parts[0], $parts[1])->startOfWeek();
                    $end = Carbon::now()->setISODate($parts[0], $parts[1])->endOfWeek();
                    $query->whereBetween('created_at', [$start, $end]);
                    $title = 'Weekly Report Overview ('.$start->format('M d').' - '.$end->format('M d, Y').')';
                }
            } elseif ($filterType === 'month') {
                try {
                    $date = Carbon::createFromFormat('Y-m', $filterValue);
                    $query->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year);
                    $title = 'Monthly Report Overview ('.$date->format('F Y').')';
                } catch (\Exception $e) {}
            } elseif ($filterType === 'year') {
                $query->whereYear('created_at', $filterValue);
                $title = 'Yearly Report Overview ('.$filterValue.')';
            }
        }

        $tickets = $query->with('serviceType')->get();
        $resolvedTickets = $tickets->where('status', 'resolved');

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

        $pdf = Pdf::loadView('reports.pdf', compact('stats', 'title', 'resolvedTickets'))->setPaper('a4', 'landscape');

        return $pdf->download('it-helpdesk-report-'.$filterType.'-'.$filterValue.'.pdf');
    }
}
