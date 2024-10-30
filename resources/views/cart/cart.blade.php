@extends('layouts.master')

@section('content') <br>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container" style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f8f9fa;">
    <h1 style="color:black; font-weight:bold; text-align: center;">Giỏ Hàng</h1>

    @if($cart && count($cart) > 0)
        @foreach($cart as $id => $product)
            <div class="product-item" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom: 10px; background-color: #ffffff;">
                <div class="row align-items-center">
                    <div class="col-auto text-start" style="padding-right: 10px;">
                        <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['name'] }}" style="width: 50px; height: auto;">
                    </div>
                    <div class="col text-start" style="display: flex; flex-direction: column; justify-content: center; position: relative;">
                        <div style="margin: 0;">{{ $product['name'] }}</div>
                        <div style="margin: 0;">{{ number_format($product['price']) }} VND</div>
                        <div style="margin: 0;">Số lượng: {{ $product['quantity'] }}</div>
                    </div>
                    <div class="col-auto text-center">
                        <form action="{{ route('cart.remove') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button type="submit" class="btn btn-danger" >Xóa</button> <!-- Nút ẩn, chỉ để gửi form -->
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="total" style="text-align: right; padding: 10px 0;">
            <h4>Tổng :
                {{ number_format(array_sum(array_map(function($product) {
                    return $product['quantity'] * $product['price'];
                }, $cart))) }} VND
            </h4>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('checkout.index')}}" class="btn btn-success">Thanh toán</a>
        </div>
    @else
        <p>Giỏ hàng của bạn đang trống!</p>
    @endif
</div>
<br>
@endsection
