<?php

use App\Models\Order;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @covers App\Http\Middleware\Authenticate
     * @covers App\Http\Controllers\OrderController
     * @covers App\Http\Resources\OrderResource
     * @covers App\Models\Order
     * @covers App\Models\User
     * @covers App\Traits\ApiResponser
     * @return void
     */
    public function test_can_get_all_orders(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(50)->create([
            'user_id' => $user->id
        ]);

        $this->get(route('orders.index'), ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'status',
                    'paid',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity'
                        ]
                    ],
                    'menus' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity'
                        ]
                    ],
                    'restaurant_id',
                    'created_at',
                    'updated_at',
                ]
            ],
            'meta' => [
                'success',
                'message'
            ]
        ]);
    }

    /**
     * @covers App\Http\Middleware\Authenticate
     * @covers App\Http\Controllers\OrderController
     * @covers App\Http\Resources\OrderResource
     * @covers App\Models\Order
     * @covers App\Models\User
     * @covers App\Traits\ApiResponser
     * @return void
     */
    public function test_can_get_one_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->get(route('orders.show', ['id' => $order->id]), ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'user_id',
                'status',
                'paid',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'quantity'
                    ]
                ],
                'menus' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'quantity'
                    ]
                ],
                'restaurant_id',
                'created_at',
                'updated_at',
            ],
            'meta' => [
                'success',
                'message'
            ]
        ]);
    }

    /**
     * @covers App\Models\Order
     * @covers App\Models\User
     * @covers App\Http\Controllers\OrderController
     * @covers App\Http\Middleware\Authenticate
     * @covers App\Traits\ApiResponser
     * @return void
     */
    public function test_cannot_get_one_order(): void {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $userTest = User::factory()->create();

        $this->get(route('orders.show', ['id' => $order->id]), ['Authorization' => 'Bearer ' . JWTAuth::fromUser($userTest)]);
        $this->seeStatusCode(404);
        $this->seeJson([
            'data' => 404,
            'meta' => [
                'success' => false,
                'message' => 'Order does not exist.',
            ]
        ]);
    }
}
