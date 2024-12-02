<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::create([
            'code' => 'SALE10',
            'discount_type' => 'percent',
            'discount_amount' => null,
            'discount_percent' => 10,
            'min_order_value' => 100000,
            'max_discount_amount' => 150000,
            'usage_limit' => 100,
            'used' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
            'status' => true,
        ]);

    }
}
