<label>Barang
    <select name="product_id" required>
        <option value="">Pilih barang</option>
        @foreach ($products as $product)
            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                {{ $product->code }} - {{ $product->name }} (stok {{ $product->stock }} {{ $product->unit }})
            </option>
        @endforeach
    </select>
</label>
<label>Jumlah
    <input name="quantity" type="number" min="1" value="{{ old('quantity') }}" placeholder="0" required>
</label>
<label>Tanggal
    <input name="movement_date" type="date" value="{{ old('movement_date', now()->toDateString()) }}" required>
</label>
<label>Keterangan
    <input name="note" value="{{ old('note') }}" placeholder="{{ $type === 'masuk' ? 'Restock supplier' : 'Penjualan / distribusi rak' }}">
</label>
