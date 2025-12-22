<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        order::create([
            'total_amount' => 100,
            'discunt_amount' => 0,
        ]);
    }
}
