<!-- resources/views/products/show.blade.php -->

@extends('layouts.master')

@section('content')
<style>
.main-image {
    position: relative; /* Để kiểm soát các nút điều khiển */
    max-width: 100%; /* Đặt chiều rộng tối đa là 100% của phần chứa */
    height: auto; /* Chiều cao tự động để giữ tỷ lệ khung hình */
}

.main-image img {
    width: 100%; /* Đặt chiều rộng bằng 100% để lấp đầy phần chứa */
    max-height: 500px; /* Chiều cao tối đa cho hình ảnh (có thể điều chỉnh) */
    object-fit: cover; /* Để đảm bảo hình ảnh được cắt và không bị biến dạng */
}
.btn-secondary {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.5; /* Đặt độ mờ ban đầu cho nút */
    transition: opacity 0.3s; /* Thêm hiệu ứng chuyển tiếp khi đổi độ mờ */
}

.btn-secondary:hover {
    opacity: 0.8; /* Khi di chuột qua, nút trở nên rõ ràng */
}
.discount-badge {
        background-color: #e10c00;
        color: white;
        padding: 5px 3px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: bold;
        animation: blinkBackground 0.2s infinite alternate; /* Hiệu ứng nháy nháy */
    }

    /* Keyframes để thay đổi màu nền */
    @keyframes blinkBackground {
        0% {
            background-color: #e10c00;
        }
        100% {
            background-color: #ffcc00;
        }
    }
</style>
<div class="container">
    <div class="col-md-12 mb-0">
        <strong class="text-black">Trang chủ</strong>
        <span class="mx-2 mb-0">/</span>
        <strong class="text-black">{{ $category->name }}</strong>
        <span class="mx-2 mb-0">/</span>
        <strong class="text-black">{{ $product->name }}</strong> <!-- Sử dụng $product thay vì $products -->
    </div>
<br>
    <div class="row">
        <div class="col-md-6">
            <!-- Hiển thị ảnh chính -->
            <div class="main-image position-relative">
                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" id="mainImage">
                <!-- Các nút điều khiển slideshow nằm trên hình ảnh -->
                <button class="btn btn-secondary position-absolute" onclick="changeImage(-1)" style="top: 50%; left: 10px; transform: translateY(-50%);"> &#10094; </button>
                <button class="btn btn-secondary position-absolute" onclick="changeImage(1)" style="top: 50%; right: 10px; transform: translateY(-50%);"> &#10095; </button>
            </div>
        </div>

        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p>Giá: {{ number_format($product->final_price) }} VND</p> <!-- Sử dụng giá cuối cùng -->
            <p>Mã sản phẩm: {{ $product->sku }}</p>
            <p>{{ $product->description }}</p>

            <!-- Thêm form để thêm sản phẩm vào giỏ hàng -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Trường nhập số lượng -->
                <div class="form-group">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Thêm vào Giỏ Hàng</button>
            </form>
        </div>
    </div>
</div> <br>
<script>
    let currentIndex = 0; // Chỉ số hình ảnh hiện tại
    const images = @json($product->galleries->pluck('image_path')); // Lấy đường dẫn của các hình ảnh

    function updateMainImage(index) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = '{{ Storage::url('') }}' + images[index]; // Cập nhật hình ảnh chính
    }

    function changeImage(direction) {
        currentIndex += direction; // Cập nhật chỉ số hình ảnh
        if (currentIndex < 0) {
            currentIndex = images.length - 1; // Quay lại hình ảnh cuối cùng nếu đi qua trái
        } else if (currentIndex >= images.length) {
            currentIndex = 0; // Quay lại hình ảnh đầu tiên nếu đi qua phải
        }
        updateMainImage(currentIndex); // Cập nhật hình ảnh chính
    }
</script>

@endsection
