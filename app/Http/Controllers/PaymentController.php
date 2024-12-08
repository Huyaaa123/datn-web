<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $resultCode = $request->input('resultCode'); // Kiểm tra mã kết quả trả về từ MoMo
        $message = $request->input('message'); // Lấy thông báo từ MoMo
        // Nếu giao dịch bị hủy (resultCode = 99)
        if ($resultCode == '99') {
            // Giao dịch bị hủy, xóa giỏ hàng
            session()->forget('cart'); // Xóa giỏ hàng trong session

            // Xóa giỏ hàng người dùng trong cơ sở dữ liệu
            $user = Auth::user();
            $cartItems = $user->carts;
            foreach ($cartItems as $cartItem) {
                $cartItem->delete(); // Xóa từng sản phẩm trong giỏ hàng của người dùng
            }

            // Thông báo cho người dùng
            return redirect()->route('cart.index')->with('error', 'Giao dịch đã bị hủy. Giỏ hàng đã được xóa.');
        }

            if ($resultCode == '0') {
                // Thành công, tạo đơn hàng và lưu thông tin
                $cart = session()->get('cart', []);
                $checkoutAddress = session('checkout_address');
                $phone = session('phone');

                $amount = array_sum(array_map(function ($product) {
                    return $product['quantity'] * $product['price'];
                }, $cart));

                // Kiểm tra số tiền
                if ($amount < 10000 || $amount > 50000000) {
                    return redirect()->back()->with('error', 'Số tiền giao dịch phải từ 10,000 VNĐ đến 50,000,000 VNĐ.');
                }
                $variants = [];
                foreach ($cart as $productId => $product) {
                    $variants[] = $product['color_id'] . ',' . $product['size_id'];
                }
                $order = new Order();
                $order->user_id = auth()->id();
                $order->total_amount = $amount;

                $order->order_date = now();
                $order->order_status_id = 1;
                $order->checkpay = "Đã thanh toán";
                $order->variants = implode(',', $variants);
                $order->shipping_address = implode(', ', [
                    $checkoutAddress['address'],
                    $checkoutAddress['ward'],
                    $checkoutAddress['district'],
                    $checkoutAddress['city']
                ]);
                $order->telephone = $phone;
                $order->payment_method = 'MoMo';
                $order->confirmed = null;
                $order->on_delivery = null;
                $order->received = null;
                $order->complete = null;
                $order->cancelorder = null;
                $order->canceled = null;

                $order->save();
                foreach ($cart as $productId => $product) {
                    // Ép kiểu product_id thành integer
                    $productId = intval($productId);

                    // Kiểm tra nếu product_id hợp lệ (là số dương và tồn tại trong bảng products)
                    if ($productId > 0 && Product::find($productId)) {
                        $order->orderDetails()->create([
                            'product_id' => $productId,
                            'quantity' => $product['quantity'],
                            'price' => $product['price'],
                            'total' => $product['quantity'] * $product['price'] // Tính tổng nếu chưa có
                        ]);
                    } else {
                        // Nếu product_id không hợp lệ, có thể báo lỗi hoặc tiếp tục với các sản phẩm hợp lệ
                        return redirect()->back()->with('error', 'Có lỗi xảy ra với một hoặc nhiều sản phẩm.');
                    }
                }
                 //check luot dung voucher
                 if (session()->has('voucher_code')) {
                    $voucherCode = session('voucher_code');
                    $voucher = Voucher::where('code', $voucherCode)->first();

                    // Cập nhật số lần sử dụng voucher
                    if ($voucher) {
                        $voucher->increment('used'); // Tăng số lần sử dụng
                        $voucher->save();
                    }

                    VoucherDetail::create([
                        'voucher_id' => $voucher->id,
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                    ]);
                    // Xóa voucher khỏi session sau khi thanh toán thành công
                    session()->forget('voucher_code');
                    session()->forget('discount_amount');
                }

                // Xóa giỏ hàng sau khi thanh toán thành công
                session()->forget('cart');

                return redirect()->route('order.success')->with('message', 'Giao dịch thành công!');
            }
         else {
            // Nếu có lỗi
            return redirect()->route('order.danger')->with('error', 'Đã xảy ra lỗi: ' . $message);
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
