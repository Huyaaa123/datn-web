@extends('layouts.master')

@section('content')
<style>
    .sidebar a {
        color: #555; /* Màu chữ cho các liên kết */
        transition: color 0.3s; /* Hiệu ứng chuyển đổi màu sắc */
    }

    .sidebar a:hover {
        color: #007bff; /* Màu chữ khi hover (di chuột) */
    }
</style>

<div class="container my-5 utB99K">
    <div class="row">
        <div class="col-md-4">
            <!-- Sidebar -->
            <div class="sidebar bg-light p-4 rounded">
                <h2 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Tài khoản của tôi</h2>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{route('user.index')}}" class="text-decoration-none">Thông tin tài khoản</a></li>
                    <li class="list-group-item"><a href="{{route('orders.user')}}" class="text-decoration-none">Đơn hàng của tôi</a></li>
                    <li class="list-group-item"><a href="{{route('addresses.index')}}" class="text-decoration-none">Địa chỉ của tôi</a></li>
                    <li class="list-group-item"><a href="{{route('password.change')}}" class="text-decoration-none">Đổi mật khẩu</a></li>
                    <!-- Bạn có thể thêm các mục khác nếu cần -->
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="SFztPl mb-4">
                <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Đơn hàng của tôi</h1>
                <p class="text-muted">Quản lý thông tin đơn hàng của bạn</p>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($orders->isEmpty())
            <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
        @else

            <div class="RCnc9v bg-white p-4 rounded shadow">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã đơn </th>
                            <th>Ngày đặt</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->telephone }}</td> <!-- Hiển thị số điện thoại từ địa chỉ -->
                                <td>{{ Str::limit($order->shipping_address, 10, '...')}}</td> <!-- Hiển thị địa chỉ từ địa chỉ -->
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>
                                    <a href="" class="btn btn-info btn-sm">Chi tiết</a>
                                    <!-- Bạn có thể thêm các hành động khác như hủy đơn hàng ở đây -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
