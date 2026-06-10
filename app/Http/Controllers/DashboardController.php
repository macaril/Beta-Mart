<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Product::count();
        $totalIn = StockMovement::where('type', 'masuk')->sum('quantity');
        $totalOut = StockMovement::where('type', 'keluar')->sum('quantity');
        $lowStock = Product::with('category')->where('stock', '<', 10)->orderBy('stock')->get();
        $highestStock = Product::orderByDesc('stock')->first();
        $recentMovements = StockMovement::with(['product', 'user'])->latest('movement_date')->latest()->limit(8)->get();
        $stockByCategory = Category::withSum('products as total_stock', 'stock')->orderByDesc('total_stock')->get();

        $latestMovementDate = StockMovement::max('movement_date');
        $endDate = $latestMovementDate
            ? Carbon::parse($latestMovementDate)->startOfDay()
            : Carbon::now()->startOfDay();
        $startDate = $endDate->copy()->subDays(6);

        $dailyMovements = StockMovement::whereBetween('movement_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('movement_date as movement_day, type, SUM(quantity) as total_quantity')
            ->groupBy('movement_date', 'type')
            ->orderBy('movement_date')
            ->get();

        $dailyMap = $dailyMovements->groupBy('movement_day')->map(function ($items) {
            return $items->keyBy('type');
        });

        $chartLabels = [];
        $chartIn = [];
        $chartOut = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayKey = $date->toDateString();
            $dayData = $dailyMap->get($dayKey, collect());

            $chartLabels[] = $date->translatedFormat('d M');
            $chartIn[] = (int) ($dayData->get('masuk')->total_quantity ?? 0);
            $chartOut[] = (int) ($dayData->get('keluar')->total_quantity ?? 0);
        }

        $chartMax = max(max($chartIn), max($chartOut), 1);
        $chartRangeLabel = $startDate->translatedFormat('d M').' - '.$endDate->translatedFormat('d M Y');

        return view('dashboard', compact(
            'totalItems',
            'totalIn',
            'totalOut',
            'lowStock',
            'highestStock',
            'recentMovements',
            'stockByCategory',
            'chartLabels',
            'chartIn',
            'chartOut',
            'chartMax',
            'chartRangeLabel'
        ));
    }
}
