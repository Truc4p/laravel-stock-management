<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'user_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'reference_number',
        'reason',
        'notes',
        'movement_date'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'movement_date' => 'datetime'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInbound($query)
    {
        return $query->whereIn('type', ['in', 'adjustment'])->where('quantity', '>', 0);
    }

    public function scopeOutbound($query)
    {
        return $query->whereIn('type', ['out', 'adjustment'])->where('quantity', '<', 0);
    }
}
