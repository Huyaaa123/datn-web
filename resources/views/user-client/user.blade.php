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
                        <!-- Bạn có thể thêm các mục khác nếu cần -->
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="SFztPl mb-4">
                    <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Thông tin tài khoản</h1>
                    <p class="text-muted">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="RCnc9v bg-white p-4 rounded shadow">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold"><label>Tên</label></td>
                                <td>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" placeholder="Nhập tên của bạn">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><label>Email</label></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $user->email }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><label>Số điện thoại</label></td>
                                <td>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại của bạn">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><label>Giới tính</label></td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                            value="male" {{ $user->gender == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                            value="female" {{ $user->gender == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">Nữ</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other"
                                            value="other" {{ $user->gender == 'other' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">Khác</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><label>Ngày sinh</label></td>
                                <td>
                                    <input type="date" name="birth_date" class="form-control"
                                        value="{{ old('birth_date', $user->birth_date) }}">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete(event)">Xóa Tài
                                        Khoản</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <form action="{{ route('user.destroy', $user->id) }}" method="POST" id="delete-account-form" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
        <script>
            function confirmDelete(event) {
                if (confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')) {
                    // Nếu xác nhận, thực hiện gửi form
                    document.getElementById('delete-account-form').submit();
                }
            }
        </script>
    </div>
@endsection
