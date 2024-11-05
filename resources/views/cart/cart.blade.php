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

        /* Định nghĩa vị trí ban đầu của overlay */
        .overlay {
            position: absolute;
            bottom: -100%;
            /* Ẩn overlay hoàn toàn bên dưới khối */
            left: 0;
            right: 0;
            background-color: rgba(49, 47, 47, 0.7);
            /* Nền tối với độ trong suốt */
            color: white;
            text-align: center;
            padding: 20px;
            transition: all 0.5s ease;
            /* Hiệu ứng di chuyển */
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

        .overlay a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        a:hover {
            color: white;
            /* Giữ nguyên màu trắng khi hover */
            text-decoration: none;
            /* Không underline hoặc bất kỳ hiệu ứng hover nào khác */
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
                    <div class="card" style="width: 100%;">
                        <figure class="block-4-image">
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="overlay">
                                <a href="" data-product-id="{{ $product->id }}"><i
                                        class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ hàng</a>
                            </div>
                        </figure>
                        <div class="card-body text-center">
                            <h5 class="card-title" style="font-weight: bold; color:black;">
                                <a style="font-size:16px; font-weight: bold; color:rgb(0, 0, 0);"
                                    href="{{ route('product.show', $product->slug) }}">{{ $product->name }}
                                    ({{ $product->sku }})</a>
                            </h5>
                            <p class="card-text" style=" font-weight: bold; color:rgb(144, 29, 29);">
                                {{ number_format($product->price) }} VND</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
      </div>
      {{-- modal --}}
      <div class="modal" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Thêm lớp này -->
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="quantityModalLabel">Chọn số lượng</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}">
                      @csrf
                      <input type="hidden" name="product_id" id="modal_product_id">
                      <div class="form-group">
                          <div class="form-group d-flex align-items-center">
                              <label for="quantity" class="mr-2">Số lượng:</label>
                              <button type="button" class="btn btn-secondary" id="decrement">-</button>
                              <input type="number" name="quantity" id="quantity" value="1" min="1"
                                  max="10" class="form-control mx-2" required style="width: 50px;">
                              <button type="button" class="btn btn-secondary" id="increment">+</button>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                  <button type="button" class="btn btn-success" id="confirm-add-to-cart">Xác nhận</button>
              </div>
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
    document.getElementById('increment').addEventListener('click', function() {
        let quantityInput = document.getElementById('quantity');
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < parseInt(quantityInput.max)) {
            quantityInput.value = currentQuantity + 1;
        }
    });

    document.getElementById('decrement').addEventListener('click', function() {
        let quantityInput = document.getElementById('quantity');
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > parseInt(quantityInput.min)) {
            quantityInput.value = currentQuantity - 1;
        }
    });
</script>
@endif


@endsection
