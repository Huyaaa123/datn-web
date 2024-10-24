@extends('layouts.master')

@section('content')
<style>
    /* Định nghĩa kiểu cho overlay */
    .block-4-image {
        position: relative;
        overflow: hidden; /* Để ẩn phần overlay khi nó nằm ngoài khối */
    }

    .product-image {
        width: 100%;
        transition: transform 0.3s ease; /* Hiệu ứng mờ dần khi hover */
    }

    .overlay {
        position: absolute;
        bottom: -100%; /* Ẩn overlay hoàn toàn bên dưới khối */
        left: 0;
        right: 0;
        background-color: rgba(49, 47, 47, 0.7); /* Nền tối với độ trong suốt */
        color: white;
        text-align: center;
        padding: 20px;
        transition: all 0.5s ease; /* Hiệu ứng di chuyển */
    }

    .block-4-image:hover .overlay {
        bottom: 0; /* Khi hover, overlay sẽ từ từ di chuyển từ dưới lên */
    }

    .block-4-image:hover .product-image {
        transform: scale(1.1); /* Tăng kích thước ảnh một chút khi hover */
    }

    .card-body {
        text-align: center; /* Giữa văn bản */
    }

    .card-text {
        font-weight: bold; /* Làm nổi bật giá */
        color: #000; /* Màu chữ đen cho giá */
    }

    /* Điều chỉnh chiều cao của ảnh */
    .card-img-top {
        height: 200px; /* Chiều cao cố định */
        object-fit: cover; /* Cắt ảnh theo tỉ lệ */
    }
    .overlay a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
    }
    a:hover {
        color: white; /* Giữ nguyên màu trắng khi hover */
        text-decoration: none; /* Không underline hoặc bất kỳ hiệu ứng hover nào khác */
    }

    .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh; /* Đảm bảo modal luôn ở giữa */
    }
    .site-blocks-cover {
        position: relative;
        max-width: 100%;
        overflow: hidden;
    }

</style>
<div class="container">
    <div class="col-md-12 mb-0">
        <strong class="text-black">Trang chủ</strong>
        <span class="mx-2 mb-0">/</span>
        <strong class="text-black">{{$category->name}}</strong>
    </div>
    <br>
    <div class="row">
        @if($products->isEmpty())
            <p>Không có sản phẩm nào trong danh mục này.</p>
        @else
            @foreach($products as $product)
                <div class="col-md-3 mb-4"> <!-- Chia 4 phần -->
                    <div class="card">
                        <figure class="block-4-image">
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="card-img-top">
                            <div class="overlay">
                                <a href="" data-product-id="{{ $product->id }}"><i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ hàng</a>
                            </div>
                        </figure>
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold;">
                                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }} ({{ $product->sku }})</a>
                            </h5>
                            <p class="card-text">Giá: {{ number_format($product->price) }} VND</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Phân trang -->
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $products->links() }} <!-- Hiển thị phân trang -->
        </div>
    </div>
</div>

<!-- Modal chọn số lượng (Không có lớp fade) -->
<div class="modal" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
              <label for="quantity">Số lượng:</label>
              <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="10" value="1">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" id="confirm-add-to-cart">Xác nhận</button>
        </div>
      </div>
    </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    // Khi người dùng nhấn vào 'Thêm vào giỏ hàng'
    document.querySelectorAll('.overlay a').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            // Lấy product_id từ data attribute
            var productId = this.getAttribute('data-product-id');

            // Đặt product_id vào input ẩn trong modal
            document.getElementById('modal_product_id').value = productId;

            // Hiển thị modal ngay lập tức
            $('#quantityModal').modal('show');
        });
    });

    // Khi người dùng nhấn 'Xác nhận' trong modal
    document.getElementById('confirm-add-to-cart').addEventListener('click', function () {
        // Gửi form để thêm vào giỏ hàng
        document.getElementById('add-to-cart-form').submit();
    });
});
</script>
@endsection
