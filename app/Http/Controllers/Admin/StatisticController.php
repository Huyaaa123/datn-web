<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date')
        ? Carbon::parse($request->input('start_date'))
        : now()->startOfMonth();

    $endDate = $request->input('end_date')
        ? Carbon::parse($request->input('end_date'))
        : now()->endOfMonth();
        // 1. Tổng doanh thu từ các đơn hàng hoàn thành
        $totalRevenueData = Order::whereBetween('order_date', [$startDate, $endDate])
            ->where('order_status_id', 6) // Chỉ lấy đơn có trạng thái hoàn thành
            ->selectRaw('DATE(order_date) as date, SUM(total_amount) as total_revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Top 5 sản phẩm bán chạy nhất
        $topProducts = OrderDetail::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate])
                  ->where('order_status_id', 6);
        })
        ->selectRaw('product_id, SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->take(5)
        ->with('product:id,name') // Load thông tin sản phẩm
        ->get();


        // 4. Top 5 đơn hàng mới nhất
        $recentOrders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->orderByDesc('order_date')
            ->take(5)
            ->with('user:id,name,phone') // Load thông tin người dùng
            ->get();

        return view('admin.statistic', compact('totalRevenueData', 'topProducts', 'recentOrders', 'startDate', 'endDate'));
    }
}
