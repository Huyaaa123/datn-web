@extends('layouts.master')

@section('content')
<style>
    .product-name {
    color: black; /* Màu đen cho tên sản phẩm */
}

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<main role="main">
    <div class="container mt-4">
        <h1 class="text-center my-4" style="color: #000000; font-size:32px;">Thanh Toán</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="border border-3 border-primary rounded p-4 shadow-lg" style="background-color: #f9f9f9;">
            <!-- Thông báo về địa chỉ -->
            @if (!$address)
                <div class="alert alert-warning">
                    Bạn chưa có địa chỉ nào. Vui lòng thêm <a href="{{route('addresses.index')}}">địa chỉ</a> để dễ dàng thanh toán hơn.
                </div>
            @else
                <div class="alert alert-info">
                    Đã nhập nhanh thông tin đã lưu.
                </div>
            @endif

            <form id="paymentForm" method="POST" action="{{ route('checkout.online_checkout') }}">
                @csrf
                <div class="row">
                    {{-- co --}}
                    <div class="col-md-8 order-md-2 mb-4">
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="name" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" required value="{{ $user->name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control form-control-sm" id="phone" name="phone" required value="{{ $user->phone ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <label for="city" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Thành phố</label>
                                <select id="province" name="city" class="form-control" required onchange="loadDistricts()">
                                    <option value="{{ $address->city ?? 'Chọn thành phố' }}">{{ $address->city ?? 'Chọn thành phố' }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="district" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Quận huyện</label>
                                <select id="district" name="district" class="form-control" required onchange="loadWards()">
                                    <option value="{{ $address->district ?? 'Chọn quận/huyện' }}">{{ $address->district ?? 'Chọn quận/huyện' }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="ward" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Phường xã</label>
                                <select id="ward" name="ward" required class="form-control">
                                    <option value="{{ $address->ward ?? 'Chọn phường/xã' }}">{{ $address->ward ?? 'Chọn phường/xã' }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Số nhà, tên đường</label>
                            <input type="text" class="form-control form-control-sm" id="address" name="address" required value="{{ $address->address ?? '' }}">
                        </div>

                        <div class="text-center my-3">
                            <button type="submit" name="cod" value="cod" class="btn border">
                                <i class="fas fa-money-bill-wave"></i> Thanh toán COD
                            </button>
                            <button type="submit" name="payUrl" value="momo" class="btn border">
                                <i class="fab fa-cc-mastercard"></i> Thanh toán Momo
                            </button>
                            <button type="submit" name="vnpay" value="vnpay" class="btn border">
                                <i class="fab fa-cc-visa"></i> Thanh toán VNPay
                            </button>
                        </div>
                    </div>
                    {{-- ord --}}
                    <div class="col-md-4 order-md-2 mb-4">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: #000000; font-size:24px;">Đơn hàng</span>
                            <span style="color: #5e5e5e; font-size:14px;">{{ count($cart) }} sản phẩm</span>
                        </h4>
                        @foreach ($cart as $product)
                            <div class="row border-bottom py-3 align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" style="width: 55px; height: auto;">
                                </div>
                                <div class="col-md-8">
                                    <p class="product-name" style="font-size:14px; font-weight: bold;">{{ $product['name'] }}</p> <!-- Sử dụng lớp CSS cho tên sản phẩm -->
                                    <span style=" color:black;">
                                        {{ number_format($product['price'] ) }}đ
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span style=" color:black; position: relative; bottom:-20px; ">
                                        x{{ $product['quantity'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        <div class="total my-4 d-flex justify-content-between">
                            <span class="total-label" style="color:rgb(37, 36, 36); font-weight: bold">Tổng cộng:</span>
                            <h4 style="color:#990000; font-weight: bold font-size:16px;" class="text-right">
                                {{ number_format(array_sum(array_map(function ($product) {
                                    return $product['quantity'] * $product['price'];
                                }, $cart))) }} đ
                            </h4>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <br>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</main>
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
            const provinceCode = this.selectedOptions[0].getAttribute('data-code'); // Lấy mã code từ attribute
            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(response => response.json())
                .then(data => {
                    const districtSelect = document.getElementById("district");
                    districtSelect.innerHTML = ""; // Xóa các tùy chọn quận/huyện cũ
                    const wardSelect = document.getElementById("ward");
                    wardSelect.innerHTML = "<option value=''>Chọn Phường/Xã</option>"; // Đặt lại phường/xã

                    data.districts.forEach(district => {
                        const option = document.createElement("option");
                        option.value = district.name; // Sử dụng tên quận/huyện làm giá trị
                        option.textContent = district.name;
                        option.setAttribute('data-code', district.code); // Lưu mã code trong attribute
                        districtSelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Lỗi khi tải danh sách quận/huyện:", error));
        });

        // Lấy phường/xã khi chọn quận/huyện
        document.getElementById("district").addEventListener("change", function() {
            const districtCode = this.selectedOptions[0].getAttribute('data-code'); // Lấy mã code từ attribute
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
