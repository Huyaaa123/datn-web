@extends('admin.layouts.master')
@section('content')
    <h1>Đơn hàng</h1>
    <style>
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            font-size: 14px;
        }

        .text-center {
            text-align: center;
            display: block;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-success {
            background-color: #08d839;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            font-size: 14px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .text-danger {
            color: #dc3545;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-add {
            background-color: #28a745;
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

        .search-form {
            display: flex;
            align-items: center;
            gap: 5px;
            /* Khoảng cách giữa các phần tử */
        }

        .search-form input {
            width: 200px;
            /* Độ rộng cụ thể */
        }

        .form-select {
            appearance: none;
            /* Ẩn mũi tên mặc định */
            padding: 8px 12px;
            /* Thêm khoảng cách bên trong */
            font-size: 14px;
            /* Kích thước chữ */
            color: #333;
            /* Màu chữ */
            background-color: #f8f9fa;
            /* Màu nền */
            border: 1px solid #ccc;
            /* Đường viền */
            border-radius: 4px;
            /* Bo góc */
            transition: all 0.3s ease;
            /* Hiệu ứng chuyển đổi */
            cursor: pointer;
            /* Con trỏ chuột */
        }

        .form-select:hover {
            border-color: #007bff;
            /* Đổi màu viền khi hover */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Hiệu ứng bóng */
        }

        .form-select:focus {
            outline: none;
            /* Xóa viền mặc định khi focus */
            border-color: #007bff;
            /* Màu viền khi focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Hiệu ứng sáng */
        }

        .form-select option {
            color: #333;
            /* Màu chữ cho các lựa chọn */
            background-color: #fff;
            /* Màu nền */
        }
    </style>

<form action="{{ route('admin.orders.index') }}" method="GET" class="form-group search-form">
    <!-- Tìm kiếm -->
    <input type="text" name="search" class="form-control "
           placeholder="Nhập từ khóa tìm kiếm..."
           value="{{ request('search') }}">
    <!-- Bộ lọc trạng thái -->
    <select name="status" class="form-select me-2 w-25" onchange="this.form.submit()">
        <option value="">Tất cả trạng thái</option>
        @foreach ($orderStatus as $status)
            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>

    <select name="sort" class="form-select me-2 w-25" onchange="this.form.submit()">
        <option value="">Sắp xếp theo</option>
        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Đơn hàng mới nhất</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Đơn hàng cũ nhất</option>
    </select>
</form>


    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Trạng thái</th>
                <th>Người mua</th>
                <th>Sản phẩm</th>
                <th>Tổng </th>
                <th>Ngày đặt</th>
                <th>Địa chỉ</th>
                <th>Thanh toán</th>
                <th>Kiểm tra</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ Str::limit($order->orderStatus->name, 15, '...') }}</td>
                    <td>{{ Str::limit($order->user->name, 10, '...') }}</td>
                    <td>
                        @foreach ($order->orderDetails as $item)
                            <div>
                                {{ Str::limit($item->product->name, 10, '...') }} (x{{ $item->quantity }}) ({{number_format($item->price)}})
                            </div>
                        @endforeach
                    </td>
                    <td>{{ number_format($order->total_amount) }} VND</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s d/m/Y ') }}</td>
                    <td>{{$order->telephone }}, {{ Str::limit($order->shipping_address, 15, '...') }}</td>
                    <td>{{ Str::limit($order->payment_method, 15, '...') }}</td>
                    <td>{{ Str::limit($order->checkpay, 15, '...') }}</td>
                    <td>
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">Xem</a>
                    </td>

                </tr>
            @endforeach
        </tbody>

    </table>
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
@endsection
