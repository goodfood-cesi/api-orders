<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdersMenus extends Model {
    use HasFactory;

    protected $table = 'orders_menus';

    protected $fillable = [
        'order_id',
        'menu_id',
        'amount_untaxed',
        'quantity'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
