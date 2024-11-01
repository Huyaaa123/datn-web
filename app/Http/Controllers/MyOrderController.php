<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();
        $orderStatus = OrderStatus::all();
        // Retrieve the user's orders, sorted by order_date in descending order
        $orders = Order::where('user_id', $user->id)->orderBy('order_date')->latest('id')->get();

        return view('user-client.orders', compact('orders', 'categories','orderStatus'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orderStatus = OrderStatus::all();
        $order = Order::with('orderDetails.product')->findOrFail($id); // Lấy thông tin đơn hàng và sản phẩm
        $categories = Category::all();
        return view('user-client.orders-show', compact('order',  'categories','orderStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
