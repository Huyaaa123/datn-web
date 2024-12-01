<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $newUsers = User::where('type', 'member')->orderBy('created_at', 'desc')->take(3)->get();

        $activeVouchers = Voucher::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalSales = Order::whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        $yesterdaySales = Order::whereDate('created_at', Carbon::yesterday())
            ->sum('total_amount');

        // Tính phần trăm thay đổi
        if ($yesterdaySales > 0) {
            $salesChangePercentage = (($totalSales - $yesterdaySales) / $yesterdaySales) * 100;
        } else {
            // Nếu hôm qua không có doanh thu, coi như tăng 100% hoặc giữ nguyên 0% tuỳ ý
            $salesChangePercentage = $totalSales > 0 ? 100 : 0;
        }

        $topCustomers = User::where('type', 'member')  // Kiểm tra loại người dùng là 'member'
        ->withCount(['orders' => function($query) {
            $query->where('order_status_id', 6);  // Chỉ tính các đơn hàng có order_status_id = 6 (hoàn thành)
        }])  // Lấy số lượng đơn hàng có trạng thái 'hoàn thành'
        ->withSum(['orders' => function($query) {
            $query->where('order_status_id', 6);  // Chỉ tính tổng giá trị đơn hàng có trạng thái 'hoàn thành'
        }], 'total_amount') // Đảm bảo chỉ định cột `total_amount`
        ->having('orders_count', '>', 0)  // Chỉ lấy những khách hàng có ít nhất 1 đơn hàng hoàn thành
        ->orderByDesc('orders_count')  // Sắp xếp theo số lượng đơn hàng giảm dần
        ->take(5)
        ->get();

        $newOrdersToday = Order::whereDate('created_at', Carbon::today())->count(); // Số đơn hàng hôm nay
        $newOrdersYesterday = Order::whereDate('created_at', Carbon::yesterday())->count(); // Số đơn hàng hôm qua

        // Tính phần trăm thay đổi
        if ($newOrdersYesterday > 0) {
            $orderChangePercentage = (($newOrdersToday - $newOrdersYesterday) / $newOrdersYesterday) * 100;
        } else {
            // Nếu hôm qua không có đơn hàng, coi như tăng 100% hoặc giữ nguyên 0% tuỳ ý
            $orderChangePercentage = $newOrdersToday > 0 ? 100 : 0;
        }

        $totalUsersToday = User::whereDate('created_at', Carbon::today())->count();

        // Lấy tổng số người dùng hôm qua
        $totalUsersYesterday = User::whereDate('created_at', Carbon::yesterday())->count();

        // Tính phần trăm thay đổi
        if ($totalUsersYesterday > 0) {
            $usersGrowthPercentage = (($totalUsersToday - $totalUsersYesterday) / $totalUsersYesterday) * 100;
        } else {
            // Nếu hôm qua không có người dùng mới, coi như tăng trưởng 100% nếu hôm nay có người dùng
            $usersGrowthPercentage = $totalUsersToday > 0 ? 100 : 0;
        }


        return view("admin.dashboard", compact("newUsers", "activeVouchers", "totalSales", "salesChangePercentage", "topCustomers","newOrdersToday", "orderChangePercentage",'totalUsersToday', 'usersGrowthPercentage'));
    }
}
