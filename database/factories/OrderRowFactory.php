<?php

namespace Database\Factories;

use App\Models\OrderRow;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderRowFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderRow::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount_untaxed' => $this->faker->randomFloat(2,10,50),
            'product_quantity' => $this->faker->randomFloat(0,1,10)
        ];
    }
}
