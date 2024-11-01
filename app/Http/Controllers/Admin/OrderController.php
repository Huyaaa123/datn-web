<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orderStatus = OrderStatus::all();
        $orders = Order::with('user')->get(); // Lấy tất cả đơn hàng
        return view('admin.orders', compact('orders','orderStatus'));
    }

    public function create()
    {

    }

    public function store(StoreOrderRequest $request)
    {

    }

    public function show(Order $order)
    {
    }

    public function edit(Order $order)
    {
        $orderStatus = OrderStatus::all();
        return view('admin.crud.orders-edit', compact('order', 'orderStatus')); // Hiển thị chi tiết đơn hàng
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        DB::transaction(function ()use ($order, $request) {
            $dataOrder = [
                'order_status_id' => $request->order_status_id,
                'cancel' => $request->cancel,
                'notes' => auth()->user()->name,
            ];

            $order->update($dataOrder);
        });
        return redirect()->route('admin.orders.index')->with('success', 'Product update successfully!');
    }

    public function destroy(Order $order)
    {

    }
}
