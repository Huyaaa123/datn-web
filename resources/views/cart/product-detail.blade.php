@extends('layouts.master')

@section('content')
    <style>
        .card {
            border: none;
            box-shadow: none;
        }

        .card-img-top {
            border-radius: 50%;
            /* Đảm bảo hình ảnh có hình tròn */
        }

        .main-image {
            position: relative;
            /* Để kiểm soát các nút điều khiển */
            max-width: 100%;
            /* Đặt chiều rộng tối đa là 100% của phần chứa */
            height: auto;
            /* Chiều cao tự động để giữ tỷ lệ khung hình */
        }

        .main-image img {
            width: 100%;
            /* Đặt chiều rộng bằng 100% để lấp đầy phần chứa */
            max-height: 500px;
            /* Chiều cao tối đa cho hình ảnh (có thể điều chỉnh) */
            object-fit: cover;
            /* Để đảm bảo hình ảnh được cắt và không bị biến dạng */
        }

        .main-image .btn-secondary {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.5;
            /* Đặt độ mờ ban đầu cho nút */
            transition: opacity 0.3s;
            /* Thêm hiệu ứng chuyển tiếp khi đổi độ mờ */
        }

        .btn-secondary:hover {
            opacity: 0.8;
            /* Khi di chuột qua, nút trở nên rõ ràng */
        }

        .discount-badge {
            background-color: #e10c00;
            color: white;
            padding: 5px 3px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            animation: blinkBackground 0.2s infinite alternate;
            /* Hiệu ứng nháy nháy */
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

        /* Viền rõ hơn khi màu được chọn */
        .color-option input[type="radio"]:checked+span {
            border: 4px solid #0056b3;
            /* Viền xanh đậm */
            box-shadow: 0 0 8px rgba(0, 86, 179, 0.8);
            /* Hiệu ứng bóng rõ hơn */
            transform: scale(1.1);
            /* Phóng to nhẹ để tạo hiệu ứng chọn */
            transition: all 0.2s ease-in-out;
            /* Hiệu ứng mượt */
        }

        /* Hover vào màu */
        .color-option span:hover {
            border: 3px solid #666;
            /* Viền đậm hơn khi hover */
            cursor: pointer;
            transition: border 0.2s ease-in-out;
        }

        .thumbnail-images {
            max-height: 80px;
            /* Giới hạn chiều cao */
            overflow-x: auto;
            /* Cho phép cuộn ngang */
            scroll-behavior: smooth;
            /* Cuộn mượt mà */
            white-space: nowrap;
            /* Đặt các ảnh nhỏ thành một hàng */
        }

        .thumbnail-container {
            position: relative;
            max-width: 100%;
            overflow: hidden;
            /* Ẩn các phần thừa khi cuộn */
        }

        .thumbnail-images {
            transition: transform 0.3s ease;
            /* Hiệu ứng cuộn mượt */
        }

        .thumbnail {
            border: 2px solid transparent;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }

        .thumbnail:hover {
            border-color: #007bff;
            /* Màu viền khi hover */
        }

        .thumbnail.active {
            border-color: #007bff;
            /* Màu viền khi được chọn */
        }

        .btn-secondary {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }
    </style>

    <div class="container">
        <div class="col-md-12 mb-0">
            <strong class="text-black">Trang chủ</strong>
            <span class="mx-2 mb-0">/</span>
            <strong class="text-black">{{ $category->name }}</strong>
            <span class="mx-2 mb-0">/</span>
            <strong class="text-black">{{ $product->name }}</strong>
        </div>
        <br>
        <div class="row">
            <div class="col-md-5">
                <!-- Hiển thị ảnh chính -->
                <div class="main-image position-relative mb-3">
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" id="mainImage"
                        class="w-100">
                    <button class="btn btn-secondary position-absolute" onclick="changeImage(-1)"
                        style="top: 50%; left: 10px; transform: translateY(-50%);"> &#10094; </button>
                    <button class="btn btn-secondary position-absolute" onclick="changeImage(1)"
                        style="top: 50%; right: 10px; transform: translateY(-50%);"> &#10095; </button>
                </div>

                <!-- Hiển thị ảnh nhỏ -->
                <div class="thumbnail-images d-flex overflow-auto px-2" style="max-width: 100%; white-space: nowrap;">
                    @foreach ($product->galleries as $index => $gallery)
                        <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $product->name }}"
                            class="thumbnail border mx-1"
                            style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; display: inline-block;"
                            onclick="updateMainImage({{ $index }})">
                    @endforeach
                </div> <br>
            </div>
            <div class="col-md-7">
                <p style="font-weight: bold; font-size: 16px; color: #777; margin-bottom: 5px;">
                    {{ $product->category->name }}
                </p>
                <h2 style="color:black;font-weight:500;">{{ $product->name }}</h2>

                <p style="color:black; font-size: 18px; margin-bottom: 5px;">Mã sản phẩm: <span
                        style="font-weight:500; color: #ffb700">{{ $product->sku }}</span></p>
                <h3 style="color:#990000;font-weight:500; ">{{ number_format($product->price) }}₫</h3>

                <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <div class="form-group d-flex align-items-center"
                        style="display: flex; align-items: center; margin-bottom: 5px;">
                        <label for="color"
                            style="color:black; margin-right: 20px; text-align: center; line-height: 30px; display: inline-flex; align-items: center; margin-top: -10px;">
                            Màu sắc:
                        </label>
                        <div id="color-options" style="display: flex; align-items: center; justify-content: center;">
                            @foreach ($product->colors as $color)
                                <label class="color-option" style="margin-right: 10px;">
                                    <input type="radio" name="color_id" value="{{ $color->id }}"
                                        style="display: none;" required>
                                    <span
                                        style="display: inline-block; width: 30px; height: 30px; background-color: {{ $color->code }};
                                    border-radius: 50%; border: 2px solid #ddd; cursor: pointer;"
                                        title="{{ $color->name }}">
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group d-flex align-items-center">
                        <label for="size" style="color:black;  margin-right: 35px;">Kích cỡ:</label>
                        <select name="size_id" id="size" class="form-control"
                            style="width: 150px; height:40px; display: inline-block;" required>
                            @foreach ($product->sizes as $size)
                                <option value="{{ $size->id }}" style="text-align:center; width: 50px;">
                                    {{ $size->name }}
                                </option>
                            @endforeach
                        </select>
                        <a href=""
                            style="margin-left: 10px; text-decoration: underline; font-weight:500; color:black;  font-size:13px;">
                            Hướng dẫn chọn size</a>
                    </div>

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="form-group d-flex align-items-center">
                        <label for="size" style="color:black;  margin-right: 25px;">Số lượng:</label>
                        <div class="input-group" style="width: 150px; border: 1px solid #dcdcdc; border-radius: 5px;">
                            <button class="btn-light border-0" type="button" id="decrement"
                                style="width: 40px; height: 40px; font-size: 20px; padding: 0;">-</button>
                            <input type="text" name="quantity" id="quantity" value="1" min="1"
                                max="10" class="form-control text-center" required
                                style="height: 40px; font-size: 18px; border: none; outline: none;"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <button class="btn-light border-0" type="button" id="increment"
                                style="width: 40px; height: 40px; font-size: 20px; padding: 0;">+</button>
                        </div>
                        <button type="submit" id="add-to-cart-button"
                            style="background-color: white; border: 1px solid black; color: black; padding: 5px 50px; font-size: 16px; cursor: pointer; transition: all 0.3s; margin-left: 10px;">
                            THÊM VÀO GIỎ
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <p style="color: black; font-weight: 500;">Sản phẩm tương tự</p>
                    <div class="row justify-content-center">
                        @foreach ($similarProducts as $similarProduct)
                            <div class="col-md-2 mb-4">
                                <div class="card text-center">
                                    <!-- Thêm đường dẫn vào hình ảnh -->
                                    <a href="{{ route('product.show', $similarProduct->slug) }}"
                                        class="product-image-link">
                                        <img src="{{ Storage::url($similarProduct->image_path) }}" class="card-img-top"
                                            alt="{{ $similarProduct->name }}">
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div style="border: 1px solid #e0b344; border-radius: 5px; overflow: hidden;">
                    <!-- Phần tiêu đề -->
                    <div
                        style="text-align:center; background-color: #e0b344; color: black; padding: 10px; font-weight: 500;">
                        Mô tả sản phẩm
                    </div>
                    <!-- Phần nội dung mô tả -->
                    <div style="padding: 10px; color:black;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="product-policises-wrapper" style="border: 1px solid #e0b344; border-radius: 2px;">
                    <h3
                        style="font-size: 1rem; font-weight: 500; margin: 0; padding: 10px 0; text-align: center; background-color: #e0b344; color: black;">
                        Chính sách mua hàng tại ROLEX</h3>
                    <ul class="product-policises list-unstyled py-sm-3 px-sm-3 m-0"
                        style="display: flex; flex-wrap: wrap; padding-left: 0; list-style-type: none; background-color: #ffffff;">
                        <li class="media" style="flex: 1 1 50%; padding-bottom: 10px; margin-bottom: 10px;">
                            <div style="margin-right: -20px;">
                                <img class="img-fluid" decoding="async" width="48" height="48" alt="polici"
                                    src="//theme.hstatic.net/200000656863/1001295514/14/policy_product_image_1.png?v=286">
                            </div>
                            <div style="color:black;" class="media-body">
                                Miễn phí vận chuyển
                            </div>
                        </li>
                        <li class="media" style="flex: 1 1 50%; padding-bottom: 10px; margin-bottom: 10px;">
                            <div style="margin-right: -20px;">
                                <img class="img-fluid" decoding="async" width="48" height="48" alt="polici"
                                    src="//theme.hstatic.net/200000656863/1001295514/14/policy_product_image_2.png?v=286">
                            </div>
                            <div style="color:black;" class="media-body">
                                Thay pin miễn phí trọn đời
                            </div>
                        </li>
                        <li class="media" style="flex: 1 1 50%; padding-bottom: 10px; margin-bottom: 10px;">
                            <div style="margin-right: -20px;">
                                <img class="img-fluid" decoding="async" width="48" height="48" alt="polici"
                                    src="//theme.hstatic.net/200000656863/1001295514/14/policy_product_image_3.png?v=286">
                            </div>
                            <div style="color:black;" class="media-body">
                                Cam kết hàng chính hãng
                            </div>
                        </li>
                        <li class="media" style="flex: 1 1 50%; padding-bottom: 10px; margin-bottom: 10px;">
                            <div style="margin-right: -20px;">
                                <img class="img-fluid" decoding="async" width="48" height="48" alt="polici"
                                    src="//theme.hstatic.net/200000656863/1001295514/14/policy_product_image_4.png?v=286">
                            </div>
                            <div style="color:black;" class="media-body">
                                Bảo hành trọn gói 5 năm
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <br>

        <div class="product-detail"
            style="font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">

            <h2 style="font-size: 24px; font-weight: bold; color: #333;">Bình luận</h2>

            {{-- <div class="product-rating-filter" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <!-- Đánh giá sản phẩm -->
                <div class="product-rating" style="display: flex; align-items: center;">
                    <div
                        style="font-size: 26px; font-weight: bold; color: #e9b800; margin-right: 10px;">
                        {{ number_format($product->comments->avg('rating'), 1) }} / 5
                    </div>
                    <div class="rating-stars" style="display: inline-block;">
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                style="font-size: 22px; color: {{ $i <= round($product->comments->avg('rating')) ? 'gold' : 'gray' }};">★</span>
                        @endfor
                    </div>
                </div>

                <!-- Bộ lọc đánh giá -->
                <div class="rating-filter" style="display: flex;margin-right: 110px; gap: 10px;">
                    <button
                        style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                        <a href="{{ route('product.show', $product->slug) }}" style="text-decoration: none; color: #333;">Tất
                            Cả</a>
                    </button>
                    <button
                        style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                        <a href="{{ route('product.show', $product->slug) }}?rating=5"
                            style="text-decoration: none; color: #333;">5 Sao</a>
                    </button>
                    <button
                        style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                        <a href="{{ route('product.show', $product->slug) }}?rating=4"
                            style="text-decoration: none; color: #333;">4 Sao</a>
                    </button>
                    <button
                        style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                        <a href="{{ route('product.show', $product->slug) }}?rating=3"
                            style="text-decoration: none; color: #333;">3 Sao</a>
                    </button>
                    <button
                        style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                        <a href="{{ route('product.show', $product->slug) }}?rating=2"
                            style="text-decoration: none; color: #333;">2 Sao</a>
                    </button>
                    <button
                        style="background-color: #fff; border: 1px solid #ccc; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px; color: #333;">
                        <a href="{{ route('product.show', $product->slug) }}?rating=1"
                            style="text-decoration: none; color: #333;">1 Sao</a>
                    </button>
                </div>
            </div>

            <!-- Hiển thị bình luận -->
            <div class="comments-container" style="margin-top: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                @foreach ($comments as $comment)
                    <div class="comment"
                        style="margin-top: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
                        <div class="comment-header" >
                            <strong style="color: black">{{ $comment->user->name }}</strong> -
                            <span style="font-size: 14px; color: gray;">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-rating" style="margin-bottom: 10px;">
                            @for ($i = 1; $i <= 5; $i++)
                                <span style="font-size: 15px; color: {{ $i <= $comment->rating ? 'gold' : 'gray' }};">★</span>
                            @endfor
                        </div>
                        <p style="margin: 10px 0; color: #333;">{{ $comment->content }}</p>
                    </div>
                @endforeach
            </div>
        </div> --}}
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

            document.getElementById('add-to-cart-button').addEventListener('click', function() {
                // Kiểm tra đăng nhập
                @if (auth()->check())
                    // Nếu đã đăng nhập, submit form
                    document.getElementById('add-to-cart-form').submit();
                @else
                    // Nếu chưa đăng nhập, chuyển hướng đến trang login
                    window.location.href = "{{ route('login') }}";
                @endif
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
