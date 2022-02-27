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
            'products' => $products = $this->products,
            'menus' => $menus = $this->menus,
            'restaurant_id' => $this->restaurant_id,
            'created_at' => date_format($this->created_at,"Y/m/d H:i"),
            'updated_at' => date_format($this->updated_at,"Y/m/d H:i"),
        ];
        $total = 0;

        foreach ($products as $product){
            $total += $product->quantity * $product->amount_untaxed;
        }
        foreach ($menus as $menu){
            $total += $menu->quantity * $menu->amount_untaxed;
        }

        $result['total_untaxed'] = round($total,2);
        return $result;
    }
}
