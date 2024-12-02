@extends('layouts.master')

@section('content')
    <style>
        @media (min-width: 1025px) {
            .h-custom {
                height: auto !important;
                /* Thay đổi từ height: 100vh thành height: auto */
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

        .block-4-image {
            position: relative;
            /* Để overlay nằm chồng lên ảnh */
            overflow: hidden;
            /* Để ẩn phần overlay khi nó nằm ngoài khối */
        }

        .product-image {
            width: 50%;
            transition: transform 0.3s ease;
            /* Hiệu ứng mờ dần khi hover */
        }
        /* Hiển thị overlay khi hover */
        .block-4-image:hover .overlay {
            bottom: 0;
            /* Khi hover, overlay sẽ từ từ di chuyển từ dưới lên */
        }

        .block-4-image:hover .product-image {
            transform: scale(1.1);
            /* Tăng kích thước ảnh một chút khi hover */
        }

        .d-flex {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .border {
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .form-control.form-control-sm {
            width: 50px;
            height: 35px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-link {
            padding: 0;
            font-size: 18px;
            color: #007bff;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            /* Bỏ gạch chân */
        }

        .btn-link:hover {
            color: inherit;
            /* Bỏ hiệu ứng hover */
        }

        .btn-link:focus {
            outline: none;
            border: none;
        }

        .btn-link i {
            font-size: 20px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @if ($cart && count($cart) > 0)
        <section class="h-100 h-custom" style="background-color: #d2c9ff;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12">
                        <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-lg-8">
                                        <div class="p-5">
                                            @if (session('error'))
                                                <div class="alert alert-danger">{{ session('error') }}</div>
                                            @endif

                                            @if (session('success'))
                                                <div class="alert alert-success">{{ session('success') }}</div>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mb-5">
                                                <h1 class="fw-bold mb-0" style="font-size: 24px;">Giỏ Hàng</h1>
                                                <h6 class="mb-0 ">{{ count($cart) }} sản phẩm</h6>
                                            </div>
                                            <hr class="my-4">

                                            @foreach ($cart as $id => $product)
                                                <div class="row mb-4 d-flex justify-content-between align-items-center">
                                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                                        <img src="{{ Storage::url($product['image']) }}"
                                                            alt="{{ $product['name'] }}" style="width: 80px; height: auto;">
                                                    </div>
                                                    <div class="col-md-4 col-lg-4 col-xl-4">
                                                        <h6 style="font-size: 14px; font-weight:500; color:black;" class=" mb-1">
                                                            {{ $product['name'] }}</h6>
                                                        @php
                                                            $color = \DB::table('colors')
                                                                ->where('id', $product['color_id'])
                                                                ->first();
                                                            $size = \DB::table('sizes')
                                                                ->where('id', $product['size_id'])
                                                                ->first();
                                                        @endphp

                                                        <p class="mb-1">{{ $size->name ?? 'Màu không xác định' }},
                                                            {{ $color->name ?? 'Kích thước không xác định' }}</p>
                                                        <p class="mb-0">{{ number_format($product['price']) }}₫</p>
                                                    </div>

                                                    <div
                                                        class="col-md-2 col-lg-2 col-xl-2 d-flex items-center border border-gray-300 rounded-lg">
                                                        <!-- Giảm số lượng -->
                                                        <form action="{{ route('cart.tru') }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $id }}">
                                                            <button type="submit"
                                                                class="btn btn-link px-2 border-0 text-primary">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </form>
                                                        <!-- Số lượng -->
                                                        <span
                                                            class="form-control form-control-sm px">{{ $product['quantity'] }}</span>

                                                        <!-- Tăng số lượng -->
                                                        <form action="{{ route('cart.cong') }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $id }}">
                                                            <button type="submit"
                                                                class="btn btn-link px-2 border-0 text-primary">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <div class="col-md-2 col-lg-2 col-xl-2 text-end">
                                                        <!-- Giảm độ rộng cho cột giá -->
                                                        <h6 style="font-size: 15px; line-height: 1.5; white-space: nowrap; text-align: right;"
                                                            class="mb-0">
                                                            {{ number_format($product['quantity'] * $product['price'], 0, ',', '.') }}₫
                                                        </h6>
                                                    </div>

                                                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                        <form action="{{ route('cart.remove') }}" method="POST"
                                                            style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $id }}">
                                                            <button type="submit" class="btn btn-link text-muted"><i
                                                                    class="fas fa-times"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <hr class="my-4">
                                            @endforeach

                                            <div class="pt-5">
                                                <h6 class="mb-0"><a href="/" class="text-body"><i
                                                            class="fas fa-long-arrow-alt-left me-2"></i>Trở lại cửa hàng</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 bg-body-tertiary">
                                        <div class="p-5">
                                            <h3 class="fw-bold mb-5 mt-2 pt-1" style="font-size: 24px;">Tóm tắt</h3>


                                            <div class="d-flex justify-content-between mb-4">
                                                <h5 style="font-size: 16px;">Phí vận chuyển</h5>
                                                <p >Miễn phí</p>
                                            </div>
                                            @if (session()->has('voucher_code'))
                                                <div
                                                    style="display: flex; justify-content: space-between; align-items: baseline;">
                                                    <h5 style="font-size: 16px;">Voucher</h5>
                                                    <a href="#" data-toggle="modal" data-target="#exampleModal">
                                                        <span style="color:black;">{{ session('voucher_code') }} >></span>
                                                    </a>
                                                </div>
                                            @else
                                                <div style="display: flex; align-items: baseline;">
                                                    <h5 style="font-size: 16px; margin-right: 150px;">Voucher</h5>
                                                    <a href="#" data-toggle="modal" data-target="#exampleModal">

                                                        <span style="color:black;">Chọn</span>
                                                    </a>
                                                </div>
                                            @endif
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Chọn Voucher</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Form để áp dụng voucher -->
                                                            <form id="applyVoucherForm" action="{{ route('cart.applyVoucher') }}" method="POST">
                                                                @csrf
                                                                <!-- Danh sách voucher radio -->
                                                                <div class="voucher-list" style="max-height: 300px; overflow-y: auto;">
                                                                    @foreach ($vouchers as $voucher)
                                                                        <div style="display: flex; align-items: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px;">
                                                                            <img src="https://down-vn.img.susercontent.com/file/aa73f8aa302834aa9fc6adbf6e704cf2" alt="Voucher Logo" style="width: 80px; height: auto;">
                                                                            <div style="flex-grow: 1; padding-left: 10px;">
                                                                                <div style="font-weight: bold;">
                                                                                    {{ $voucher->code }} -
                                                                                    @if ($voucher->discount_percent)
                                                                                        Giảm {{ $voucher->discount_percent }}%
                                                                                        @if ($voucher->max_discount_amount)
                                                                                            (Tối đa: {{ number_format($voucher->max_discount_amount) }}₫)
                                                                                        @endif
                                                                                    @elseif($voucher->discount_amount)
                                                                                        Giảm {{ number_format($voucher->discount_amount) }}₫
                                                                                    @endif
                                                                                </div>
                                                                                <div style="color: #555; font-size: 14px;">
                                                                                    <p style=" color:black;margin: 0;">Đơn Tối Thiểu: {{ number_format($voucher->min_order_value) }}₫</p>
                                                                                    <p style="color:black; margin: 0;">Hạn sử dụng: {{ \Carbon\Carbon::parse($voucher->end_date)->format('H:i:s d/m/Y') }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <input type="radio" name="voucher_id" {{ session('voucher_id') == $voucher->id ? 'checked' : '' }} value="{{ $voucher->id }}" style="margin-left: auto;">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('cart.removeVoucher') }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-secondary">Không
                                                                    dùng </button>
                                                            </form>
                                                            <!-- Nút OK để submit form -->
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="document.getElementById('applyVoucherForm').submit();">OK</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <br>

                                            @if (session()->has('discount_amount') && session('discount_amount') > 0)
                                                <div class="d-flex justify-content-between mb-4">
                                                    <h5 style="font-size: 16px;">Đã giảm</h5>
                                                    <h5 style="color: green; font-size: 16px;">
                                                        -{{ number_format(session('discount_amount')) }}₫
                                                    </h5>
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between mb-5">
                                                <h5 style="font-size: 16px;">Tổng thanh toán</h5>
                                                <h5 class="text-danger" style="font-size: 20px; color:#990000;">
                                                    {{ number_format(array_sum(array_map(function ($product) {return $product['quantity'] * $product['price'];}, $cart))) }}₫</h5>
                                            </div>

                                            <a href="{{ route('checkout.index') }}" class="btn btn-dark btn-block btn-lg"
                                                data-mdb-ripple-color="dark">Thanh toán</a>
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
        <div class="site-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="https://salanest.com/img/empty-cart.webp" width="25%" height="auto">
                    </div>
                </div> <br>
                <div class="site-section-heading pt-4">
                    <h4>Có thể bạn cũng thích</h4>
                </div> <br>
                <div class="row">
                    @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm rounded border-2">
                            <figure class="block-4-image mb-0">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                    class="card-img-top" style="height: 200px; object-fit: cover; border-bottom: 2px solid #eee;">
                            </figure>
                            <div class="card-body text-center" style="padding-top: 10px;">
                                <h5 class="card-title" style="font-size: 16px; font-weight: 500; color: #333; margin-top: 5px;">
                                    <a href="{{ route('product.show', $product->slug) }}" style="font-size: 16px; font-weight: bold; color: rgb(0, 0, 0); text-decoration: none;">
                                        {{ $product->name }} ({{ $product->sku }})
                                    </a>
                                </h5>
                                <p class="product-category text-muted" style="font-weight: 500; font-size: 14px; color: #777; margin-top: -5px;">
                                    {{ $product->category->name }}
                                </p>
                                <p class="card-text" style="font-weight: 500; color: rgb(144, 29, 29); font-size: 18px; margin-top: 5px;">
                                    {{ number_format($product->price) }}₫
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.overlay a').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Kiểm tra đăng nhập
                        @if (auth()->check())
                            var productId = this.getAttribute('data-product-id');
                            document.getElementById('modal_product_id').value = productId;
                            $('#quantityModal').modal('show');
                        @else
                            // Chưa đăng nhập: chuyển hướng đến trang login
                            window.location.href = "{{ route('login') }}";
                        @endif
                    });
                });

                document.getElementById('confirm-add-to-cart').addEventListener('click', function() {
                    document.getElementById('add-to-cart-form').submit();
                });
            });

            // Xử lý tăng giảm số lượng
            document.getElementById('cong').addEventListener('click', function() {
                let quantityInput = document.getElementById('quantity');
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity < parseInt(quantityInput.max)) {
                    quantityInput.value = currentQuantity + 1;
                }
            });

            document.getElementById('tru').addEventListener('click', function() {
                let quantityInput = document.getElementById('quantity');
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > parseInt(quantityInput.min)) {
                    quantityInput.value = currentQuantity - 1;
                }
            });
        </script>
    @endif


@endsection
