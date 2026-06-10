<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Beta Mart',
            'email' => 'admin@betamart.local',
            'password' => Hash::make('admin123'),
            'phone' => '0812-1000-2026',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::insert([
            [
                'name' => 'Rina Gudang',
                'email' => 'rina@betamart.local',
                'password' => Hash::make('staff123'),
                'phone' => '0813-4400-1111',
                'role' => 'gudang',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $categories = collect([
            ['name' => 'Makanan Ringan', 'description' => 'Snack, biskuit, wafer, dan camilan harian.'],
            ['name' => 'Minuman', 'description' => 'Air mineral, teh, kopi, susu, dan minuman kemasan.'],
            ['name' => 'Sembako', 'description' => 'Beras, gula, minyak, telur, dan kebutuhan dapur.'],
            ['name' => 'Perawatan Diri', 'description' => 'Sabun, sampo, pasta gigi, dan produk kebersihan pribadi.'],
            ['name' => 'Rumah Tangga', 'description' => 'Deterjen, tisu, pembersih lantai, dan kebutuhan rumah.'],
            ['name' => 'Obat Ringan', 'description' => 'Vitamin, minyak kayu putih, plester, dan obat bebas.'],
        ])->mapWithKeys(fn ($row) => [$row['name'] => Category::create($row)]);

        $products = [
            ['BRG-001', 'Indomie Goreng 85g', 'Makanan Ringan', 'pcs', 126, 2800, 3500],
            ['BRG-002', 'Qtela Singkong Balado 60g', 'Makanan Ringan', 'pcs', 8, 6200, 7500],
            ['BRG-003', 'Roma Kelapa 300g', 'Makanan Ringan', 'pcs', 34, 8800, 10500],
            ['BRG-004', 'Aqua Botol 600ml', 'Minuman', 'botol', 210, 2800, 4000],
            ['BRG-005', 'Teh Pucuk Harum 350ml', 'Minuman', 'botol', 76, 3300, 4500],
            ['BRG-006', 'Ultra Milk Cokelat 250ml', 'Minuman', 'kotak', 6, 5600, 7000],
            ['BRG-007', 'Beras Ramos 5kg', 'Sembako', 'karung', 18, 64000, 72000],
            ['BRG-008', 'Gula Pasir Gulaku 1kg', 'Sembako', 'pcs', 3, 14500, 17000],
            ['BRG-009', 'Minyak Goreng Bimoli 2L', 'Sembako', 'pouch', 22, 33000, 38000],
            ['BRG-010', 'Lifebuoy Sabun Cair 450ml', 'Perawatan Diri', 'botol', 15, 24500, 29500],
            ['BRG-011', 'Pepsodent 190g', 'Perawatan Diri', 'pcs', 9, 12800, 15500],
            ['BRG-012', 'Pantene Shampoo 170ml', 'Perawatan Diri', 'botol', 0, 24500, 30000],
            ['BRG-013', 'Rinso Deterjen Bubuk 770g', 'Rumah Tangga', 'pcs', 41, 18800, 22500],
            ['BRG-014', 'Tisu Paseo 250 Sheet', 'Rumah Tangga', 'pack', 12, 14200, 18000],
            ['BRG-015', 'Wipol Pembersih Lantai 780ml', 'Rumah Tangga', 'botol', 7, 16500, 20500],
            ['BRG-016', 'Tolak Angin Cair Sachet', 'Obat Ringan', 'sachet', 68, 3300, 4500],
            ['BRG-017', 'Minyak Kayu Putih Cap Lang 60ml', 'Obat Ringan', 'botol', 5, 17800, 23000],
            ['BRG-018', 'Hansaplast Plester Isi 10', 'Obat Ringan', 'box', 24, 7600, 9500],
        ];

        foreach ($products as [$code, $name, $category, $unit, $stock, $buy, $sell]) {
            Product::create([
                'code' => $code,
                'name' => $name,
                'category_id' => $categories[$category]->id,
                'unit' => $unit,
                'stock' => $stock,
                'purchase_price' => $buy,
                'selling_price' => $sell,
            ]);
        }

        $movements = [
            ['2026-06-01', 'masuk', 'BRG-001', 80, 46, 126, 'Restock supplier mi instan'],
            ['2026-06-01', 'keluar', 'BRG-004', 32, 242, 210, 'Distribusi ke rak minuman'],
            ['2026-06-02', 'masuk', 'BRG-009', 18, 4, 22, 'Restock minyak goreng'],
            ['2026-06-02', 'keluar', 'BRG-013', 15, 56, 41, 'Penjualan harian'],
            ['2026-06-03', 'keluar', 'BRG-006', 12, 18, 6, 'Penjualan susu kemasan'],
            ['2026-06-03', 'masuk', 'BRG-016', 40, 28, 68, 'Restock obat ringan'],
            ['2026-06-04', 'keluar', 'BRG-008', 11, 14, 3, 'Penjualan sembako'],
            ['2026-06-04', 'keluar', 'BRG-012', 5, 5, 0, 'Stok habis di rak'],
            ['2026-06-05', 'masuk', 'BRG-005', 36, 40, 76, 'Restock minuman teh'],
            ['2026-06-05', 'keluar', 'BRG-002', 14, 22, 8, 'Penjualan camilan'],
        ];

        foreach ($movements as [$date, $type, $code, $qty, $before, $after, $note]) {
            StockMovement::create([
                'product_id' => Product::where('code', $code)->value('id'),
                'user_id' => $admin->id,
                'type' => $type,
                'quantity' => $qty,
                'stock_before' => $before,
                'stock_after' => $after,
                'movement_date' => $date,
                'note' => $note,
            ]);
        }
    }
}
