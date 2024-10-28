@extends('layouts.master')

@section('content')
    <div class="container">
        <h1 class="text-center my-4">Thanh Toán</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="border border-3 border-primary rounded p-4 shadow-lg" style="background-color: #f9f9f9;">
            <form id="paymentForm" method="POST" action="{{ route('checkout.online_checkout') }}">
                @csrf

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="name" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control form-control-sm" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-4">
                        <label for="city" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Thành phố</label>
                        <input type="text" class="form-control form-control-sm" id="city" name="city" required>
                    </div>
                    <div class="col-md-4">
                        <label for="district" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Quận huyện</label>
                        <input type="text" class="form-control form-control-sm" id="district" name="district" required>
                    </div>
                    <div class="col-md-4">
                        <label for="ward" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Phường xã</label>
                        <input type="text" class="form-control form-control-sm" id="ward" name="ward" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" style="color:rgb(37, 36, 36); font-weight: bold" class="form-label">Số nhà, tên đường</label>
                    <input type="text" class="form-control form-control-sm" id="address" name="address" required>
                </div>

                <h4 style="color:rgb(37, 36, 36); font-weight: bold" class="my-4">Chi tiết đơn hàng</h4>
                @foreach ($cart as $product)
                    <div class="row border-bottom py-3 align-items-center">
                        <div class="col-md-2">
                            <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}"
                                style="width: 75px; height: auto;">
                        </div>
                        <div class="col-md-6">
                            <p>{{ $product['name'] }}</p>
                            <p>Số lượng: {{ $product['quantity'] }}</p>
                        </div>
                    </div>
                @endforeach

                <div class="total my-4">
                    <h4 style="color:rgb(37, 36, 36); font-weight: bold" class="text-right">Tổng giá trị:
                        {{ number_format(
                            array_sum(
                                array_map(function ($product) {
                                    return $product['quantity'] * $product['price'];
                                }, $cart),
                            ),
                        ) }}
                        VND
                    </h4>
                </div>

                <div class="text-center my-3">
                    <button type="submit" name="cod" value="cod" class="btn btn-success">Thanh toán cod</button>
                    <button type="submit" name="payUrl" value="momo" class="btn btn-success">Thanh toán Momo</button>
                    <button type="submit" name="vnpay" value="vnpay" class="btn btn-success">Thanh toán vnpay</button>
                </div>
            </form>
        </div> <br>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@endsection
