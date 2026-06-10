<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('name');

        if ($request->filled('q')) {
            $query->where(function ($builder) use ($request) {
                $builder->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('code', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'tersedia' => $query->where('stock', '>', 0),
                'kosong' => $query->where('stock', '<=', 0),
                'rendah' => $query->where('stock', '>', 0)->where('stock', '<', 10),
                default => null,
            };
        }

        $products = $query->paginate(12)->withQueryString();
        $allProducts = Product::orderBy('name')->get();
        $movements = StockMovement::with(['product', 'user'])->latest('movement_date')->latest()->limit(10)->get();

        return view('inventory.index', compact('products', 'allProducts', 'movements'));
    }

    public function storeIn(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'movement_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $this->recordMovement($data, 'masuk');

        return back()->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function storeOut(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'movement_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        if ($product->stock < $data['quantity']) {
            return back()->withInput()->with('error', 'Stok tidak mencukupi untuk barang keluar.');
        }

        $this->recordMovement($data, 'keluar');

        return back()->with('success', 'Barang keluar berhasil dicatat.');
    }

    private function recordMovement(array $data, string $type): void
    {
        DB::transaction(function () use ($data, $type) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);
            $before = $product->stock;
            $after = $type === 'masuk'
                ? $before + $data['quantity']
                : max(0, $before - $data['quantity']);

            $product->update(['stock' => $after]);

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'quantity' => $data['quantity'],
                'stock_before' => $before,
                'stock_after' => $after,
                'movement_date' => $data['movement_date'],
                'note' => $data['note'] ?? null,
            ]);
        });
    }
}
