@extends('layouts.master')

@section('content')
<style>

/* Định nghĩa vị trí cơ bản của ảnh sản phẩm và overlay */
.block-4-image {
    position: relative; /* Để overlay nằm chồng lên ảnh */
    overflow: hidden; /* Để ẩn phần overlay khi nó nằm ngoài khối */
}

.product-image {
    width: 100%;
    transition: transform 0.3s ease; /* Hiệu ứng mờ dần khi hover */
}

/* Định nghĩa vị trí ban đầu của overlay */
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

/* Hiển thị overlay khi hover */
.block-4-image:hover .overlay {
    bottom: 0; /* Khi hover, overlay sẽ từ từ di chuyển từ dưới lên */
}

.block-4-image:hover .product-image {
    transform: scale(1.1); /* Tăng kích thước ảnh một chút khi hover */
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

.slide {
    display: none;
}

.prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    margin-top: -22px;
    padding: 16px;
    color: white;
    font-weight: bold;
    font-size: 18px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
}

.next {
    right: 0;
    border-radius: 3px 0 0 3px;
}

.prev:hover, .next:hover {
    background-color: rgba(0,0,0,0.8);
}

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="site-blocks-cover" id="slideshow">
    <a href="#"><img src="../client/images/bn1.jpg" alt="Slide 1" class="slide" style="width:100%"></a>
    <a href="#"><img src="../client/images/bn2.jpg" alt="Slide 2" class="slide" style="width:100%"></a>


    <!-- Nút điều khiển -->
    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeSlide(1)">&#10095;</a>
</div>


<div class="site-section site-blocks-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                <a class="block-2-item" href="../client/#">
                    <figure class="image">
                        <img src="../client/images/women.jpg" alt="" class="img-fluid">
                    </figure>
                    <div class="text">
                        <span class="text-uppercase">Watches</span>
                        <h3>Women</h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
                <a class="block-2-item" href="../client/#">
                    <figure class="image">
                        <img src="../client/images/luxury.jpg" alt="" class="img-fluid">
                    </figure>
                    <div class="text">
                        <span class="text-uppercase">Watches</span>
                        <h3>Luxury</h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
                <a class="block-2-item" href="../client/#">
                    <figure class="image">
                        <img src="../client/images/men.jpg" alt="" class="img-fluid">
                    </figure>
                    <div class="text">
                        <span class="text-uppercase">Watches</span>
                        <h3>Men</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 site-section-heading text-center pt-4">
            <h2>Mới ra mắt</h2>
        </div>
    </div>
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-3 mb-4"> <!-- Thay đổi col-md-3 thành col-md-4 hoặc col-md-3 để điều chỉnh kích thước -->
                <div class="card" style="width: 100%;">
                    <figure class="block-4-image">
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="overlay">
                            <a href="" data-product-id="{{ $product->id }}"><i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ hàng</a>
                        </div>
                    </figure>
                    <div class="card-body text-center">
                        <h5 class="card-title" style="font-weight: bold; color:black;">
                            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }} ({{ $product->sku }})</a>
                        </h5>
                        <p class="card-text">Giá: {{ number_format($product->price) }} VND</p>
                    </div>
                </div>
            </div>
        @endforeach
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
              <input type="number" class="form-control" id="quantity" name="quantity" min="1"  value="1">
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

let slideIndex = 0;
showSlides();

// Tự động thay đổi slide
function showSlides() {
    let slides = document.getElementsByClassName("slide");

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slideIndex++;
    if (slideIndex > slides.length) { slideIndex = 1; }

    slides[slideIndex-1].style.display = "block";

    // Thay đổi sau mỗi 3 giây
    setTimeout(showSlides, 3000);
}

// Điều khiển slide thủ công
function changeSlide(n) {
    slideIndex += n;
    let slides = document.getElementsByClassName("slide");

    if (slideIndex > slides.length) { slideIndex = 1; }
    if (slideIndex < 1) { slideIndex = slides.length; }

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex-1].style.display = "block";
}

</script>

@endsection
