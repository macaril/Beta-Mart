# Beta Mart Warehouse

Aplikasi pergudangan minimarket berbasis Laravel dengan database SQLite.

## Fitur

- Login admin dengan pesan error saat kredensial salah.
- Dashboard: total barang, total stok masuk, total stok keluar, stok terendah kurang dari 10, dan stok tertinggi.
- Persediaan Barang: catat barang masuk, catat barang keluar, stok terakhir, serta status tersedia/tidak tersedia.
- Master Data: kategori barang, daftar barang, dan manajemen pengguna dengan fitur tambah, detail, edit, hapus.
- Laporan: histori keluar masuk barang dengan filter tanggal dan jenis transaksi.

## Akun Demo

- Email: `admin@betamart.local`
- Password: `admin123`

## Menjalankan

Pastikan PHP 8.2+ dan Composer sudah terpasang.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Setelah server aktif, buka `http://127.0.0.1:8000`.

Default database memakai SQLite di `database/database.sqlite`. Jika ingin MySQL, ubah konfigurasi `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
