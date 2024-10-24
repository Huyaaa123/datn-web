@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Thông tin cá nhân</h1>

    <div class="user-info">
        <p><strong>Tên:</strong> {{ $users->name }}</p>
        <p><strong>Email:</strong> {{ $users->email }}</p>
        <!-- Thêm các trường thông tin khác nếu cần -->
    </div>

    <div class="order-status">
        <h2>Trạng thái đơn hàng</h2>
        @if(isset($orders) && $orders->isEmpty())
        <p>Không có đơn hàng nào.</p>
    @else
            <ul>
                @foreach($orders as $order)
                    <li>
                        <strong>Đơn hàng #{{ $order->id }}</strong> -
                        <span>Trạng thái: {{ $order->status }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
