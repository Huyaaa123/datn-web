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
        return view('thanks', compact('categories'));
    }
    public function sorry()
    {
        $categories = Category::all();
        return view('sorry', compact('categories'));
    }
    // Controller
    public function paymentCallback(Request $request)
    {
        $resultCode = $request->input('resultCode');
        $message = $request->input('message');
        $orderId = $request->input('orderId');
        $amount = $request->input('amount');

        // Tìm đơn hàng theo orderId
        $order = Order::where('id', $orderId)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($resultCode == '0') {
            // Giao dịch thành công
            $order->order_status_id = 1; // Chờ xử lý
            $order->checkpay = 'Đã thanh toán';
            $order->save();

            return redirect()->route('order.success')->with('success', 'Thanh toán thành công.');
        } else {
            // Giao dịch thất bại
            $order->order_status_id = 9; // Đã hủy
            $order->checkpay = 'Chưa thanh toán';
            $order->save();

            return redirect()->route('order.danger')->with('error', 'Thanh toán thất bại: ' . $message);
        }
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
