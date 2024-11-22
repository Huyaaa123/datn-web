<?php
namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Chưa xác nhận',
            'Đã xác nhận',
            'Đang giao hàng',
            'Đã giao hàng',
            'Đã nhận hàng',
            'Hoàn thành',
            'Hủy đơn',
            'Chờ xác nhận hủy',
            'Đã hủy',
        ];

        foreach ($statuses as $status) {
            OrderStatus::create(['name' => $status]);
        }
    }
}

