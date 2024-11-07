<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherClientController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $vouchers = Voucher::where('status', 1, 2)->get(); // Lấy tất cả mã giảm giá đang hoạt đ��ng

        return view("vouchers", compact("categories", "vouchers"));
    }

    public function applyVoucher(Request $request)
    {
        $cart = session()->get('cart', []);
        $orderValue = $this->calculateCartTotal($cart); // Tính tổng giá trị giỏ hàng

        // Kiểm tra voucher
        $voucher = Voucher::where('code', $request->input('voucher_code'))->first();

        if (!$voucher || !$voucher->isValid()) {
            return back()->with('error', 'Mã voucher không hợp lệ hoặc đã hết hạn.');
        }

        // Kiểm tra nếu giá trị đơn hàng đủ điều kiện để áp dụng voucher
        if (!$voucher->isApplicable($orderValue)) {
            return back()->with('error', 'Giá trị đơn hàng không đủ điều kiện để áp dụng voucher.');
        }

        // Kiểm tra xem voucher đã được sử dụng trong đơn hàng nào chưa
        $existingVoucherUsage = Order::where('voucher_id', $voucher->id)->exists();
        if ($existingVoucherUsage) {
            return back()->with('error', 'Voucher này đã được sử dụng cho đơn hàng khác.');
        }

        $totalDiscountAmount = 0; // Số tiền giảm tổng cộng
        $discountPercent = 0; // Phần trăm giảm

        // Áp dụng giảm giá vào giỏ hàng
        foreach ($cart as $index => $item) {
            if ($voucher->discount_type == 'percent') {
                // Áp dụng giảm giá theo phần trăm
                $discountAmount = $item['price'] * $voucher->discount_percent / 100;
                $cart[$index]['price'] -= $discountAmount;
                $totalDiscountAmount += $discountAmount; // Cộng dồn số tiền giảm
                $discountPercent = $voucher->discount_percent; // Lưu phần trăm giảm
            } elseif ($voucher->discount_type == 'amount') {
                // Áp dụng giảm giá theo số tiền
                $cart[$index]['price'] -= $voucher->discount_amount;
                $totalDiscountAmount += $voucher->discount_amount; // Cộng dồn số tiền giảm
            }
        }

        // Cập nhật lại giỏ hàng
        session()->put('cart', $cart);

        // Cập nhật số lần sử dụng voucher
        $voucher->increment('used');

        // Hiển thị thông báo giảm giá
        $discountMessage = '';
        if ($voucher->discount_type == 'percent') {
            $discountMessage = 'Voucher "' . $voucher->code . '" đã được áp dụng. Giảm ' . number_format($discountPercent) . '% tổng giá trị đơn hàng.';
        } elseif ($voucher->discount_type == 'amount') {
            $discountMessage = 'Voucher "' . $voucher->code . '" đã được áp dụng. Giảm ' . number_format($totalDiscountAmount) . ' đ tổng giá trị đơn hàng.';
        }

        // Lưu voucher vào đơn hàng
        session()->put('voucher_code', $voucher->code);

        return back()->with('success', $discountMessage);
    }



    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    public function checkout(Request $request)
{
    $cart = session()->get('cart', []);
    $orderValue = $this->calculateCartTotal($cart);

    // Kiểm tra và áp dụng voucher nếu có
    $voucher = Voucher::where('code', session('voucher_code'))->first();
    if ($voucher && $voucher->isValid() && $voucher->isApplicable($orderValue)) {
        if ($voucher->discount_type == 'percent') {
            $total = $orderValue - ($orderValue * $voucher->discount_percent / 100);
        } elseif ($voucher->discount_type == 'amount') {
            $total = $orderValue - $voucher->discount_amount;
        }
    } else {
        $total = $orderValue;
    }

    // Lưu đơn hàng vào cơ sở dữ liệu
    $order = Order::create([
        'user_id' => auth()->id(),
        'total_amount' => $total,
        'status' => 'pending',
        'shipping_address' => $request->input('shipping_address'),
        'payment_method' => $request->input('payment_method'),
    ]);

    // Xử lý thanh toán và chuyển hướng đến trang thành công
    return redirect()->route('order.success', ['order' => $order->id]);
}

}
