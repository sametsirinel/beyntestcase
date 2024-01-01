<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class OrderSeeder extends Seeder
{
    use WithFaker;

    public function run(): void
    {
        User::get()->each(function ($user) {
            for ($i = 0; $i < 5; $i++) {
                $defination = Order::factory()->definition();
                $user->orders()->create(collect($defination)->except("user_id")->toArray());
            }
        });
    }
}
