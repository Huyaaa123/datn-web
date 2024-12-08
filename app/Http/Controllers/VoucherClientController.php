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

    public function applyVoucher(Request $request)
    {
        $cart = session()->get('cart', []);
        $orderValue = $this->calculateCartTotal($cart);

        // Lấy voucher_id từ radio button
        $voucherId = $request->input('voucher_id');

        // Nếu không có voucher_id, xóa áp dụng voucher
        if (empty($voucherId)) {
            session()->forget('voucher_code');
            session()->forget('voucher_id');
            session()->forget('discount_amount');

            // Khôi phục giá gốc cho mỗi sản phẩm trong giỏ hàng
            foreach ($cart as $index => $item) {
                if (isset($item['original_price'])) {
                    $cart[$index]['price'] = $item['original_price'];
                }
            }
            session()->put('cart', $cart);
            return back()->with('success', 'Không áp dụng voucher cho đơn hàng này.');
        }

        // Khôi phục giá gốc cho mỗi sản phẩm trong giỏ hàng trước khi áp dụng voucher mới
        foreach ($cart as $index => $item) {
            if (isset($item['original_price'])) {
                $cart[$index]['price'] = $item['original_price'];
            } else {
                $cart[$index]['original_price'] = $item['price'];
            }
        }

        session()->put('cart', $cart);
        session()->put('voucher_id', $voucherId); // Lưu ID voucher vào session

        // Tìm voucher dựa trên voucher_id
        $voucher = Voucher::find($voucherId);

        // Kiểm tra voucher hợp lệ
        if (!$voucher || !$voucher->isValid() || $voucher->used >= $voucher->usage_limit) {
            session()->forget('voucher_code');
            session()->forget('voucher_id');
            session()->forget('discount_amount');

            foreach ($cart as $index => $item) {
                if (isset($item['original_price'])) {
                    $cart[$index]['price'] = $item['original_price'];
                }
            }
            session()->put('cart', $cart);

            return back()->with('error', 'Mã voucher đã hết hạn hoặc chưa đến thời điểm sử dụng.');
        }

        // Kiểm tra xem giá trị đơn hàng có đủ điều kiện để áp dụng voucher không
        if (!$voucher->isApplicable($orderValue)) {
            session()->forget('voucher_code');
            session()->forget('voucher_id');
            session()->forget('discount_amount');

            foreach ($cart as $index => $item) {
                if (isset($item['original_price'])) {
                    $cart[$index]['price'] = $item['original_price'];
                }
            }
            session()->put('cart', $cart);

            return back()->with('error', 'Giá trị đơn hàng không đủ điều kiện để áp dụng voucher.');
        }

        // Áp dụng chiết khấu và lưu vào session như trước
        $totalDiscountAmount = 0;
        $discountPercent = 0;

        if ($voucher->discount_type == 'percent') {
            // Nếu voucher là giảm theo phần trăm
            $discountPercent = $voucher->discount_percent;
            $totalDiscountAmount = $orderValue * $discountPercent / 100;

            // Kiểm tra giảm giá tối đa
            if ($voucher->max_discount_amount) {
                $totalDiscountAmount = min($totalDiscountAmount, $voucher->max_discount_amount);
            }

            // Tính tổng giá trị giỏ hàng
            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }

            // Cập nhật giá sản phẩm trong giỏ hàng
            foreach ($cart as $index => $item) {
                // Tính phần giảm giá cho mỗi sản phẩm, dựa trên số lượng
                $itemDiscount = ($item['price'] * $item['quantity']) / $cartTotal * $totalDiscountAmount;
                $cart[$index]['price'] = max(0, $item['price'] - $itemDiscount);
            }
        } elseif ($voucher->discount_type == 'amount') {
            // Nếu voucher là giảm theo số tiền cố định
            $totalDiscountAmount = min($voucher->discount_amount, $orderValue);

            // Tính tổng giá trị giỏ hàng
            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }

            // Cập nhật giá sản phẩm trong giỏ hàng
            foreach ($cart as $index => $item) {
                // Tính phần giảm giá cho mỗi sản phẩm
                $itemDiscount = ($item['price'] * $item['quantity']) / $cartTotal * $totalDiscountAmount;
                $cart[$index]['price'] -= $itemDiscount;
            }
        }

        // Lưu chiết khấu trong session
        if ($totalDiscountAmount > 0) {
            session()->put('discount_amount', $totalDiscountAmount);
        }
        session()->put('cart', $cart);
        session()->put('voucher_code', $voucher->code);

        // Thông báo thành công
        return back()->with('success', 'Voucher đã được áp dụng thành công.');
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
    public function removeVoucher()
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // Xóa voucher khỏi session
        session()->forget('voucher_code');
        session()->forget('voucher_id');
        session()->forget('discount_amount');

        // Khôi phục giá gốc cho mỗi sản phẩm trong giỏ hàng
        foreach ($cart as $index => $item) {
            if (isset($item['original_price'])) {
                $cart[$index]['price'] = $item['original_price']; // Đặt lại giá gốc cho sản phẩm
            }
        }

        // Lưu lại giỏ hàng với giá gốc
        session()->put('cart', $cart);

        // Trả về trang trước đó với thông báo thành công
        return back()->with('success', 'Không áp dụng voucher.');
    }

}
