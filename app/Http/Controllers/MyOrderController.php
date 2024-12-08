<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Size;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = Category::all();
        $orderStatus = OrderStatus::all();

        // Truy vấn các đơn hàng của người dùng
        $orders = Order::where('user_id', $user->id)
            ->with('orderDetails.product')
            ->orderBy('order_date', 'desc');

        // Nếu có giá trị trạng thái trong request, thêm điều kiện vào truy vấn
        if ($request->has('order_status_id') && $request->input('order_status_id') !== '') {
            $orders->where('order_status_id', $request->input('order_status_id'));
        }

        $orders = $orders->paginate(4); // Thực hiện phân trang

        return view('user-client.orders', compact('orders', 'categories', 'orderStatus'));
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
        $product = Product::all();
        $orderStatus = OrderStatus::all();

        // Lấy thông tin đơn hàng và chi tiết sản phẩm
        $order = Order::with('orderDetails.product')->findOrFail($id);

        $categories = Category::all();
        $vouchers = Voucher::all();

        $colors = Color::pluck('name', 'id')->toArray();
        $sizes = Size::pluck('name', 'id')->toArray();

        // Lấy voucher đã áp dụng
        $voucherDetail = VoucherDetail::where('order_id', $id)->first();
        $appliedVoucher = $voucherDetail ? $voucherDetail->voucher : null;

        $totalDiscount = 0; // Tổng số tiền giảm giá

        if ($appliedVoucher) {
            foreach ($order->orderDetails as $item) {
                $item->original_price = $item->product->price;

                if ($appliedVoucher->discount_type == 'percent') {
                    // Tính giảm giá theo phần trăm
                    $discount = ($item->product->price * $appliedVoucher->discount_percent) / 100;

                    // Kiểm tra mức giảm tối đa (nếu có)
                    if ($appliedVoucher->max_discount_amount) {
                        $discount = min($discount, $appliedVoucher->max_discount_amount);
                    }

                    $item->discounted_price = $item->product->price - $discount; // Giá sau giảm
                    $item->discount_amount = $discount; // Số tiền đã giảm
                    $totalDiscount += $discount; // Cộng tổng giảm giá
                } elseif ($appliedVoucher->discount_type == 'amount') {
                    // Giảm giá cố định
                    $item->discounted_price = $item->product->price - $appliedVoucher->discount_amount;
                    $item->discount_amount = $appliedVoucher->discount_amount;
                    $totalDiscount += $appliedVoucher->discount_amount;
                } else {
                    $item->discounted_price = $item->product->price;
                    $item->discount_amount = 0;
                }
            }
        } else {
            foreach ($order->orderDetails as $item) {
                $item->original_price = $item->product->price;
                $item->discounted_price = $item->product->price;
                $item->discount_amount = 0;
            }
        }

        return view('user-client.orders-show', compact('order', 'categories', 'orderStatus', 'product', 'vouchers', 'appliedVoucher', 'totalDiscount', 'colors', 'sizes'));
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        if ($request->order_status_id === '7' && in_array($order->order_status_id, [1, 2, 4])) {
            $order->order_status_id = 8;
            $order->cancelorder = now();
            $order->cancel = $request->cancel;
            $order->notes = auth()->user()->name;
        } elseif ($request->order_status_id === '5') {
            $order->order_status_id = 5;
            $order->received = now();
            $order['checkpay'] = 'Đã thanh toán';

            if ($order['checkpay'] === 'Đã thanh toán') {
                $order->order_status_id = 6; // Trạng thái "Thành công"
                $order->complete = now(); // Lưu thời gian hoàn thành
            }
        } else {
            return redirect()->route('order.client.show', $order->id)
                ->with('error', 'Không thể cập nhật trạng thái đơn hàng.');
        }
        $order->save();

        return redirect()->route('order.client.show', $order->id)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
