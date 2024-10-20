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
        $products = Product::all();

        $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];

        $sizes = [];
        for ($i = 20; $i <= 50; $i += 1) {
            $sizes[] = $i . 'mm';
        }

        foreach ($products as $product) {
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    Variant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $size,
                    ]);
                }
            }
        }
    }
}

