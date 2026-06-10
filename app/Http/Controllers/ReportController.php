<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());
        $type = $request->input('type', 'all');

        $movements = StockMovement::with(['product.category', 'user'])
            ->whereBetween('movement_date', [$from, $to])
            ->when(in_array($type, ['masuk', 'keluar'], true), fn ($query) => $query->where('type', $type))
            ->latest('movement_date')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summaryQuery = StockMovement::whereBetween('movement_date', [$from, $to])
            ->when(in_array($type, ['masuk', 'keluar'], true), fn ($query) => $query->where('type', $type));

        $totalIn = (clone $summaryQuery)->where('type', 'masuk')->sum('quantity');
        $totalOut = (clone $summaryQuery)->where('type', 'keluar')->sum('quantity');
        $transactionCount = (clone $summaryQuery)->count();

        return view('reports.index', compact('movements', 'from', 'to', 'type', 'totalIn', 'totalOut', 'transactionCount'));
    }

    public function exportPDF(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());
        $type = $request->input('type', 'all');

        $movements = StockMovement::with(['product.category', 'user'])
            ->whereBetween('movement_date', [$from, $to])
            ->when(in_array($type, ['masuk', 'keluar'], true), fn ($query) => $query->where('type', $type))
            ->latest('movement_date')
            ->latest()
            ->get();

        $summaryQuery = StockMovement::whereBetween('movement_date', [$from, $to])
            ->when(in_array($type, ['masuk', 'keluar'], true), fn ($query) => $query->where('type', $type));

        $totalIn = (clone $summaryQuery)->where('type', 'masuk')->sum('quantity');
        $totalOut = (clone $summaryQuery)->where('type', 'keluar')->sum('quantity');
        $transactionCount = (clone $summaryQuery)->count();

        $pdf = PDF::loadView('reports.pdf', compact('movements', 'from', 'to', 'type', 'totalIn', 'totalOut', 'transactionCount'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10);

        return $pdf->download("laporan_{$from}_{$to}.pdf");
    }
}
