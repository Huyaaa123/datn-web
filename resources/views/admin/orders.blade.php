@extends('admin.layouts.master')
@section('content')
    <h1>Orders</h1>
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
            text-align: left;
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
    </style>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Cancel</th>
                <th>User</th>
                <th>Products</th>
                <th>Total</th>
                <th>Date Order</th>
                <th>Ship</th>
                <th>Phone</th>
                <th>Payment</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ Str::limit($order->orderStatus->name, 15, '...') }}</td>
                    <td>
                        @if ($order->cancel)
                            {{ Str::limit($order->cancel, 15, '...') }}
                        @else
                            No Problem
                        @endif
                    </td>
                    <td>{{ Str::limit($order->user->name, 10, '...') }}</td>
                    <td>
                        @foreach ($order->orderDetails as $item)
                            <div>
                                {{ Str::limit($item->product->name, 10, '...') }} (SL: x{{ $item->quantity }})
                            </div>
                        @endforeach
                    </td>
                    <td>{{ number_format($order->total_amount) }} VND</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($order->shipping_address, 15, '...') }}</td>
                    <td>{{ Str::limit($order->telephone, 15, '...') }}</td>
                    <td>{{ Str::limit($order->payment_method, 15, '...') }}</td>
                    <td>
                        @if ($order->notes)
                            {{ Str::limit($order->notes, 15, '...') }}
                        @else
                            ...
                        @endif
                    </td>
                    <td>
                        @if ($order->order_status_id === 6)
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-success">Show</a>
                        @elseif($order->order_status_id === 7)
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-success">Show</a>
                        @else
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-success">Show</a>
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
                        @endif
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
