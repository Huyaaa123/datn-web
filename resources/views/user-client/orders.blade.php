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
                <h2 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Tài khoản </h2>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route('user.index') }}" class="text-decoration-none">Thông tin
                            tài khoản</a></li>
                    <li class="list-group-item"><a href="{{ route('order.client.user') }}" class="text-decoration-none">Đơn
                            hàng </a></li>
                    <li class="list-group-item"><a href="{{ route('addresses.index') }}"
                            class="text-decoration-none">Địa chỉ </a></li>
                    <li class="list-group-item"><a href="{{ route('password.change') }}"
                            class="text-decoration-none">Đổi mật khẩu</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="SFztPl mb-4">
                <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Đơn hàng</h1>
                <p class="text-muted">Quản lý thông tin đơn hàng của bạn</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Status Filter -->
            <div class="mb-3">
                <form action="{{ route('order.client.user') }}" method="GET" class="form-inline">
                    <label for="status" class="mr-2">Lọc :</label>
                    <select name="status" id="status" class="form-control mr-2">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    <button type="submit" class="btn btn-danger">Lọc</button>
                </form>
            </div>

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
                                    <td>{{ Str::limit($order->telephone,5, '...') }}</td> <!-- Hiển thị số điện thoại từ địa chỉ -->
                                    <td>{{ Str::limit($order->shipping_address, 10, '...')}}</td> <!-- Hiển thị địa chỉ từ địa chỉ -->
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} </td>
                                    <td>{{ $order->orderStatus->name }}</td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>
                                        <a href="{{route('order.client.show', $order->id )}}" >Chi tiết</a>
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
