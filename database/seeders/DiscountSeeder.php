<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1, $percent = 0; $i <= 10; $i++, $percent += 5) {
            DB::table('discounts')->insert([
                'discount_percent' => $percent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
