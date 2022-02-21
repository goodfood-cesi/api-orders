<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'status',
        'paid',
        'paypal_id',
        'shop_id'
    ];

    public function rows(): HasMany {
        return $this->hasMany(OrderRow::class);
    }
}