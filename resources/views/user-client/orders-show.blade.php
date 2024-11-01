@extends('layouts.master')

@section('content')
<div class="container my-5">
    <div class="RCnc9v bg-white p-4 rounded shadow">
        <h2 class="h4 mb-4" style="color:rgb(37, 36, 36); font-weight: bold">Chi tiết </h2>

        <div class="mb-4">
            <p><strong>Mã đơn hàng:</strong> {{ $order->id }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->order_date }}</p>
            <p><strong>Trạng thái:</strong> {{ $order->orderStatus->name }}</p>
            <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->telephone }}</p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>

        <h5>Sản phẩm trong đơn hàng</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Nút hủy đơn hàng -->
        @if ($order->status === 'Chờ xác nhận' || $order->status === 'Đã xác nhận')
            <button class="btn btn-danger mt-3" data-toggle="modal" data-target="#cancelOrderModal">Hủy đơn hàng</button>
        @endif

        <a href="{{ route('order.client.user') }}" class="btn btn-secondary mt-3">Quay lại Đơn hàng</a>
    </div>
</div>

<!-- Modal xác nhận hủy đơn -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Lý do hủy đơn hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason">Lý do hủy đơn:</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
