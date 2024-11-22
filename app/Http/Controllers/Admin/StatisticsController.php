<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // Lấy số lượng người dùng
        $totalUsers = User::count();

        // Lấy số lượng đơn hàng
        $totalOrders = Order::count();

        // Tính tổng doanh thu
        $totalRevenue = Order::where('order_status_id', 6)->sum('total_amount');

        $statusCount  = Order::selectRaw('order_status_id, count(*) as count')
        ->groupBy('order_status_id')
        ->pluck('count', 'order_status_id');

        $revenueByDay = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_revenue')
        ->where('order_status_id', 6) // Chỉ lấy đơn hàng đã hoàn thành
        ->groupBy('date')
        ->orderBy('date', 'asc') // Sắp xếp theo ngày tăng dần
        ->get();

        return view('admin.statistic', compact('totalUsers', 'totalOrders','statusCount', 'totalRevenue','revenueByDay'));
    }
}
