@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMD1T5jWkEXhLRx5q2pr0Y5afQe1fFj7kh4OrNg" crossorigin="anonymous">
    <div class="container my-5">
        <div class="RCnc9v bg-white p-4 rounded shadow">
            <h2 class="h4 mb-4" style="color:rgb(37, 36, 36); font-weight: bold">Chi tiết Đơn hàng</h2>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Ngày đặt</th>
                        <th>Giá</th>
                        <th>Địa chỉ</th>
                        <th>Thanh toán</th>
                        <th>Ghi chú</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $item)
                        <tr>
                            <td>
                                @if ($item->product->image_path)
                                    <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}"
                                        style="height: auto; width:100px;">
                                @else
                                    Không có hình ảnh
                                @endif
                            </td>
                            <td>{{ $item->product->name }} (x{{ $item->quantity }})</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s d/m/Y ') }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                            <td>{{ $order->telephone }} / {{ $order->shipping_address }} </td>
                            <td>{{ $order->payment_method }}</td>
                            <td>{{ $order->checkpay }}</td>
                            <td>
                                <strong>{{ $order->orderStatus->name }}</strong>
                                @if ($order->orderStatus->id == 9) <!-- Kiểm tra nếu trạng thái là "Đã hủy" -->
                                    <button type="button" class="btn btn-link p-0 text-primary" onclick="toggleCancelInfo()">

                                    </button>
                                    <div id="cancelInfo" style="display: none; margin-top: 10px;">
                                        <p><strong>Lý do hủy:</strong> {{ $order->cancel ?? 'Không có' }}</p>
                                        <p><strong>Người hủy:</strong> {{ $order->notes ?? 'Không xác định' }}</p>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <h4>Tổng tiền: <span class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} VND</span>
                </h4>

                @if (in_array($order->orderStatus->id, [1, 2]))
                    <!-- 1: Chờ xác nhận, 2: Đã xác nhận -->
                    <form action="{{ route('order.client.update', $order->id) }}" method="POST" class="mr-2">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-danger" onclick="toggleCancelForm()">
                            Hủy đơn
                        </button>
                    </form>
                @endif

                @if ($order->orderStatus->id === 4)
                    <!-- 4: Đã giao hàng -->
                    <form action="{{ route('order.client.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_status_id" value="5"> <!-- ID trạng thái 'Đã nhận hàng' -->
                        <button type="submit" class="btn btn-success">Đã nhận hàng</button>
                    </form>
                @endif
            </div>

            <a href="{{ route('order.client.user') }}" class="btn btn-secondary mt-3">Quay lại Đơn hàng</a>
        </div>
    </div>

    <!-- Form hủy đơn hàng, hiển thị khi nhấn nút "Hủy đơn" -->
    <div id="cancelForm" style="display: none; margin-top: 20px;">
        <form action="{{ route('order.client.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="order_status_id" value="7"> <!-- ID trạng thái 'Hủy' -->
            <div class="form-group">
                <label for="cancelReason">Lý do hủy:</label>
                <textarea class="form-control" id="cancelReason" name="cancel" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
            <button type="button" class="btn btn-secondary" onclick="toggleCancelForm()">Đóng</button>
        </form>
    </div>

@endsection

<script>
    function toggleCancelInfo() {
        var cancelInfo = document.getElementById('cancelInfo');
        cancelInfo.style.display = cancelInfo.style.display === 'none' ? 'block' : 'none';
    }

    function toggleCancelForm() {
        var cancelForm = document.getElementById('cancelForm');
        cancelForm.style.display = cancelForm.style.display === 'none' ? 'block' : 'none';
    }
</script>
