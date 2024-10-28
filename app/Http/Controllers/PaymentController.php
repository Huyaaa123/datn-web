<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function thankYou()
    {
        $categories = Category::all();
        return view('thanks',compact('categories'));
    }

    public function storeOrder(Request $request)
    {
        // Kiểm tra nếu thanh toán thành công
        if ($request->get('resultCode') == '0') {
            // Lấy thông tin giỏ hàng từ session
            $cart = session()->get('cart', []);

            // Tạo đơn hàng mới
            $order = Order::create([
                'user_id' => auth()->id() ?? null, // null nếu không đăng nhập
                'status' => 'paid',
                'total_amount' => array_sum(array_map(function ($product) {
                    return $product['quantity'] * $product['price'];
                }, $cart)), // Tổng tiền
                'order_date' => now(),
                'shipping_address' => $request->get('address') ?? 'default address',
            ]);

            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }


            session()->forget('cart');

            return redirect()->route('order.success')->with('message', 'Đơn hàng của bạn đã được lưu thành công!');
        } else {
            return redirect()->route('checkout')->with('error', 'Thanh toán không thành công!');
        }
    }
}
