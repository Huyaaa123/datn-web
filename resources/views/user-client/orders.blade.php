@extends('layouts.master')

@section('content')
    <style>
        .sidebar a {
            color: #555;
            /* Màu chữ cho các liên kết */
            transition: color 0.3s;
            /* Hiệu ứng chuyển đổi màu sắc */
        }

        .sidebar a:hover {
            color: #007bff;
            /* Màu chữ khi hover (di chuột) */
        }

        .pagination {
            display: flex;
            /* Sử dụng Flexbox để căn giữa */
            justify-content: center;
            /* Căn giữa các nút */
            align-items: center;
            /* Căn giữa theo chiều dọc */
            list-style: none;
            /* Xóa các dấu chấm */
            padding: 0;
            /* Xóa padding */
            margin: 20px 0;
            /* Khoảng cách trên và dưới */
        }

        .pagination li {
            margin: 0 5px;
            /* Khoảng cách giữa các nút */
        }

        .page-link {
            padding: 8px 12px;
            /* Padding cho các nút */
            border: 1px solid #007bff;
            /* Đường viền cho các nút */
            border-radius: 4px;
            /* Bo góc */
            color: #007bff;
            /* Màu chữ */
            text-decoration: none;
            /* Xóa gạch chân */
        }

        .page-link:hover {
            background-color: #007bff;
            /* Màu nền khi hover */
            color: #fff;
            /* Màu chữ khi hover */
        }

        .pagination .disabled .page-link {
            color: #ccc;
            /* Màu chữ cho nút bị vô hiệu hóa */
        }

        .pagination .active .page-link {
            background-color: #007bff;
            /* Màu nền cho trang đang hoạt động */
            color: #fff;
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
                        <li class="list-group-item"><a href="{{ route('order.client.user') }}"
                                class="text-decoration-none">Đơn
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

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($orders->isEmpty())
                    <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
                @else
                <div class=" bg-white p-2 rounded shadow table-responsive">
                    <table class="table ">
                            <thead>
                                <tr>
                                    <th>Ngày đặt</th>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Địa chỉ</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th>Ghi chú</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s d/m/Y ') }}</td>

                                        <td>
                                            @foreach ($order->orderDetails as $detail)
                                                <div>
                                                    <img src="{{ asset('storage/' . $detail->product->image_path) }}" alt="{{ $detail->product->name }}" width="50" height="50">
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($order->orderDetails as $detail)
                                                <div>
                                                    <span>{{ Str::limit($detail->product->name, 10, '...') }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <!-- Hiển thị số điện thoại từ địa chỉ -->
                                        <td>{{ Str::limit($order->telephone, 5, '...') }}/{{ Str::limit($order->shipping_address, 10, '...') }}</td>
                                        <!-- Hiển thị địa chỉ từ địa chỉ -->
                                        <td>{{ number_format($order->total_amount, 0, ',', '.') }} </td>
                                        <td>{{ $order->orderStatus->name }}</td>
                                        <td>{{ $order->payment_method }} </td>
                                        <td>{{ $order->checkpay }}</td>
                                        <td>
                                            <a href="{{ route('order.client.show', $order->id) }}">Chi tiết</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <ul class="pagination">
                            {{-- Nếu trang hiện tại không phải là trang đầu tiên --}}
                            @if ($orders->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->url(1) }}">1</a>
                                </li>
                            @endif

                            {{-- Hiển thị các trang trước trang hiện tại --}}
                            @for ($i = 2; $i < $orders->currentPage(); $i++)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Trang hiện tại --}}
                            <li class="page-item active">
                                <a class="page-link" href="#">{{ $orders->currentPage() }}</a>
                            </li>

                            {{-- Hiển thị các trang sau trang hiện tại --}}
                            @for ($i = $orders->currentPage() + 1; $i <= $orders->lastPage(); $i++)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                        </ul>
                    </div>
                @endif
            </div>

        </div>

    </div>


@endsection
