@extends('admin.layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
        <link rel="stylesheet" href="../../../admindb/style.css">
    </head>
    <style>
        .order-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .order-details {
            flex: 1;
            padding-right: 20px;
            border-right: 1px solid #ddd;
            margin-right: 20px;
        }

        .order-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .product-info {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            /* Đường viền cho từng sản phẩm */
            border-radius: 8px;
            /* Bo góc */
            background-color: #f9f9f9;
            /* Màu nền nhẹ */
        }

        .product-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .order-details {
            padding: 20px;
        }

        .product-list {
            max-height: 300px;
            /* Giới hạn chiều cao, tạo thanh cuộn nếu vượt quá */
            overflow-y: auto;
            padding-right: 10px;
            /* Khoảng trống để thanh cuộn không che nội dung */
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .product-info {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .product-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .product-details {
            flex-grow: 1;
        }

        .product-name {
            font-weight: bold;
        }

        .product-quantity {
            margin-top: 5px;
            color: #555;
        }

        .total-price {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 5px;
        }

        .order-summary {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 16px;
        }

        .order-summary p {
            margin: 5px 0;
        }

        #cancel-reason-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #cancel-reason-container strong {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
        }

        #cancel_reason {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        #cancel-reason-container .btn-danger {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        #cancel-reason-container .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>

    <body style=" font-family: 'Playfair Display', serif;">
        <h1>Cập nhật đơn hàng</h1>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm">
            @csrf
            @method('PUT')

            @if (in_array($order->orderStatus->id, [1, 2, 3, 4, 5, 6]))
                <svg width="100%" height="160" viewBox="0 0 1500 200" xmlns="http://www.w3.org/2000/svg">
                    <line x1="195" y1="100" x2="450" y2="100"
                        stroke="{{ $order->orderStatus->id >= 1 ? 'green' : 'gray' }}" stroke-width="5" />
                    <line x1="450" y1="100" x2="650" y2="100"
                        stroke="{{ $order->orderStatus->id >= 2 ? 'green' : 'gray' }}" stroke-width="5" />
                    <line x1="650" y1="100" x2="950" y2="100"
                        stroke="{{ $order->orderStatus->id >= 3 ? 'green' : 'gray' }}" stroke-width="5" />
                    <line x1="950" y1="100" x2="1200" y2="100"
                        stroke="{{ $order->orderStatus->id >= 4 ? 'green' : 'gray' }}" stroke-width="5" />
                    <line x1="1200" y1="100" x2="1450" y2="100"
                        stroke="{{ $order->orderStatus->id >= 5 ? 'green' : 'gray' }}" stroke-width="5" />


                    <!-- Bước 1: Đơn hàng đã đặt -->
                    <circle cx="150" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 1 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="150" y="170" font-size="18" text-anchor="middle" fill="black">Đơn hàng đã đặt</text>

                    <!-- Existing SVG Icon -->
                    <svg x="126" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 1 ? '#00CD00' : '#808080' }}">
                        <path
                            d="M222-80q-43.75 0-74.37-30.63Q117-141.25 117-185v-125h127v-570l59.8 60 59.8-60 59.8 60 59.8-60 59.8 60 60-60 60 60 60-60 60 60 60-60v695q0 43.75-30.62 74.37Q781.75-80 738-80H222Zm516-60q20 0 32.5-12.5T783-185v-595H304v470h389v125q0 20 12.5 32.5T738-140ZM357-622v-60h240v60H357Zm0 134v-60h240v60H357Zm333-134q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9Zm0 129q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9ZM221-140h412v-110H177v65q0 20 12.65 32.5T221-140Zm-44 0v-110 110Z" />
                    </svg>

                    <!-- New Text Below "Đơn hàng đã đặt" -->
                    <text x="150" y="200" font-size="18" text-anchor="middle" fill="black">
                        {{ $order->created_at->format(' H:i:s d/m/Y') }}
                    </text>

                    <!-- Hiển thị trạng thái và thời gian xác nhận đơn hàng -->
                    <circle cx="400" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 2 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="400" y="170" font-size="18" text-anchor="middle" fill="black">Xác nhận đơn hàng</text>

                    <!-- Hiển thị thời gian xác nhận nếu có -->
                    <text x="400" y="200" font-size="18" text-anchor="middle" fill="black">
                        @if ($order->confirmed)
                            {{ \Carbon\Carbon::parse($order->confirmed)->format('H:i:s d/m/Y ') }}
                        @else
                            ___
                        @endif

                    </text>

                    <svg x="376" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 2 ? '#00CD00' : '#808080' }}">
                        <path
                            d="M540-420q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM220-280q-24.75 0-42.37-17.63Q160-315.25 160-340v-400q0-24.75 17.63-42.38Q195.25-800 220-800h640q24.75 0 42.38 17.62Q920-764.75 920-740v400q0 24.75-17.62 42.37Q884.75-280 860-280H220Zm100-60h440q0-42 29-71t71-29v-200q-42 0-71-29t-29-71H320q0 42-29 71t-71 29v200q42 0 71 29t29 71Zm480 180H100q-24.75 0-42.37-17.63Q40-195.25 40-220v-460h60v460h700v60ZM220-340v-400 400Z" />
                    </svg>


                    <!-- Bước 3: Đang giao hàng -->
                    <circle cx="650" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 3 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="650" y="170" font-size="18" text-anchor="middle" fill="black">Đang giao hàng</text>

                    <text x="650" y="200" font-size="18" text-anchor="middle" fill="black">
                        @if ($order->on_delivery)
                            {{ \Carbon\Carbon::parse($order->on_delivery)->format('H:i:s d/m/Y ') }}
                        @else
                            ___
                        @endif

                    </text>

                    <svg x="626" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 3 ? '#00CD00' : '#808080' }}">
                        <path
                            d="M224.12-161q-49.12 0-83.62-34.42Q106-229.83 106-279H40v-461q0-24 18-42t42-18h579v167h105l136 181v173h-71q0 49.17-34.38 83.58Q780.24-161 731.12-161t-83.62-34.42Q613-229.83 613-279H342q0 49-34.38 83.5t-83.5 34.5Zm-.12-60q24 0 41-17t17-41q0-24-17-41t-41-17q-24 0-41 17t-17 41q0 24 17 41t41 17ZM100-339h22q17-27 43.04-43t58-16q31.96 0 58.46 16.5T325-339h294v-401H100v401Zm631 118q24 0 41-17t17-41q0-24-17-41t-41-17q-24 0-41 17t-17 41q0 24 17 41t41 17Zm-52-204h186L754-573h-75v148ZM360-529Z" />
                    </svg>

                    <!-- Bước 4: Đã giao hàng -->
                    <circle cx="900" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 4 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="900" y="170" font-size="18" text-anchor="middle" fill="black">Đã giao hàng</text>

                    <text x="900" y="200" font-size="18" text-anchor="middle" fill="black">
                        @if ($order->delivered)
                            {{ \Carbon\Carbon::parse($order->delivered)->format('H:i:s d/m/Y ') }}
                        @else
                            ___
                        @endif

                    </text>

                    <svg x="876" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 4 ? '#00CD00' : '#808080' }}">
                        <path
                            d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h600q24 0 42 18t18 42v600q0 24-18 42t-42 18H180Zm0-60h600v-136H634q-26 40-67.5 61.5T480-233q-45 0-86.5-21.5T326-316H180v136Zm300.25-113Q521-293 554-316.5t56-59.5h170v-404H180v404h170q23 36 56.25 59.5 33.24 23.5 74 23.5ZM480-422 327-575l43-43 80 80v-189h60v189l80-80 43 43-153 153ZM180-180h600-600Z" />
                    </svg>

                    <!-- Bước 5: Đã nhận hàng -->
                    <circle cx="1150" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 5 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="1150" y="170" font-size="18" text-anchor="middle" fill="black">Đã nhận hàng</text>

                    <text x="1150" y="200" font-size="18" text-anchor="middle" fill="black">
                        @if ($order->received)
                            {{ \Carbon\Carbon::parse($order->received)->format('H:i:s d/m/Y ') }}
                        @else
                            ___
                        @endif

                    </text>

                    <svg x="1126" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 5 ? '#00CD00' : '#808080' }}">
                        <path
                            d="M180-80q-24.75 0-42.37-17.63Q120-115.25 120-140v-530q0-24.75 17.63-42.38Q155.25-730 180-730h110q0-78 53.5-134T475-920q80.92 0 137.96 55Q670-810 670-730h110q24.75 0 42.38 17.62Q840-694.75 840-670v530q0 24.75-17.62 42.37Q804.75-80 780-80H180Zm0-60h600v-530H180v530Zm300-290q79 0 137-58t58-137h-60q0 55-40 95t-95 40q-55 0-95-40t-40-95h-60q0 79 58 137t137 58ZM350-730h260q0-55-37.5-92.5T480-860q-55 0-92.5 37.5T350-730ZM180-140v-530 530Z" />
                    </svg>
                    <!-- Bước 6: Hoàn thành -->
                    <circle cx="1400" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 6 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="1400" y="170" font-size="18" text-anchor="middle" fill="black">Hoàn thành</text>

                    <text x="1400" y="200" font-size="18" text-anchor="middle" fill="black">
                        @if ($order->complete)
                            {{ \Carbon\Carbon::parse($order->complete)->format('H:i:s d/m/Y ') }}
                        @else
                            ___
                        @endif

                    </text>

                    <svg x="1376" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 6 ? '#00CD00' : '#808080' }}">
                        <path
                            d="m421-298 283-283-46-45-237 237-120-120-45 45 165 166Zm59 218q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-156t86-127Q252-817 325-848.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82-31.5 155T763-197.5q-54 54.5-127 86T480-80Zm0-60q142 0 241-99.5T820-480q0-142-99-241t-241-99q-141 0-240.5 99T140-480q0 141 99.5 240.5T480-140Zm0-340Z" />
                    </svg>
                </svg>
            @else
                <svg width="100%" height="160" viewBox="0 0 600 200" xmlns="http://www.w3.org/2000/svg">
                    <!-- Đường kết nối giữa các bước -->
                    <line x1="195" y1="100" x2="400" y2="100"
                        stroke="{{ $order->orderStatus->id >= 1 ? 'green' : 'gray' }}" stroke-width="5" />

                    <!-- Bước 1: Đơn hàng đã đặt -->
                    <circle cx="150" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 1 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="150" y="170" font-size="18" text-anchor="middle" fill="black">Đơn hàng đã đặt</text>

                    <text x="150" y="200" font-size="18" text-anchor="middle" fill="black">
                        {{ $order->created_at->format(' H:i:s d/m/Y') }}
                    </text>

                    <svg x="126" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 1 ? '#00CD00' : '#808080' }}">
                        <path
                            d="M222-80q-43.75 0-74.37-30.63Q117-141.25 117-185v-125h127v-570l59.8 60 59.8-60 59.8 60 59.8-60 59.8 60 60-60 60 60 60-60 60 60 60-60v695q0 43.75-30.62 74.37Q781.75-80 738-80H222Zm516-60q20 0 32.5-12.5T783-185v-595H304v470h389v125q0 20 12.5 32.5T738-140ZM357-622v-60h240v60H357Zm0 134v-60h240v60H357Zm333-134q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9Zm0 129q-12 0-21-9t-9-21q0-12 9-21t21-9q12 0 21 9t9 21q0 12-9 21t-21 9ZM221-140h412v-110H177v65q0 20 12.65 32.5T221-140Zm-44 0v-110 110Z" />
                    </svg>

                    <!-- Bước 3: Đã hủy đơn -->
                    <circle cx="400" cy="100" r="45" fill="white"
                        stroke="{{ $order->orderStatus->id >= 9 ? 'green' : 'gray' }}" stroke-width="5" />
                    <text x="400" y="170" font-size="18" text-anchor="middle" fill="black">Đã hủy đơn</text>

                    <text x="400" y="200" font-size="18" text-anchor="middle" fill="black">
                        @if ($order->canceled)
                            {{ \Carbon\Carbon::parse($order->canceled)->format('H:i:s d/m/Y ') }}
                        @else
                            ___
                        @endif

                    </text>

                    <svg x="376" y="76" width="48px" height="48px" viewBox="0 -960 960 960"
                        fill="{{ $order->orderStatus->id >= 9 ? '#00CD00' : '#808080' }}">
                        <path
                            d="m346-60-76-130-151-31 17-147-96-112 96-111-17-147 151-31 76-131 134 62 134-62 77 131 150 31-17 147 96 111-96 112 17 147-150 31-77 130-134-62-134 62Zm27-79 107-45 110 45 67-100 117-30-12-119 81-92-81-94 12-119-117-28-69-100-108 45-110-45-67 100-117 28 12 119-81 94 81 92-12 121 117 28 70 100Zm107-341Zm-43 133 227-225-45-41-182 180-95-99-46 45 141 140Z" />
                    </svg>
                </svg>
            @endif
            <svg width="930" height="2" xmlns="http://www.w3.org/2000/svg">
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
            </svg>
            <div class="order-container">
                <div class="order-details">
                    <h2 class="order-title">Đơn hàng #{{ $order->id }}</h2>

                    <div class="product-list">
                        @foreach ($order->orderDetails as $item)
                            <div class="product-info">
                                <div class="product-image">
                                    @if ($item->product->image_path)
                                        <img src="{{ Storage::url($item->product->image_path) }}"
                                            alt="{{ $item->product->name }}">
                                    @else
                                        Không có hình ảnh
                                    @endif
                                </div>
                                <div class="product-details">
                                    <div class="product-name">{{ $item->product->name }}</div>
                                    <div class="product-category">
                                        {{ $item->product->category->name ?? 'Không có danh mục' }}</div>
                                    <div class="product-quantity">
                                        {{ number_format($item->product->price) }}₫ x{{ $item->quantity }} =
                                        <strong>{{ number_format($item->product->price * $item->quantity) }}₫</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="total-price"><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}₫</div>
                </div>

                <div class="order-summary">
                    <p style="color:black;"><strong style="font-weight: bold; color:black;">Khách hàng:</strong> {{ $order->user->name ?? 'Không có' }}</p>
                    <p style="color:black;"><strong style="font-weight: bold; color:black;">Địa chỉ giao hàng:</strong> {{ $order->telephone }}, {{ $order->shipping_address }}</p>
                    <p style="color:black;"><strong style="font-weight: bold; color:black;">Phương thức:</strong> {{ $order->payment_method }}</p>
                    <p style="color:black;"><strong style="font-weight: bold; color:black;">Trạng thái:</strong> {{ $order->checkpay }}</p>
                    @if ($order->order_status_id == 1)
                        <button type="submit" name="order_status_id" value="2" class="btn btn-secondary">Xác
                            nhận</button>
                        <button type="button" id="cancel-button" class="btn btn-danger">Hủy đơn</button>
                    @elseif($order->order_status_id == 2)
                        <button type="submit" name="order_status_id" value="3" class="btn btn-info">Giao
                            hàng</button>
                        <button type="button" id="cancel-button" class="btn btn-danger">Hủy đơn</button>
                    @elseif($order->order_status_id == 3)
                        <button type="submit" name="order_status_id" value="4" class="btn btn-warning">Đã giao
                            hàng</button>
                    @elseif($order->order_status_id == 5)
                        <button type="submit" name="order_status_id" value="6" class="btn btn-success">Hoàn
                            thành</button>
                    @elseif($order->order_status_id == 8)
                        <button type="submit" name="order_status_id" value="9" class="btn btn-success">Đồng ý
                            hủy</button>
                    @elseif($order->order_status_id == 9)
                        <strong style="font-weight: 500; color:red;">Đơn hàng đã bị hủy.</strong>
                        <p style="color:black;"><strong style="font-weight: bold; color:black;">Người hủy:</strong>  {{$order->notes}}</p>
                        <p style="color:black;"><strong style="font-weight: bold; color:black;">Lý do: </strong> {{ $order->cancel }}</p>
                    @elseif($order->order_status_id == 6)
                        <p style="color: green" class="text-success">Đơn hàng đã hoàn thành</p>
                    @endif
                    <div id="cancel-reason-container" style="display:none;">
                        <strong>Lý do hủy</strong>
                        <input type="text" name="cancel" id="cancel_reason" class="form-control">
                        <button type="submit" name="order_status_id" value="9" class="btn btn-danger">Xác nhận
                        </button>
                    </div>
                </div>
        </form>

    </body>
    <script>
        document.getElementById('cancel-button').addEventListener('click', function() {
            // Hiển thị ô nhập lý do hủy đơn
            document.getElementById('cancel-reason-container').style.display = 'block';
        });
    </script>

    </html>
@endsection
