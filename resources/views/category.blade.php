@extends('layouts.master')

@section('content')
    <style>
        /* Định nghĩa kiểu cho overlay */
        .block-4-image {
            position: relative;
            overflow: hidden;
            /* Để ẩn phần overlay khi nó nằm ngoài khối */
        }

        .product-image {
            width: 100%;
            transition: transform 0.3s ease;
            /* Hiệu ứng mờ dần khi hover */
        }

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

        .block-4-image:hover .overlay {
            bottom: 0;
            /* Khi hover, overlay sẽ từ từ di chuyển từ dưới lên */
        }

        .block-4-image:hover .product-image {
            transform: scale(1.1);
            /* Tăng kích thước ảnh một chút khi hover */
        }

        .card-body {
            text-align: center;
            /* Giữa văn bản */
        }

        .card-text {
            font-weight: bold;
            /* Làm nổi bật giá */
            color: #000;
            /* Màu chữ đen cho giá */
        }

        /* Điều chỉnh chiều cao của ảnh */
        .card-img-top {
            height: 200px;
            /* Chiều cao cố định */
            object-fit: cover;
            /* Cắt ảnh theo tỉ lệ */
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

        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* Đảm bảo modal luôn ở giữa */
        }

        .site-blocks-cover {
            position: relative;
            max-width: 100%;
            overflow: hidden;
        }

        /* Thay đổi màu sắc thanh trượt */
        #price-range .ui-slider-range {
            background-color: #007bff;
            /* Màu xanh */
        }

        #price-range .ui-slider-handle {
            background-color: #007bff;
            /* Màu xanh */
            border-color: #0056b3;
            /* Màu xanh đậm hơn cho viền */
        }
    </style>
    <!-- Thêm CSS của jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="container">
        <div class="col-md-12 mb-0">
            <strong class="text-black">Trang chủ</strong>
            <span class="mx-2 mb-0">/</span>
            <strong class="text-black">{{ $category->name }}</strong>
        </div> <br>
        <form method="GET" action="{{ route('index.view', $category->slug) }}" class="mb-4" id="filter-form">
            <div class="row">
                <!-- Tìm kiếm -->
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm"
                        value="{{ request()->get('search', '') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-6" style="margin-top: 15px;">
                    <!-- Thanh trượt -->
                    <div id="price-range"></div>
                    <div class="d-flex justify-content-between">
                        <span style="color: black;" id="min-price">{{ number_format(request()->get('min_price', 0)) }}₫</span>
                        <span style="color: black;" id="max-price">{{ number_format(request()->get('max_price', $maxPrice)) }}₫</span>
                    </div>
                    <!-- Các input ẩn để gửi giá trị min_price và max_price -->
                    <input type="hidden" name="min_price" id="min-price-input"
                        value="{{ request()->get('min_price', 0) }}">
                    <input type="hidden" name="max_price" id="max-price-input"
                        value="{{ request()->get('max_price', $maxPrice) }}">
                </div>

                <!-- Sắp xếp -->
                <div class="col-md-3">
                    <select name="sort" class="form-control" onchange="this.form.submit()">
                        <option value="">Sắp xếp</option>
                        <option value="price_desc" {{ request()->get('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm
                            dần</option>
                        <option value="price_asc" {{ request()->get('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần
                        </option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Hiển thị các sản phẩm trong danh mục -->
        <div class="row">
            @if ($products->isEmpty())
                <p>Không có sản phẩm nào trong danh mục này.</p>
            @else
                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <figure class="block-4-image">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                    class="card-img-top">
                            </figure>
                            <div class="card-body">
                                <h5 class="card-title" style="font-weight: 500;">
                                    <a style="font-size:16px; font-weight: 500; color:rgb(0, 0, 0);"
                                        href="{{ route('product.show', $product->slug) }}">{{ $product->name }}
                                        ({{ $product->sku }})
                                    </a>
                                </h5>
                                <p class="card-text" style="font-weight: 500; color:rgb(144, 29, 29);">
                                    {{ number_format($product->price) }}₫</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Phân trang -->
        <div class="row">
            <div class="col-md-12 text-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Cài đặt thanh trượt
            $("#price-range").slider({
                range: true,
                min: 0,
                max: {{ $maxPrice }},
                values: [{{ request()->get('min_price', 0) }},
                    {{ request()->get('max_price', $maxPrice) }}
                ],
                step: 50000,
                slide: function(event, ui) {
                    // Cập nhật giá trị hiển thị khi kéo thanh trượt
                    $("#min-price").text(ui.values[0]);
                    $("#max-price").text(ui.values[1]);
                    $("#min-price-input").val(ui.values[0]);
                    $("#max-price-input").val(ui.values[1]);

                    // Gửi form khi giá trị thay đổi
                    $("#filter-form").submit();
                }
            });
        });
    </script>
@endsection
