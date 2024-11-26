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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="container my-5 utB99K">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar -->
                <div class="sidebar bg-light p-2 rounded">
                    <h2 class="h5" style="color:rgb(37, 36, 36);"> <i class="fa-regular fa-user"></i> Quản lý tài khoản </h2>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route('user.index') }}" class="text-decoration-none">Thông
                                tin
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
            <div class="col-md-9">
                <div class="SFztPl mb-4">
                    <h1 class="h4" style="color:rgb(37, 36, 36); font-weight: bold">Địa chỉ </h1>
                    <p class="text-muted">Thêm địa chỉ để thuận tiện hơn</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif


                <div class="RCnc9v bg-white p-4 rounded shadow">
                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="province" class="form-label">Thành phố/Tỉnh</label>
                            <select id="province" name="city" class="form-control" onchange="loadDistricts()">
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="district" class="form-label">Quận/Huyện</label>
                            <select id="district" name="district" class="form-control" onchange="loadWards()">
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                            @error('district')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ward" class="form-label">Phường/Xã</label>
                            <select id="ward" name="ward" class="form-control">
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                            @error('ward')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Số nhà/Đường</label>
                            <input type="text" class="form-control" name="address" id="address" >
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Lưu địa chỉ</button>
                    </form>
                </div> <br>
                <h2 class="h5">Danh sách địa chỉ của tôi</h2>
                <ul class="list-group">
                    @foreach ($addresses as $address)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Số nhà {{ $address->address }}, {{ $address->ward }}, {{ $address->district }},
                             {{ $address->city }}
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?');">
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy danh sách tỉnh/thành phố
            fetch('https://provinces.open-api.vn/api/?depth=1')
                .then(response => response.json())
                .then(provinces => {
                    const provinceSelect = document.getElementById("province");
                    provinces.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.name; // Sử dụng tên tỉnh làm giá trị
                        option.textContent = province.name;
                        option.setAttribute('data-code', province.code); // Lưu mã code trong attribute
                        provinceSelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Lỗi khi tải danh sách tỉnh/thành phố:", error));

            // Lấy quận/huyện khi chọn tỉnh/thành phố
            document.getElementById("province").addEventListener("change", function() {
                const provinceCode = this.selectedOptions[0].getAttribute(
                'data-code'); // Lấy mã code từ attribute
                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                    .then(response => response.json())
                    .then(data => {
                        const districtSelect = document.getElementById("district");
                        districtSelect.innerHTML = ""; // Xóa các tùy chọn quận/huyện cũ
                        const wardSelect = document.getElementById("ward");
                        wardSelect.innerHTML =
                        "<option value=''>Chọn Phường/Xã</option>"; // Đặt lại phường/xã

                        data.districts.forEach(district => {
                            const option = document.createElement("option");
                            option.value = district.name; // Sử dụng tên quận/huyện làm giá trị
                            option.textContent = district.name;
                            option.setAttribute('data-code', district
                            .code); // Lưu mã code trong attribute
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error("Lỗi khi tải danh sách quận/huyện:", error));
            });

            // Lấy phường/xã khi chọn quận/huyện
            document.getElementById("district").addEventListener("change", function() {
                const districtCode = this.selectedOptions[0].getAttribute(
                'data-code'); // Lấy mã code từ attribute
                fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                    .then(response => response.json())
                    .then(data => {
                        const wardSelect = document.getElementById("ward");
                        wardSelect.innerHTML = ""; // Xóa các tùy chọn phường/xã cũ
                        data.wards.forEach(ward => {
                            const option = document.createElement("option");
                            option.value = ward.name; // Sử dụng tên phường/xã làm giá trị
                            option.textContent = ward.name;
                            wardSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error("Lỗi khi tải danh sách phường/xã:", error));
            });
        });
    </script>
@endsection
