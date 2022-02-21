<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRow extends Model {
    use HasFactory;

    protected $table = 'orders_rows';

    protected $fillable = [
        'order_id',
        'product_id',
        'amount_untaxed',
        'product_quantity'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
