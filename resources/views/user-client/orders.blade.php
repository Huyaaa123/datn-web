@extends('layouts.master')

@section('content')
    <style>
        .sidebar a {
            color: #555;
            transition: color 0.3s;
        }

        .sidebar a:hover {
            color: #007bff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .page-link {
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 4px;
            color: #007bff;
            text-decoration: none;
        }

        .page-link:hover {
            background-color: #007bff;
            color: #fff;
        }

        .pagination .disabled .page-link {
            color: #ccc;
        }

        .pagination .active .page-link {
            background-color: #007bff;
            color: #fff;
        }
    </style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container my-5 utB99K">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="sidebar bg-light p-2 rounded">
                <h2 class="h5 " style="color:rgb(37, 36, 36);"> <i class="fa-regular fa-user"></i> Quản lý tài khoản </h2>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route('user.index') }}" class="text-decoration-none">Thông tin tài khoản</a></li>
                    <li class="list-group-item"><a href="{{ route('order.client.user') }}" class="text-decoration-none">Đơn hàng</a></li>
                    <li class="list-group-item"><a href="{{ route('addresses.index') }}" class="text-decoration-none">Địa chỉ</a></li>
                    <li class="list-group-item"><a href="{{ route('password.change') }}" class="text-decoration-none">Đổi mật khẩu</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="SFztPl mb-4">
                <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Đơn hàng</h1>
                <p class="text-muted">Quản lý thông tin đơn hàng của bạn</p>
            </div>
            <form method="GET" action="{{ route('order.client.user') }}" class="mb-4" id="filter-form">
                <select name="order_status_id" id="order_status_id" class="form-select" onchange="handleSelectChange()">
                    <option value="">Tất cả trạng thái</option>
                    @foreach ($orderStatus as $status)
                        <option value="{{ $status->id }}" {{ request('order_status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($orders->isEmpty())
                <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
            @else
            <div class="bg-white p-2 rounded shadow table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ngày đặt</th>
                            <th>Ảnh</th>
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
                                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                        <img src="{{ asset('storage/' . $detail->product->image_path) }}" alt="{{ $detail->product->name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                        <div>
                                            <strong>{{ Str::limit($detail->product->name, 15, '...') }}</strong> (x{{ $detail->quantity }}) ({{ number_format($detail->price) }}đ)
                                        </div>
                                    </div>
                                    @endforeach
                                </td>

                                <td>{{ Str::limit($order->telephone, 5, '...') }}/{{ Str::limit($order->shipping_address, 10, '...') }}</td>
                                <td style=" color: #AA0000;">{{ number_format($order->total_amount, 0, ',', '.') }} </td>
                                <td><strong>{{ $order->orderStatus->name }}</strong></td>
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
                    {{-- Hiển thị các trang --}}
                    @if ($orders->currentPage() > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $orders->url(1) }}">1</a>
                        </li>
                    @endif

                    @for ($i = 2; $i < $orders->currentPage(); $i++)
                        <li class="page-item">
                            <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    <li class="page-item active">
                        <a class="page-link" href="#">{{ $orders->currentPage() }}</a>
                    </li>

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
<script>
    function handleSelectChange() {
        var select = document.getElementById('order_status_id');
        var selectedValue = select.value;

        if (selectedValue === '') {
            var url = new URL(window.location.href);
            url.searchParams.delete('order_status_id'); // Xóa tham số order_status_id
            window.location.href = url.toString();
        } else {
            document.getElementById('filter-form').submit();
        }
    }
    </script>
@endsection
