<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource{
    public function toArray($request): array {
        $result =  [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'paid' => $this->paid,
            'products' => $products = $this->rows,
            'shop_id' => $this->shop_id,
            'created_at' => date_format($this->created_at,"Y/m/d"),
            'updated_at' => date_format($this->updated_at,"Y/m/d"),
        ];
        $total = 0;

        foreach ($products as $product){
            $total += $product->product_quantity*$product->amount_untaxed;
        }

        $result['total_untaxed'] = round($total,2);
        return $result;
    }
}
