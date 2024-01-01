<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            "user_id" => User::factory()->create()->id,
            "product_id" => Product::factory()->create()->id,
            "order_code" => Str::random(20),
            "address" => $this->faker->address(),
            "quantity" => rand(1, 10),
            "shipping_at" => rand(1, 10) % 2 == 0 ? now() : null,
        ];
    }
}
