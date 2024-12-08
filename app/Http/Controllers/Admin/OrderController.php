<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('status');
        $sort = $request->input('sort'); // Lấy giá trị từ select 'sort'

        $orderStatus = OrderStatus::all();

        $orders = Order::with('user')
            ->when($search, function ($query, $search) {
                // Tìm kiếm theo tên người dùng, id đơn hàng và địa chỉ giao hàng
                $query->where('payment_method', 'like', "%{$search}%")
                      ->orWhere('shipping_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($subQuery) use ($search) {
                          $subQuery->where('name', 'like', "%{$search}%");
                      });
            })
            ->when($filterStatus, function ($query, $filterStatus) {
                // Lọc theo trạng thái đơn hàng
                $query->where('order_status_id', $filterStatus);
            })
            // Thêm điều kiện sắp xếp dựa trên lựa chọn 'sort'
            ->when($sort, function ($query, $sort) {
                if ($sort == 'latest') {
                    $query->latest(); // Sắp xếp theo đơn hàng mới nhất
                } elseif ($sort == 'oldest') {
                    $query->oldest(); // Sắp xếp theo đơn hàng cũ nhất
                }
            })
            ->paginate(4); // Phân trang kết quả

        return view('admin.orders', compact('orders', 'orderStatus', 'search', 'filterStatus', 'sort'));
    }

    public function create()
    {

    }

    public function store(StoreOrderRequest $request)
    {

    }

    public function show(Order $order)
    {
        return view('admin.show.orders-show', compact('order'));
    }

    public function edit(Order $order)
    {
        $orderStatus = OrderStatus::all();
        return view('admin.crud.orders-edit', compact('order', 'orderStatus')); // Hiển thị chi tiết đơn hàng
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        DB::transaction(function () use ($order, $request) {

            // Kiểm tra trạng thái đơn hàng
            if ($order->order_status_id === 8) { // Nếu trạng thái là "Chờ xác nhận hủy"
                $dataOrder = [
                    'order_status_id' => 9, // Chuyển sang trạng thái "Đã hủy"
                    'canceled' => now(),
                ];
            } else {
                // Trường hợp khác (cập nhật trạng thái thông thường)
                $dataOrder = [
                    'order_status_id' => $request->order_status_id,
                    'cancel' => $request->cancel,
                    'checkpay' => $order->checkpay,
                    'notes' => auth()->user()->name,
                ];
                if ($request->order_status_id == 2 && !$order->confirmed) {
                    $dataOrder['confirmed'] = now();
                }

                if ($request->order_status_id == 3 && !$order->on_delivery) {
                    $dataOrder['on_delivery'] = now();
                }
                if ($request->order_status_id == 4 && !$order->delivered) {
                    $dataOrder['delivered'] = now();
                }

                if ($request->order_status_id == 5 && !$order->received) {
                    $dataOrder['received'] = now();
                }

                if ($request->order_status_id == 6 && !$order->complete) {
                    $dataOrder['complete'] = now();
                }
                if ($request->order_status_id == 9 && !$order->canceled) {
                    $dataOrder['canceled'] = now();
                }

            }

            // Cập nhật trạng thái đơn hàng
            $order->update($dataOrder);
        });

        return redirect()->route('admin.orders.edit', $order->id)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function destroy(Order $order)
    {

    }


}
