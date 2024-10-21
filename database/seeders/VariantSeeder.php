<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Variant;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];
        $sizes = [];

        for ($i = 20; $i <= 50; $i++) {
            $sizes[] = $i . 'mm';
        }

            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    Variant::create([
                        'color' => $color,
                        'size' => $size,
                    ]);
                }
            }
    }

}

