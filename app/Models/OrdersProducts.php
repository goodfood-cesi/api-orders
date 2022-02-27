<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdersProducts extends Model {
    use HasFactory;

    protected $table = 'orders_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'amount_untaxed',
        'quantity'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
