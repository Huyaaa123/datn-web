<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $orderValue = $this->calculateCartTotal($cart);
        $voucherCode = $request->input('voucher_code');

        if (empty($voucherCode)) {
            session()->forget('voucher_code');
            session()->forget('discount_amount');
            foreach ($cart as $index => $item) {
                if (isset($item['original_price'])) {
                    $cart[$index]['price'] = $item['original_price'];
                }
            }
            session()->put('cart', $cart);
            return back()->with('success', 'Không áp dụng voucher cho đơn hàng này.');
        }

        foreach ($cart as $index => $item) {
            if (isset($item['original_price'])) {
                $cart[$index]['price'] = $item['original_price'];
            } else {
                $cart[$index]['original_price'] = $item['price'];
            }
        }
        session()->put('cart', $cart);

        $voucher = Voucher::where('code', $voucherCode)->first();

        if (!$voucher || !$voucher->isValid()) {
            return back()->with('error', 'Mã voucher không hợp lệ hoặc đã hết hạn.');
        }

        if (!$voucher->isApplicable($orderValue)) {
            return back()->with('error', 'Giá trị đơn hàng không đủ điều kiện để áp dụng voucher.');
        }

        $totalDiscountAmount = 0;
        $discountPercent = 0;

        if ($voucher->discount_type == 'percent') {
            $discountAmount = $orderValue * $voucher->discount_percent / 100;
            $discountPercent = $voucher->discount_percent;
            foreach ($cart as $index => $item) {
                $itemDiscount = $item['price'] * $discountPercent / 100;
                $cart[$index]['price'] -= $itemDiscount;
            }
            $totalDiscountAmount = $discountAmount;
        } elseif ($voucher->discount_type == 'amount') {
            $totalDiscountAmount = min($voucher->discount_amount, $orderValue);
            $remainingDiscount = $totalDiscountAmount;
            foreach ($cart as $index => $item) {
                $itemDiscount = min($remainingDiscount, $item['price']);
                $cart[$index]['price'] -= $itemDiscount;
                $remainingDiscount -= $itemDiscount;
            }
        }

        if ($totalDiscountAmount > 0) {
            session()->put('discount_amount', $totalDiscountAmount);
        }
        session()->put('cart', $cart);
        session()->put('voucher_code', $voucher->code);


        $discountMessage = '';
        if ($voucher->discount_type == 'percent') {
            $discountMessage = 'Voucher "' . $voucher->code . '" đã được áp dụng. Giảm ' . number_format($discountPercent) . '% tổng giá trị đơn hàng.';
        } elseif ($voucher->discount_type == 'amount') {
            $discountMessage = 'Voucher "' . $voucher->code . '" đã được áp dụng. Giảm ' . number_format($totalDiscountAmount) . ' đ tổng giá trị đơn hàng.';
        }

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
