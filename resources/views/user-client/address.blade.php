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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <div class="container my-5 utB99K">
        <div class="row">
            <div class="col-md-4">
                <!-- Sidebar -->
                <div class="sidebar bg-light p-4 rounded">
                    <h2 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Tài khoản </h2>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route('user.index') }}" class="text-decoration-none">Thông tin
                                tài khoản</a></li>
                        <li class="list-group-item"><a href="{{ route('orders.user') }}" class="text-decoration-none">Đơn
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
                    <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Địa chỉ </h1>
                    <p class="text-muted">Thêm địa chỉ để thuận tiện hơn</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

                <div class="RCnc9v bg-white p-4 rounded shadow">
                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="address" class="form-label">Số nhà/Đường</label>
                            <input type="text" class="form-control" name="address" id="address" required>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ward" class="form-label">Phường/Xã</label>
                            <input type="text" class="form-control" name="ward" id="ward" required>
                            @error('ward')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="district" class="form-label">Quận/Huyện</label>
                            <input type="text" class="form-control" name="district" id="district" required>
                            @error('district')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Thành phố/Tỉnh</label>
                            <input type="text" class="form-control" name="city" id="city" required>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Lưu địa chỉ</button>
                    </form>
                </div> <br>
                <h2 class="h5">Danh sách địa chỉ của tôi</h2>
                <ul class="list-group">
                    @foreach($addresses as $address)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Số nhà {{ $address->address }}, Xã {{ $address->ward }}, Huyện {{ $address->district }}, Thành phố {{ $address->city }}
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-dark p-0" title="Xóa">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>


            </div>
        </div>
    </div>
@endsection
