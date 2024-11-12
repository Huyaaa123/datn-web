@extends('layouts.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
        }

        .stepper__step {
            text-align: center;
            position: relative;
        }

        .stepper__step-icon {
            font-size: 2rem;
            color: #2dc258;
            margin-bottom: 0.5rem;
        }

        .stepper__step--finish .stepper__step-icon {
            color: #2dc258;
            /* Màu xanh cho các bước đã hoàn thành */
        }

        .stepper__step-text {
            font-weight: 500;
            color: #333;
        }

        .stepper__step-date {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
    <section>
        <div class="container"> <br>
            <div class="d-flex justify-content-between">
                <a href="{{ route('order.client.user') }}" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Quay lại Đơn hàng</a>
            </div>


            @if (in_array($order->orderStatus->id, [1, 2, 3, 4, 5, 6]))
                <div class="stepper">
                    <div
                        class="stepper__step {{ $order->orderStatus->id === 1 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 1 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 1 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M222-80q-43.75 0-74.37-30.63Q117-141.25 117-185v-125h127v-570l59.8 60 59.8-60 59.8 60 59.8-60 59.8 60 60-60 60 60 60-60 60 60 60-60v695q0 43.75-30.62 74.37Q781.75-80 738-80H222Zm516-60q20 0 32.5-12.5T783-185v-595H304v470h389v125q0 20 12.5 32.5T738-140ZM357-622v-60h240v60H357Zm0 134v-60h240v60H357Zm333-134q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9Zm0 129q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9ZM221-140h412v-110H177v65q0 20 12.65 32.5T221-140Zm-44 0v-110 110Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <div class="stepper__step-text">Đơn hàng đã đặt</div>
                        {{-- <div class="stepper__step-date">14:39 26-05-2023</div> --}}
                    </div>
                    <div
                        class="stepper__step {{ $order->orderStatus->id === 2 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 2 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 2 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M540-420q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM220-280q-24.75 0-42.37-17.63Q160-315.25 160-340v-400q0-24.75 17.63-42.38Q195.25-800 220-800h640q24.75 0 42.38 17.62Q920-764.75 920-740v400q0 24.75-17.62 42.37Q884.75-280 860-280H220Zm100-60h440q0-42 29-71t71-29v-200q-42 0-71-29t-29-71H320q0 42-29 71t-71 29v200q42 0 71 29t29 71Zm480 180H100q-24.75 0-42.37-17.63Q40-195.25 40-220v-460h60v460h700v60ZM220-340v-400 400Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div class="stepper__step-text">Xác nhận đơn hàng</div>
                    </div>

                    <div
                        class="stepper__step {{ $order->orderStatus->id === 3 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 3 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 3 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M224.12-161q-49.12 0-83.62-34.42Q106-229.83 106-279H40v-461q0-24 18-42t42-18h579v167h105l136 181v173h-71q0 49.17-34.38 83.58Q780.24-161 731.12-161t-83.62-34.42Q613-229.83 613-279H342q0 49-34.38 83.5t-83.5 34.5Zm-.12-60q24 0 41-17t17-41q0-24-17-41t-41-17q-24 0-41 17t-17 41q0 24 17 41t41 17ZM100-339h22q17-27 43.04-43t58-16q31.96 0 58.46 16.5T325-339h294v-401H100v401Zm631 118q24 0 41-17t17-41q0-24-17-41t-41-17q-24 0-41 17t-17 41q0 24 17 41t41 17Zm-52-204h186L754-573h-75v148ZM360-529Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="stepper__step-text">Đang giao hàng</div>
                    </div>

                    <div
                        class="stepper__step {{ $order->orderStatus->id === 4 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 4 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 4 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h600q24 0 42 18t18 42v600q0 24-18 42t-42 18H180Zm0-60h600v-136H634q-26 40-67.5 61.5T480-233q-45 0-86.5-21.5T326-316H180v136Zm300.25-113Q521-293 554-316.5t56-59.5h170v-404H180v404h170q23 36 56.25 59.5 33.24 23.5 74 23.5ZM480-422 327-575l43-43 80 80v-189h60v189l80-80 43 43-153 153ZM180-180h600-600Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-house-door"></i>
                        </div>
                        <div class="stepper__step-text">Đã giao hàng</div>
                    </div>

                    <div
                        class="stepper__step {{ $order->orderStatus->id === 5 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 5 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 5 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M180-80q-24.75 0-42.37-17.63Q120-115.25 120-140v-530q0-24.75 17.63-42.38Q155.25-730 180-730h110q0-78 53.5-134T475-920q80.92 0 137.96 55Q670-810 670-730h110q24.75 0 42.38 17.62Q840-694.75 840-670v530q0 24.75-17.62 42.37Q804.75-80 780-80H180Zm0-60h600v-530H180v530Zm300-290q79 0 137-58t58-137h-60q0 55-40 95t-95 40q-55 0-95-40t-40-95h-60q0 79 58 137t137 58ZM350-730h260q0-55-37.5-92.5T480-860q-55 0-92.5 37.5T350-730ZM180-140v-530 530Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-check-all"></i>
                        </div>
                        <div class="stepper__step-text">Đã nhận hàng</div>
                    </div>
                    <div
                        class="stepper__step {{ $order->orderStatus->id === 6 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 6 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 6 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="m421-298 283-283-46-45-237 237-120-120-45 45 165 166Zm59 218q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-156t86-127Q252-817 325-848.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82-31.5 155T763-197.5q-54 54.5-127 86T480-80Zm0-60q142 0 241-99.5T820-480q0-142-99-241t-241-99q-141 0-240.5 99T140-480q0 141 99.5 240.5T480-140Zm0-340Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-check-all"></i>
                        </div>
                        <div class="stepper__step-text">Hoàn thành</div>
                    </div>
                </div>
            @else
                <div class="stepper">
                    <div
                        class="stepper__step {{ $order->orderStatus->id === 1 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 1 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 1 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M222-80q-43.75 0-74.37-30.63Q117-141.25 117-185v-125h127v-570l59.8 60 59.8-60 59.8 60 59.8-60 59.8 60 60-60 60 60 60-60 60 60 60-60v695q0 43.75-30.62 74.37Q781.75-80 738-80H222Zm516-60q20 0 32.5-12.5T783-185v-595H304v470h389v125q0 20 12.5 32.5T738-140ZM357-622v-60h240v60H357Zm0 134v-60h240v60H357Zm333-134q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9Zm0 129q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9ZM221-140h412v-110H177v65q0 20 12.65 32.5T221-140Zm-44 0v-110 110Z" />
                                </svg>
                            </svg>
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <div class="stepper__step-text">Đơn hàng đã đặt</div>
                        {{-- <div class="stepper__step-date">14:39 26-05-2023</div> --}}
                    </div>

                    <!-- Chờ xác nhận hủy -->
                    <div
                        class="stepper__step {{ $order->orderStatus->id === 8 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 8 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 8 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-60q68 0 130.62-25.81Q673.24-191.61 721-240L480-480v-340q-142 0-241 98.81T140-480q0 142.37 98.81 241.19Q337.63-140 480-140Z" />
                                </svg>
                            </svg>
                        </div>
                        <div class="stepper__step-text">Chờ xác nhận hủy</div>
                    </div>

                    <!-- Đã hủy đơn -->
                    <div
                        class="stepper__step {{ $order->orderStatus->id === 9 ? 'stepper__step--finish' : 'stepper__step--gray' }}">
                        <div class="stepper__step-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" width="100px" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45"
                                    stroke="{{ $order->orderStatus->id === 9 ? 'green' : 'gray' }}" stroke-width="5"
                                    fill="white" />
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" width="48px"
                                    viewBox="0 -960 960 960"
                                    fill="{{ $order->orderStatus->id === 9 ? '#00CD00' : '#808080' }}" x="26" y="26">
                                    <path
                                        d="m346-60-76-130-151-31 17-147-96-112 96-111-17-147 151-31 76-131 134 62 134-62 77 131 150 31-17 147 96 111-96 112 17 147-150 31-77 130-134-62-134 62Zm27-79 107-45 110 45 67-100 117-30-12-119 81-92-81-94 12-119-117-28-69-100-108 45-110-45-67 100-117 28 12 119-81 94 81 92-12 121 117 28 70 100Zm107-341Zm-43 133 227-225-45-41-182 180-95-99-46 45 141 140Z" />
                                </svg>
                            </svg>
                        </div>
                        <div class="stepper__step-text">Đã hủy đơn</div>
                    </div>
                </div>
            @endif



            <svg width="1150" height="2" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="50" fill="#0088ff" />
                <rect x="50" width="45" height="50" fill="#ee4d2d" />
                <rect x="100" width="45" height="50" fill="#0088ff" />
                <rect x="150" width="45" height="50" fill="#ee4d2d" />
                <rect x="200" width="45" height="50" fill="#0088ff" />
                <rect x="250" width="45" height="50" fill="#ee4d2d" />
                <rect x="300" width="45" height="50" fill="#0088ff" />
                <rect x="350" width="45" height="50" fill="#ee4d2d" />
                <rect x="400" width="45" height="50" fill="#0088ff" />
                <rect x="450" width="45" height="50" fill="#ee4d2d" />
                <rect x="500" width="45" height="50" fill="#0088ff" />
                <rect x="550" width="45" height="50" fill="#ee4d2d" />
                <rect x="600" width="45" height="50" fill="#0088ff" />
                <rect x="650" width="45" height="50" fill="#ee4d2d" />
                <rect x="700" width="45" height="50" fill="#0088ff" />
                <rect x="750" width="45" height="50" fill="#ee4d2d" />
                <rect x="800" width="45" height="50" fill="#0088ff" />
                <rect x="850" width="45" height="50" fill="#ee4d2d" />
                <rect x="900" width="45" height="50" fill="#0088ff" />
                <rect x="950" width="45" height="50" fill="#ee4d2d" />
                <rect x="1000" width="45" height="50" fill="#0088ff" />
                <rect x="1050" width="45" height="50" fill="#ee4d2d" />
                <rect x="1100" width="10" height="50" fill="#0088ff" />
            </svg>
            <div class="row">
                <div class="col-4" style="border-right: 1px solid #000;">
                    <h5 style="font-weight: bold; color:black;">Địa chỉ nhận hàng</h5> <br>
                    <div class="order-item-contact" style="color:black;">{{ $order->user->name }} </div>
                    <div class="order-item-contact" style="color:black;">{{ $order->telephone }} </div>
                    <div class="order-item-contact" style="color:black;"> {{ $order->shipping_address }} </div>
                </div>
                <div class="col-8">
                    <div class="d-flex justify-content-between">
                        <h5 style="font-weight: bold; color:black;">Chi tiết đơn hàng</h5>

                        <div class="text-end">
                            <div class="text-end">
                                @if (in_array($order->orderStatus->id, [1, 2]))
                                    <!-- 1: Chờ xác nhận, 2: Đã xác nhận -->
                                    <a href="#"
                                       style="font-weight: bold; color: red; text-decoration: none; border-bottom: 2px solid red; padding-bottom: 2px; transition: all 0.3s ease;"
                                       data-toggle="modal" data-target="#cancelOrderModal">
                                        Hủy đơn
                                    </a>
                                @endif

                                @if ($order->orderStatus->id === 4)
                                    <!-- 4: Đã giao hàng -->
                                    <a href="#"
                                       style="font-weight: bold; color: green; text-decoration: none; border-bottom: 2px solid green; padding-bottom: 2px; transition: all 0.3s ease;"
                                       onclick="event.preventDefault(); document.getElementById('confirmForm').submit();">
                                        Xác nhận
                                    </a>
                                    <form id="confirmForm" action="{{ route('order.client.update', $order->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="order_status_id" value="5"> <!-- ID trạng thái 'Đã nhận hàng' -->
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                    <br>

                    @php
                        $totalOriginalPrice = 0; // Khởi tạo biến tổng giá gốc
                    @endphp

                    @foreach ($order->orderDetails as $item)
                        <div class="order-item mb-3">
                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <!-- Hình ảnh sản phẩm -->
                                <div class="order-item-image mr-3">
                                    @if ($item->product->image_path)
                                        <img src="{{ Storage::url($item->product->image_path) }}"
                                            alt="{{ $item->product->name }}" class="img-fluid" style="max-width: 80px;">
                                    @else
                                        Không có hình ảnh
                                    @endif
                                </div>

                                <!-- Thông tin sản phẩm -->
                                <div class="order-item-info flex-grow-1">
                                    <div style="font-weight: bold; color:black;" class="order-item-name font-weight-bold">
                                        {{ $item->product->name }}
                                    </div>
                                    <!-- Hiển thị danh mục sản phẩm -->
                                    <div class="order-item-category text-muted">
                                        Phân loại: {{ $item->product->category->name ?? 'Không có danh mục' }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="order-item-date text-muted">
                                            x{{ $item->quantity }}
                                        </div>

                                        <!-- Hiển thị giá gốc và giá đã giảm -->
                                        <div class="order-item-price text-danger font-weight-bold ml-auto">
                                            @php
                                                // Tính giá tổng (không áp dụng giảm giá)
                                                $originalTotalPrice = isset($item->original_price)
                                                    ? $item->original_price * $item->quantity
                                                    : $item->price * $item->quantity; // Nếu không có giá gốc, lấy giá bán

                                                $totalOriginalPrice += $originalTotalPrice; // Cộng vào tổng giá gốc
                                            @endphp

                                            @if ($item->original_price && $item->price < $item->original_price)
                                                <span class="text-muted"
                                                    style="font-size:14px; text-decoration: line-through; opacity: 0.5;">
                                                    {{ number_format($originalTotalPrice, 0, ',', '.') }} đ
                                                </span>
                                                <span>
                                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
                                                </span>
                                            @else
                                                <span>
                                                    {{ number_format($originalTotalPrice, 0, ',', '.') }} đ
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (in_array($order->orderStatus->id, [8, 9]))
                        <!-- Đơn hàng đã hủy -->
                        <div class="wGEXn5 mt-4">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="col-8 text-end border-end"><strong>Yêu cầu hủy</strong></td>
                                        <td class="col-4 text-end">
                                            @if ($order->notes == 'Admin')
                                                Người bán hàng
                                            @else
                                                Người mua
                                            @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="col-8 text-end border-end"><strong>Phương thức thanh toán</strong></td>
                                        <td class="col-4 text-end">
                                            <strong>
                                                @if ($order->payment_method == 'COD')
                                                    Thanh toán khi nhận hàng
                                                @elseif ($order->payment_method == 'Momo')
                                                    Thanh toán online
                                                @else
                                                    {{ $order->payment_method }}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="bg-white border rounded shadow-sm p-3 mt-3" style=" color:black;">
                                <strong>Lý do hủy: </strong>{{ $order->cancel ?? 'Không có lý do' }}
                            </div> <br>
                        </div>
                    @else
                        <!-- Đơn hàng bình thường -->
                        <div class="wGEXn5 mt-4">
                            <table class="table">
                                <tbody>
                                    <!-- Hiển thị tổng tiền hàng (chưa giảm giá) -->
                                    <tr>
                                        <td class="col-8 text-end border-end">Tổng tiền hàng</td>
                                        <td class="col-4 text-end">{{ number_format($totalOriginalPrice, 0, ',', '.') }} đ
                                        </td>
                                    </tr>

                                    @if ($appliedVoucher)
                                        <tr>
                                            <td class="col-8 text-end border-end">Giảm giá</td>
                                            <td class="col-4 text-end">
                                                @if ($appliedVoucher->discount_type == 'amount')
                                                    -{{ number_format($appliedVoucher->discount_amount, 0, ',', '.') }} đ
                                                @elseif($appliedVoucher->discount_type == 'percent')
                                                    -{{ $appliedVoucher->discount_percent }}%
                                                @else
                                                    0 đ
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="col-8 text-end border-end">Giảm giá</td>
                                            <td class="col-4 text-end" style="font-weight: bold;">0 đ</td>
                                        </tr>
                                    @endif

                                    <!-- Hiển thị thành tiền -->
                                    <tr>
                                        <td class="col-8 text-end border-end"><strong>Thành tiền</strong></td>
                                        <td class="col-4 text-end text-danger font-weight-bold">
                                            {{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                    </tr>

                                    <!-- Hiển thị phương thức thanh toán -->
                                    <tr>
                                        <td class="col-8 text-end border-end"><strong>Phương thức thanh toán</strong></td>
                                        <td class="col-4 text-end">
                                            <strong>
                                                @if ($order->payment_method == 'COD')
                                                    Thanh toán khi nhận hàng
                                                @elseif ($order->payment_method == 'Momo')
                                                    Thanh toán online
                                                @else
                                                    {{ $order->payment_method }}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        </div>
    </section>
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('order.client.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_status_id" value="7"> <!-- ID trạng thái 'Hủy' -->
                        <div class="form-group">
                            <label for="cancelReason">Lý do hủy:</label>
                            <textarea class="form-control" id="cancelReason" name="cancel" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit"   >Xác nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
