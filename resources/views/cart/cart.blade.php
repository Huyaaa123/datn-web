@extends('layouts.master')

@section('content')
<style>
    .btn-no-hover:hover {
    background-color: transparent; /* Hoặc một màu sắc bạn muốn */
    color: inherit; /* Để không thay đổi màu chữ */
    border: none; /* Để không có viền */
}

</style>
<br>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container" style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f8f9fa;">
    <h1 class="text-center" style="color:black; font-weight:bold;">Giỏ Hàng</h1>

    @if($cart && count($cart) > 0)
        <table class="table table-bordered" style="background:#FFF; font-size:14px; margin-top: 20px;">
            <thead class="thead-light">
                <tr>
                    <th>Hình</th>
                    <th>Thông tin sản phẩm</th>
                    <th style="width:120px;">Số lượng</th>
                    <th style="width:100px;">Đơn giá</th>
                    <th style="width:100px;">Tổng</th>
                    <th style="width:70px;">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $product)
                    <tr>
                        <td>
                            <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" style="width: 60px; height: auto;">
                        </td>
                        <td>
                            <strong>{{ $product['name'] }}</strong>
                        </td>
                        <td align="center">
                            <div class="d-flex align-items-center justify-content-center">
                                <form action="{{ route('cart.decrease') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <button type="submit" class="btn btn-light btn-no-hover"><i class="fas fa-minus"></i></button>
                                </form>

                                <span class="mx-2">{{ $product['quantity'] }}</span> <!-- Sử dụng <span> để căn chỉnh tốt hơn -->

                                <form action="{{ route('cart.increase') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <button type="submit" class="btn btn-light btn-no-hover"><i class="fas fa-plus"></i></button>
                                </form>
                            </div>
                        </td>


                        <td align="right">
                            {{ number_format($product['price']) }} VND
                        </td>
                        <td align="right">
                            {{ number_format($product['quantity'] * $product['price']) }} VND
                        </td>
                        <td align="center">
                            <form action="{{ route('cart.remove') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit" class="btn-danger ">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <fieldset style="margin-bottom: 20px;">
            <legend>Tổng:</legend>
            <div class="row">
                <div class="col-9">Số tiền mua sản phẩm:</div>
                <div class="col-3 text-right">{{ number_format(array_sum(array_map(function($product) { return $product['quantity'] * $product['price']; }, $cart))) }} VND</div>
            </div>

            <div class="row">
                <div class="col-9">Phí vận chuyển:</div>
                <div class="col-3 text-right">0 VND</div>
            </div>

            <div class="row font-weight-bold" style="font-size: larger; color: #b31f2a;">
                <div class="col-9">Tổng tiền thanh toán:</div>
                <div class="col-3 text-right">{{ number_format(array_sum(array_map(function($product) { return $product['quantity'] * $product['price']; }, $cart))) }} VND</div>
            </div>
        </fieldset>

        <div class="text-center">
            <a href="{{ route('checkout.index') }}" class="btn btn-success">Thanh toán</a>
        </div>
    @else
        <p class="text-center">Giỏ hàng của bạn đang trống!</p>
    @endif
</div>
<br>
@endsection
