@extends('admin.layouts.master')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <h1>Đánh giá</h1>
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

    <body>
        <form action="{{ route('admin.comments.index') }}" method="GET" class="form-group search-form">
            <input type="text" name="search" class="form-control" placeholder="Nhập từ khóa tìm kiếm..."
                value="{{ $search ?? '' }}">

            <select name="rating" class="form-select me-2 w-25" onchange="this.form.submit()">
                <option value="">Đánh giá</option>
                <option value="1" {{ $rating == '1' ? 'selected' : '' }}>1 sao</option>
                <option value="2" {{ $rating == '2' ? 'selected' : '' }}>2 sao</option>
                <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3 sao</option>
                <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4 sao</option>
                <option value="5" {{ $rating == '5' ? 'selected' : '' }}>5 sao</option>
            </select>

            <select name="order_by" class="form-select me-2 w-25" onchange="this.form.submit()">
                <option value="">Sắp xếp</option>
                <option value="latest" {{ $order_by == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="oldest" {{ $order_by == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
            </select>
        </form>


        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Người đánh giá</th>
                    <th>Đánh giá</th>
                    <th>Nội dung</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $index => $comment)
                    <tr>
                        <td style="color:black;">{{ $index + 1 }}</td>
                        <td style="color:black;">{{ $comment->product->name }}</td>
                        <td style="color:black;">{{ $comment->user->name }}</td>
                        <td >
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color: {{ $i <= $comment->rating ? 'gold' : 'gray' }};"></i>
                            @endfor
                        </td>

                        <td style="color:black;">{{ Str::limit($comment->content, 15, '...') }}</td>
                        <td>
                            <a href="{{ route('admin.comments.show', $comment->id) }}" class="btn btn-success">Show</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endsection
