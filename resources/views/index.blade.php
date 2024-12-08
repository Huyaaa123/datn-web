@extends('layouts.master')

@section('content')
    <style>
        .block-4-image {
            position: relative;
            /* Để overlay nằm chồng lên ảnh */
            overflow: hidden;
            /* Để ẩn phần overlay khi nó nằm ngoài khối */
        }

        .product-image {
            width: 100%;
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

        .slide {
            display: none;
        }

        .prev,
        .next {
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

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="site-blocks-cover" id="slideshow">
        <a href="#"><img src="../client/images/bn1.jpg" alt="Slide 1" class="slide" style="width:100%"></a>
        <a href="#"><img src="../client/images/bn2.jpg" alt="Slide 2" class="slide" style="width:100%"></a>


        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
    </div>
    <div class="site-section site-blocks-2">
        <div class="container">
            <div class="row">
                @foreach ($categories as $category)
                    @if ($category->slug == 'dong-ho-nu')
                        <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                            <a class="block-2-item" href="../categories/{{ $category->slug }}">
                                <figure class="image">
                                    <img src="../client/images/women.jpg" alt="" class="img-fluid">
                                </figure>
                                <div class="text">
                                    <span class="text-uppercase">Watches</span>
                                    <h3>Women</h3>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if ($category->slug == 'luxury')
                        <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
                            <a class="block-2-item" href="../categories/{{ $category->slug }}">
                                <figure class="image">
                                    <img src="../client/images/luxury.jpg" alt="" class="img-fluid">
                                </figure>
                                <div class="text">
                                    <span class="text-uppercase">{{ $category->name }}</span> <!-- Tên danh mục -->
                                    <h3>Luxury</h3> <!-- Tiêu đề có thể giữ nguyên hoặc điều chỉnh -->
                                </div>
                            </a>
                        </div>
                    @endif

                    @if ($category->slug == 'dong-ho-nam')
                        <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
                            <a class="block-2-item" href="../categories/{{ $category->slug }}">
                                <figure class="image">
                                    <img src="../client/images/men.jpg" alt="" class="img-fluid">
                                </figure>
                                <div class="text">
                                    <span class="text-uppercase">Watches</span>
                                    <h3>Men</h3>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 site-section-heading text-center pt-4">
                <h2>Mới ra mắt</h2>
            </div>
        </div>
        <div class="row" id="new-products">
            @foreach ($products as $product)
                @if ($product->category && $product->category->name !== 'Chưa phân loại') <!-- Kiểm tra danh mục -->
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm rounded border-2">
                            <figure class="block-4-image mb-0">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                    class="card-img-top"
                                    style="height: 200px; object-fit: cover; border-bottom: 2px solid #eee;">
                            </figure>
                            <div class="card-body text-center" style="padding-top: 10px;">
                                <h5 class="card-title" style="font-size: 16px; font-weight: 500; color: #333; margin-top: 5px;">
                                    <a href="{{ route('product.show', $product->slug) }}"
                                        style="font-size: 16px; font-weight: bold; color: rgb(0, 0, 0); text-decoration: none;">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <p class="product-category text-muted"
                                    style="font-weight: 500; font-size: 14px; color: #777; margin-top: -5px;">
                                    {{ $product->category->name }}
                                </p>
                                <p class="card-text"
                                    style="font-weight: 500; color: rgb(144, 29, 29); font-size: 18px; margin-top: 5px;">
                                    {{ number_format($product->price) }}₫
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            <button id="prev-new-btn" class="btn btn-primary mx-1" disabled>
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button id="next-new-btn" class="btn btn-primary mx-1">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 site-section-heading text-center pt-4">
                <h2>Đồng hồ nam</h2>
            </div>
        </div>
        <div class="row" id="men-products">
            @if ($menProducts->isNotEmpty())
                <div class="row">
                    @foreach ($menProducts as $product)
                        <div class="col-md-3 mb-4">
                            <div class="card shadow-sm rounded border-2">
                                <figure class="block-4-image mb-0">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                        class="card-img-top"
                                        style="height: 200px; object-fit: cover; border-bottom: 2px solid #eee;">
                                </figure>
                                <div class="card-body text-center" style="padding-top: 10px;">
                                    <h5 class="card-title"
                                        style="font-size: 16px; font-weight: 500; color: #333; margin-top: 5px;">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            style="font-size: 16px; font-weight: bold; color: rgb(0, 0, 0); text-decoration: none;">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="product-category text-muted"
                                        style="font-weight: 500; font-size: 14px; color: #777; margin-top: -5px;">
                                        {{ $product->category->name }}
                                    </p>
                                    <p class="card-text"
                                        style="font-weight: 500; color: rgb(144, 29, 29); font-size: 18px; margin-top: 5px;">
                                        {{ number_format($product->price) }}₫
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="d-flex justify-content-center ">
            <button id="prev-men-btn" class="btn btn-primary mx-1" disabled>
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button id="next-men-btn" class="btn btn-primary mx-1">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 site-section-heading text-center pt-4">
                <h2>Đồng hồ nữ</h2>
            </div>
        </div>
        <div class="row" id="women-products">
            @if ($womenProducts->isNotEmpty())
                <div class="row">
                    @foreach ($womenProducts as $product)
                        <div class="col-md-3 mb-4">
                            <div class="card shadow-sm rounded border-2">
                                <figure class="block-4-image mb-0">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                        class="card-img-top"
                                        style="height: 200px; object-fit: cover; border-bottom: 2px solid #eee;">
                                </figure>
                                <div class="card-body text-center" style="padding-top: 10px;">
                                    <h5 class="card-title"
                                        style="font-size: 16px; font-weight: 500; color: #333; margin-top: 5px;">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            style="font-size: 16px; font-weight: bold; color: rgb(0, 0, 0); text-decoration: none;">
                                            {{ $product->name }} 
                                        </a>
                                    </h5>
                                    <p class="product-category text-muted"
                                        style="font-weight: 500; font-size: 14px; color: #777; margin-top: -5px;">
                                        {{ $product->category->name }}
                                    </p>
                                    <p class="card-text"
                                        style="font-weight: 500; color: rgb(144, 29, 29); font-size: 18px; margin-top: 5px;">
                                        {{ number_format($product->price) }}₫
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="d-flex justify-content-center ">
            <button id="prev-women-btn" class="btn btn-primary mx-1" disabled>
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button id="next-women-btn" class="btn btn-primary mx-1">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>
    <br>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productsPerPage = 4;

            // Phân trang cho Sản phẩm mới
            let currentNewPage = 0;
            const newProducts = document.querySelectorAll('#new-products .col-md-3');

            function showNewProducts() {
                newProducts.forEach((product, index) => {
                    const start = currentNewPage * productsPerPage;
                    const end = start + productsPerPage;
                    product.style.display = index >= start && index < end ? 'block' : 'none';
                });

                document.getElementById('prev-new-btn').disabled = currentNewPage === 0;
                document.getElementById('next-new-btn').disabled = currentNewPage >= Math.ceil(newProducts.length /
                    productsPerPage) - 1;
            }

            document.getElementById('next-new-btn').addEventListener('click', () => {
                if (currentNewPage < Math.ceil(newProducts.length / productsPerPage) - 1) {
                    currentNewPage += 1;
                    showNewProducts();
                }
            });

            document.getElementById('prev-new-btn').addEventListener('click', () => {
                if (currentNewPage > 0) {
                    currentNewPage -= 1;
                    showNewProducts();
                }
            });

            // Phân trang cho Đồng hồ nam
            let currentMenPage = 0;
            const menProducts = document.querySelectorAll('#men-products .col-md-3');

            function showMenProducts() {
                menProducts.forEach((product, index) => {
                    const start = currentMenPage * productsPerPage;
                    const end = start + productsPerPage;
                    product.style.display = index >= start && index < end ? 'block' : 'none';
                });

                document.getElementById('prev-men-btn').disabled = currentMenPage === 0;
                document.getElementById('next-men-btn').disabled = currentMenPage >= Math.ceil(menProducts.length /
                    productsPerPage) - 1;
            }

            document.getElementById('next-men-btn').addEventListener('click', () => {
                if (currentMenPage < Math.ceil(menProducts.length / productsPerPage) - 1) {
                    currentMenPage += 1;
                    showMenProducts();
                }
            });

            document.getElementById('prev-men-btn').addEventListener('click', () => {
                if (currentMenPage > 0) {
                    currentMenPage -= 1;
                    showMenProducts();
                }
            });

            // Phân trang cho Đồng hồ nữ
            let currentWomenPage = 0;
            const womenProducts = document.querySelectorAll('#women-products .col-md-3');

            function showWomenProducts() {
                womenProducts.forEach((product, index) => {
                    const start = currentWomenPage * productsPerPage;
                    const end = start + productsPerPage;
                    product.style.display = index >= start && index < end ? 'block' : 'none';
                });

                document.getElementById('prev-women-btn').disabled = currentWomenPage === 0;
                document.getElementById('next-women-btn').disabled = currentWomenPage >= Math.ceil(womenProducts
                    .length / productsPerPage) - 1;
            }

            document.getElementById('next-women-btn').addEventListener('click', () => {
                if (currentWomenPage < Math.ceil(womenProducts.length / productsPerPage) - 1) {
                    currentWomenPage += 1;
                    showWomenProducts();
                }
            });

            document.getElementById('prev-women-btn').addEventListener('click', () => {
                if (currentWomenPage > 0) {
                    currentWomenPage -= 1;
                    showWomenProducts();
                }
            });

            // Hiển thị các sản phẩm ban đầu
            showNewProducts();
            showMenProducts();
            showWomenProducts();
        });
    </script>
    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let slides = document.getElementsByClassName("slide");

            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }

            slides[slideIndex - 1].style.display = "block";

            setTimeout(showSlides, 3000);
        }

        function changeSlide(n) {
            slideIndex += n;
            let slides = document.getElementsByClassName("slide");

            if (slideIndex > slides.length) {
                slideIndex = 1;
            }
            if (slideIndex < 1) {
                slideIndex = slides.length;
            }

            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slides[slideIndex - 1].style.display = "block";
        }
    </script>
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
@endsection
