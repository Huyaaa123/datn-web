<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
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

        // Lấy thông tin đơn hàng, bao gồm thông tin chi tiết về sản phẩm
        $order = Order::with('orderDetails.product') // Lấy thông tin chi tiết sản phẩm
            ->findOrFail($id); // Lấy thông tin đơn hàng và sản phẩm

        $categories = Category::all();
        $vouchers = Voucher::all();

        // Lấy voucher đã áp dụng cho đơn hàng từ bảng voucher_details
        $voucherDetail = VoucherDetail::where('order_id', $id)->first();
        $appliedVoucher = $voucherDetail ? $voucherDetail->voucher : null; // Nếu có voucher, lấy thông tin voucher

        // Nếu không có voucher, giữ nguyên giá gốc cho sản phẩm
        foreach ($order->orderDetails as $item) {
            $item->original_price = $item->product->price;
            $item->discounted_price = $item->product->price;
        }

        return view('user-client.orders-show', compact('order', 'categories', 'orderStatus', 'product', 'vouchers', 'appliedVoucher'));
    }





    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        // Tìm đơn hàng theo ID
        $order = Order::findOrFail($id);

        // Kiểm tra nếu trạng thái là "Đã hủy" và đơn hàng đang ở trạng thái "Chờ xác nhận" hoặc "Đã xác nhận"
        if ($request->order_status_id === '7' && in_array($order->order_status_id, [1, 2])) {
            $order->order_status_id = 8; // Đặt trạng thái thành "Đã hủy"
            $order->cancelorder = now();
            $order->cancel = $request->cancel; // Lưu lý do hủy nếu có
            $order->notes = auth()->user()->name; // Lưu tên người hủy
        } elseif ($request->order_status_id === '5') { // Kiểm tra nếu trạng thái là "Đã nhận hàng"
            $order->order_status_id = 5;
            $order->received = now();
            $order['checkpay'] = 'Đã thanh toán';
        } else {
            return redirect()->route('order.client.show', $order->id)
                ->with('error', 'Không thể cập nhật trạng thái đơn hàng.');
        }

        // Lưu các thay đổi
        $order->save();

        // Trả về phản hồi
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
