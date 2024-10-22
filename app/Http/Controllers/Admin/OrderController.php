<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get(); // Lấy tất cả đơn hàng
        return view('admin.orders', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create'); // Hiển thị form tạo đơn hàng
    }

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated()); // Tạo đơn hàng
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được tạo thành công.');
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order')); // Hiển thị chi tiết đơn hàng
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order')); // Hiển thị form chỉnh sửa
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated()); // Cập nhật đơn hàng
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công.');
    }
}
