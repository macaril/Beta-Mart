<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'unit',
        'stock',
        'purchase_price',
        'selling_price',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'purchase_price' => 'integer',
            'selling_price' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getAvailabilityAttribute(): string
    {
        return $this->stock > 0 ? 'Tersedia' : 'Tidak Tersedia';
    }
}
