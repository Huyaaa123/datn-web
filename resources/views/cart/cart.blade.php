@extends('layouts.master')

@section('content')
<style>
@media (min-width: 1025px) {
    .h-custom {
        height: auto !important; /* Thay đổi từ height: 100vh thành height: auto */
    }
}

.card-registration .select-input.form-control[readonly]:not([disabled]) {
    font-size: 1rem;
    line-height: 2.15;
    padding-left: .75em;
    padding-right: .75em;
}

.card-registration .select-arrow {
    top: 13px;
}


</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@if($cart && count($cart) > 0)
    <section class="h-100 h-custom" style="background-color: #d2c9ff;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                    <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-8">
                                    <div class="p-5">
                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <h1 class="fw-bold mb-0">Giỏ Hàng</h1>
                                            <h6 class="mb-0 text-muted">{{ count($cart) }} sản phẩm</h6>
                                        </div>
                                        <hr class="my-4">

                                        @foreach($cart as $id => $product)
                                            <div class="row mb-4 d-flex justify-content-between align-items-center">
                                                <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" style="width: 80px; height: auto;">
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-3">
                                                    <h6 class="text-muted">{{ $product['name'] }}</h6>
                                                    <h6 class="mb-0">Dm</h6>
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                    <form action="{{ route('cart.decrease') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                                        <button type="submit" class="btn btn-link px-2"><i class="fas fa-minus"></i></button>
                                                    </form>

                                                    <span class="form-control form-control-sm">{{ $product['quantity'] }}</span>

                                                    <form action="{{ route('cart.increase') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                                        <button type="submit" class="btn btn-link px-2"><i class="fas fa-plus"></i></button>
                                                    </form>
                                                </div>
                                                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                    <h6 class="mb-0">{{ number_format($product['price']) }} VND</h6>
                                                </div>
                                                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                    <form action="{{ route('cart.remove') }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                                        <button type="submit" class="btn btn-link text-muted"><i class="fas fa-times"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                        @endforeach
                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 class="text-uppercase"></h5>
                                            <h5 class="text-danger">{{ number_format(array_sum(array_map(function($product) { return $product['quantity'] * $product['price']; }, $cart))) }} VND</h5>
                                        </div>
                                        <div class="pt-5">
                                            <h6 class="mb-0"><a href="/" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Trở lại cửa hàng</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 bg-body-tertiary">
                                    <div class="p-5">
                                        <h3 class="fw-bold mb-5 mt-2 pt-1">Tóm tắt</h3>


                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 >Vận chuyển</h5>
                                            <p>Miễn phí</p>
                                        </div>

                                        <h5 class="">Nhập mã giảm giá</h5>
                                        <div class="mb-5">
                                            <div class="form-outline">
                                                <input type="text" id="form3Examplea2" class="form-control form-control-lg" />
                                                <label class="form-label" for="form3Examplea2">Nhập mã của bạn</label>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-5">
                                            <h5 >Giá</h5>
                                            <h5 class="text-danger">{{ number_format(array_sum(array_map(function($product) { return $product['quantity'] * $product['price']; }, $cart))) }} VND</h5>
                                        </div>

                                        <a href="{{ route('checkout.index') }}" class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">Thanh toán</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <p class="text-center">Giỏ hàng của bạn đang trống!</p>
@endif


@endsection
