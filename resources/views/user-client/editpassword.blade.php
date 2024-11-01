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
                        <!-- Bạn có thể thêm các mục khác nếu cần -->
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="SFztPl mb-4">
                    <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Đổi mật khẩu</h1>
                    <p class="text-muted">Đổi mật khẩu nếu như mật khẩu của bạn đã bị lộ</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="RCnc9v bg-white p-4 rounded shadow">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu cũ</label>
                            <input type="password" name="current_password" id="current_password" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
