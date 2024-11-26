<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            ['name' => 'Đỏ', 'code' => '#FF0000'],
            ['name' => 'Xanh da trời', 'code' => '#0000FF'],
            ['name' => 'Xanh lá cây', 'code' => '#00FF00'],
            ['name' => 'Vàng', 'code' => '#FFFF00'],
            ['name' => 'Đen', 'code' => '#000000'],
            ['name' => 'Xám', 'code' => '#808080'],
        ]);
    }
}
