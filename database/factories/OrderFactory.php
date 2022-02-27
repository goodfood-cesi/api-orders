<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'status' => $this->faker->randomFloat(0,1,5),
            'paid' => $this->faker->boolean(),
            'paypal_id' => $this->faker->text(255),
            'shop_id' => $this->faker->randomNumber(),
        ];
    }
}
