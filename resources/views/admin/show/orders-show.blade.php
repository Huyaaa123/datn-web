@extends('admin.layouts.master')

@section('content')
<style>
    h1 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        font-size: 14px;
        border: none;
        border-radius: 4px;
        color: #fff;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        margin-top: 15px; /* Đảm bảo có khoảng cách ở trên */
    }

    .btn-primary {
        background-color: #ff1515;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-success {
        background-color: #28a745;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        text-align: center;
    }

    .card-body {
        padding: 20px;
    }

    .card-body p {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 10px;
    }

    .card-body p strong {
        color: #333;
    }

    .image-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 10px;
    }

    .product-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0;
        width: auto;
    }

    .product-item img {
        height: auto;
        width: 100px;
        margin: 0 5px;
    }

    .order-info {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    .order-info p {
        margin-right: 20px;
    }
</style>

<h1>Chi tiết đơn hàng #{{ $order->id }}</h1>

<div class="card">
    <div class="card-body">
        <p><strong>Người mua: </strong> {{ $order->user->name }}</p>
        <p><strong>Sản phẩm: </strong>
            <div class="image-container">
                @foreach ($order->orderDetails as $item)
                    <div class="product-item">
                        <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}">
                        {{ $item->product->name }} (SL: x{{ $item->quantity }}) ({{number_format($item->price)}})
                    </div>
                @endforeach
            </div>
        </p>
        <div class="order-info">
            <p><strong>Ngày đặt: </strong> {{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s d/m/Y') }}</p>
            <p><strong>Trạng thái: </strong>
                @if ($order->order_status_id === 6)
                    <span style="color: #28a745; font-weight:bold;">Đã hoàn thành</span>
                @elseif ($order->order_status_id === 7)
                    <span style="color: #c82333; font-weight:bold;">Đã hủy</span>
                @else
                    {{ $order->orderStatus->name }}
                @endif
            </p>
        </div>

        <div class="order-info">
            <p><strong>Phương thức: </strong> {{ $order->payment_method }}</p>
            <p><strong>Kiểm tra: </strong> {{ $order->checkpay }}</p>
            <p><strong>Tổng: </strong> {{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
        </div>

        <div class="order-info">
            <p><strong>Người thao tác: </strong> {{ $order->notes ?? '...' }}</p>
            <p><strong>Lý do: </strong>
                @if ($order->cancel)
                    {{ $order->cancel }}
                @else
                    ...
                @endif
            </p>
        </div>

        <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
    </div>

    <!-- Đặt các nút bên ngoài card-body để đảm bảo không bị lỗi hiển thị -->
    <div class="d-flex justify-content-center">
        @if ($order->order_status_id === 6 || $order->order_status_id === 7)
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Cancel</a>
        @else
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-success">Sửa</a>
        @endif
    </div>
</div>
@endsection
