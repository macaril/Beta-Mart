@extends('layouts.app')

@section('title', 'Dashboard - Beta Mart')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan persediaan barang Beta Mart.')

@section('content')
<section class="card chart-panel">
    <div class="card-head">
        <div>
            <h2>Grafik Barang Masuk & Keluar per Hari</h2>
            <p class="muted">Sumbu X menampilkan tanggal, sedangkan sumbu Y menunjukkan jumlah barang. Garis hijau untuk masuk dan garis jingga untuk keluar.</p>
        </div>
        <span class="badge slate">{{ $chartRangeLabel }}</span>
    </div>

    <div class="chart-summary">
        <div>
            <span>Barang Masuk</span>
            <strong>{{ number_format($totalIn, 0, ',', '.') }}</strong>
        </div>
        <div>
            <span>Barang Keluar</span>
            <strong>{{ number_format($totalOut, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="line-chart-card">
        <div class="line-chart-legend">
            <span><i class="dot dot-green"></i> Masuk</span>
            <span><i class="dot dot-amber"></i> Keluar</span>
        </div>
        <div id="chart-data" data-labels='{{ json_encode($chartLabels) }}' data-masuk='{{ json_encode($chartIn) }}' data-keluar='{{ json_encode($chartOut) }}' hidden></div>
        <div class="chart-canvas-wrap">
            <canvas id="dailyMovementChart" aria-label="Grafik barang masuk dan keluar per hari"></canvas>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('dailyMovementChart');

        if (!ctx) return;

        const chartData = document.getElementById('chart-data');
        const labels = JSON.parse(chartData?.dataset.labels || '[]');
        const masuk = JSON.parse(chartData?.dataset.masuk || '[]');
        const keluar = JSON.parse(chartData?.dataset.keluar || '[]');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: masuk,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.12)',
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        borderWidth: 3
                    },
                    {
                        label: 'Barang Keluar',
                        data: keluar,
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217, 119, 6, 0.12)',
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.formattedValue;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal',
                            color: '#475569'
                        },
                        ticks: {
                            color: '#475569'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.18)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Barang',
                            color: '#475569'
                        },
                        ticks: {
                            color: '#475569'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.18)'
                        }
                    }
                }
            }
        });
    });
</script>

<section class="stats">
    <x-stat label="Total Barang" :value="$totalItems" tone="blue" />
    <x-stat label="Total Stok Masuk" :value="number_format($totalIn, 0, ',', '.')" tone="green" />
    <x-stat label="Total Stok Keluar" :value="number_format($totalOut, 0, ',', '.')" tone="amber" />
    <x-stat label="Stok Terendah < 10" :value="$lowStock->count()" tone="red" />
    <x-stat label="Stok Tertinggi" :value="$highestStock ? number_format($highestStock->stock, 0, ',', '.') : 0" :meta="$highestStock?->name" tone="slate" />
</section>

<section class="grid two">
    <div class="card">
        <div class="card-head">
            <h2>Aktivitas Terbaru</h2>
            <a href="{{ route('inventory.index') }}">Lihat persediaan</a>
        </div>
        <table>
            <tbody>
            @forelse ($recentMovements as $movement)
                <tr>
                    <td><span class="badge {{ $movement->type }}">{{ $movement->type }}</span></td>
                    <td>
                        <strong>{{ $movement->product->name }}</strong>
                        <small>{{ $movement->movement_date->format('d M Y') }} oleh {{ $movement->user?->name ?? '-' }}</small>
                    </td>
                    <td class="right">{{ $movement->type === 'masuk' ? '+' : '-' }}{{ $movement->quantity }}</td>
                </tr>
            @empty
                <tr><td>Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2>Stok Per Kategori</h2>
        <div class="bar-list">
            @foreach ($stockByCategory as $category)
                <div>
                    <span>{{ $category->name }}</span>
                    <strong>{{ number_format($category->total_stock ?? 0, 0, ',', '.') }}</strong>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="card">
    <h2>Barang Perlu Restock</h2>
    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th class="right">Stok</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($lowStock as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td class="right">{{ $product->stock }} {{ $product->unit }}</td>
                <td><span class="badge {{ $product->stock > 0 ? 'rendah' : 'kosong' }}">{{ $product->stock > 0 ? 'Stok Rendah' : 'Tidak Tersedia' }}</span></td>
            </tr>
        @empty
            <tr><td colspan="5">Semua stok aman.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
