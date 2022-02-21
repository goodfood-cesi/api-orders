<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class OrderController extends  Controller {
    public function index(Request $request): JsonResponse {
        return $this->success(OrderResource::collection(Order::where('user_id', $request->input('token')->sub)->where('paid', true)->latest()->get()), 'Orders loaded');
    }

    public function show(Request $request, int $id): JsonResponse {
        try {
            return $this->success(new OrderResource(Order::where('user_id', $request->input('token')->sub)->where('id', $id)->where('paid', true)->firstOrFail()), 'Order fetched');
        } catch (Exception $e) {
            return $this->error('Order does not exist.');
        }
    }
}
