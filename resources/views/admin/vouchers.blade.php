@extends('admin.layouts.master')
@section('content')
    <h1>Mã giảm giá</h1>
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
            font-family: 'Playfair Display', serif;

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

        .active-voucher {
            color: green;
            font-weight: bold;
        }

        .inactive-voucher {
            color: red;
            font-weight: bold;
        }

        .not-started-voucher {
            color: rgb(56, 56, 56);
            font-weight: bold;
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
            /* Màu chữ cho trang đang hoạt động */
        }
    </style>

    <a href="{{ route('admin.vouchers.create') }}" class="text-center btn btn-add">Thêm</a>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Giảm giá</th>
                <th>Giá trị tối thiểu</th>
                <th>Đã dùng</th>
                <th>Tối đa</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vouchers as $voucher)
                <tr data-voucher-id="{{ $voucher->id }}">
                    <td>{{ $voucher->code }}</td>
                    <td>
                        @if ($voucher->discount_amount)
                            {{ number_format($voucher->discount_amount) }} VND
                        @elseif ($voucher->discount_percent)
                            {{ number_format($voucher->discount_percent) }}%
                        @endif
                    </td>

                    <td>{{ number_format($voucher->min_order_value) }} VND</td>
                    <td>{{ $voucher->used }}</td>
                    <td>{{ $voucher->usage_limit }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->start_date)->format('H:i:s d/m/Y ') }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('H:i:s d/m/Y ') }}</td>
                    <td class="voucher-status">
                        <span
                            class="
                            {{ \Carbon\Carbon::parse($voucher->start_date)->isFuture() ? 'not-started-voucher' : ($voucher->status == 1 ? 'active-voucher' : 'inactive-voucher') }}">
                            @if (\Carbon\Carbon::parse($voucher->start_date)->isFuture())
                                Chưa bắt đầu
                            @elseif ($voucher->status == 1)
                                Còn hạn
                            @else
                                Hết hạn
                            @endif
                        </span>
                    </td>


                    <td>
                        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-primary">Sửa</a>
                        <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST"
                            style="display:inline;" onsubmit="confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <ul class="pagination">
            {{-- Nếu trang hiện tại không phải là trang đầu tiên --}}
            @if ($vouchers->currentPage() > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $vouchers->url(1) }}">1</a>
                </li>
            @endif

            {{-- Hiển thị các trang trước trang hiện tại --}}
            @for ($i = 2; $i < $vouchers->currentPage(); $i++)
                <li class="page-item">
                    <a class="page-link" href="{{ $vouchers->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Trang hiện tại --}}
            <li class="page-item active">
                <a class="page-link" href="#">{{ $vouchers->currentPage() }}</a>
            </li>

            {{-- Hiển thị các trang sau trang hiện tại --}}
            @for ($i = $vouchers->currentPage() + 1; $i <= $vouchers->lastPage(); $i++)
                <li class="page-item">
                    <a class="page-link" href="{{ $vouchers->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </div>
    <script>
        function confirmDelete(event) {
            if (!confirm('Are you sure?')) {
                event.preventDefault();
            }
        }
    </script>
@endsection
