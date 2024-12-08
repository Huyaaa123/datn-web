@extends('admin.layouts.master')
@section('content')
    <h1>Mã giảm giá</h1>
    <style>
        /* Modal Container */
        .modal {
            display: none;
            /* Mặc định ẩn */
            position: fixed;
            z-index: 1;
            /* Đảm bảo modal hiển thị trên tất cả các phần tử khác */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* Màu nền mờ phía sau */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 60%;
            /* Điều chỉnh kích thước modal nhỏ hơn */
            max-width: 500px;
            /* Điều chỉnh kích thước tối đa nhỏ hơn */
        }

        /* Modal Header */
        .modal-header {
            font-size: 20px;
            font-weight: bold;
            color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        /* Modal Body */
        .modal-body {
            font-size: 14px;
            margin: 10px 0;
        }

        .modal-body p {
            margin-bottom: 10px;
            /* Tăng khoảng cách giữa các người dùng */
        }

        /* Modal Footer */
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding-top: 10px;
        }


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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <a href="{{ route('admin.vouchers.create') }}" class="text-center btn btn-add">Thêm</a>
    <form action="{{ route('admin.vouchers.index') }}" method="GET" class="form-group search-form">
        <input type="text" name="search" class="form-control mr-2" placeholder="Nhập từ khóa tìm kiếm..."
            value="{{ request('search') }}">

        <select name="status" class="form-select mr-2 w-25" onchange="this.form.submit()">
            <option value="">Trạng thái</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Còn hạn</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Hết hạn</option>
        </select>

    </form>

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
                <th>Giảm tối đa</th>
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
                    <td style="color:black;">{{ $voucher->code }}</td>
                    <td style="color:black;">
                        @if ($voucher->discount_amount)
                            {{ number_format($voucher->discount_amount) }}₫
                        @elseif ($voucher->discount_percent)
                            {{ number_format($voucher->discount_percent) }}%
                        @endif
                    </td>

                    <td style="color:black;">{{ number_format($voucher->min_order_value) }}₫</td>
                    <td style="color:black;">
                        @if ($voucher->discount_type === 'percent' && $voucher->max_discount_amount)
                            {{ number_format($voucher->max_discount_amount) }}₫
                        @else
                            ___
                        @endif
                    </td>

                    <td style="color:black;">{{ $voucher->used }}
                        <a href="#" data-bs-toggle="modal" data-bs-target="#usedUsersModal{{ $voucher->id }}">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        <div class="modal fade" id="usedUsersModal{{ $voucher->id }}" tabindex="-1"
                            aria-labelledby="usedUsersModalLabel{{ $voucher->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="usedUsersModalLabel{{ $voucher->id }}">Người đã sử
                                            dụng Voucher: {{ $voucher->name }}</h5>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Liệt kê người dùng đã sử dụng voucher -->
                                        @foreach ($voucher->voucherDetail as $voucherDetails)
                                            @if ($voucherDetails->user)
                                                <p style="color: black;">
                                                    (#{{ $voucherDetails->user->id }}) {{ $voucherDetails->user->name }} - Đã sử dụng lúc:
                                                    {{ $voucherDetails->created_at ? $voucherDetails->created_at->format('H:i:s d/m/Y ') : 'Chưa có thời gian' }}
                                                </p>
                                            @endif
                                        @endforeach

                                        @if ($voucher->voucherDetail->isEmpty())
                                            <p>Chưa có người dùng nào sử dụng voucher này.</p>
                                        @endif
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="color:black;">{{ $voucher->usage_limit }}</td>
                    <td style="color:black;">{{ \Carbon\Carbon::parse($voucher->start_date)->format('H:i:s d/m/Y ') }}</td>
                    <td style="color:black;">{{ \Carbon\Carbon::parse($voucher->end_date)->format('H:i:s d/m/Y ') }}</td>
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
            if (!confirm('Bạn chắc chứ?')) {
                event.preventDefault();
            }
        }
    </script>

    <script>
        // Mở modal
        document.querySelectorAll('.open-modal').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                const modalId = this.getAttribute('data-modal-id');
                document.getElementById(modalId).style.display = 'block';
            });
        });

        // Đóng modal khi nhấn vào dấu x hoặc nút đóng
        document.querySelectorAll('.close-btn').forEach(item => {
            item.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal-id');
                document.getElementById(modalId).style.display = 'none';
            });
        });

        // Đóng modal khi click ra ngoài modal
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
@endsection
