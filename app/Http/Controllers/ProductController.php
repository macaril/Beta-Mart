<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($builder) use ($request) {
                    $builder->where('name', 'like', '%'.$request->q.'%')
                        ->orWhere('code', 'like', '%'.$request->q.'%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.products.index', compact('products'));
    }

    public function create()
    {
        return view('master.products.form', [
            'product' => new Product(['unit' => 'pcs', 'stock' => 0]),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Product::create($this->validated($request));

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'stockMovements.user']);

        return view('master.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('master.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request, $product->id));

        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->stockMovements()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $product->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:30', 'unique:products,code'.($ignoreId ? ','.$ignoreId : '')],
            'name' => ['required', 'string', 'max:160'],
            'unit' => ['required', 'string', 'max:30'],
            'stock' => ['required', 'integer', 'min:0'],
            'purchase_price' => ['required', 'integer', 'min:0'],
            'selling_price' => ['required', 'integer', 'min:0'],
        ]);
    }
}
