<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use WithFaker;

    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $i = $i == 0 ? '' : $i;
            User::create([
                "email" => "info$i@site.com",
                "password" => Hash::make("password"), // password
                "name" => "info$i"
            ]);
        }
    }
}
