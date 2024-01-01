<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => $this->faker->name() . " " . $this->faker->ean13()
        ];
    }
}
