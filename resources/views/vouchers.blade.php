@extends('layouts.master')

@section('content')
    <style>
        /* Định nghĩa kiểu cho thẻ voucher */
        .voucher-card {
            display: flex;
            flex-direction: row;
            border: 1px solid #007bff;
            /* Đổi màu từ cam sang xanh dương */
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            max-width: 100%;
            /* Đảm bảo thẻ chiếm toàn bộ chiều rộng có sẵn */
        }

        .voucher-left {
            flex: 1;
            background-color: #c5c4c4;
            padding: 8px;
            display: flex;
            /* Đảm bảo thẻ chứa ảnh sử dụng flexbox */
            justify-content: center;
            /* Căn giữa ảnh theo chiều ngang */
            align-items: center;
            /* Căn giữa ảnh theo chiều dọc */
        }

        .voucher-left img {
            width: 60%;
            /* Giảm kích thước logo */
            height: auto;
            /* Tự động điều chỉnh chiều cao */
            object-fit: cover;
        }


        /* Thẻ nội dung */
        .voucher-right {
            flex: 2;
            padding: 15px;
            background-color: white;
            position: relative;
        }

        /* Tiêu đề và thông tin voucher */
        .voucher-title {
            font-weight: bold;
            font-size: 16px;
            /* Giảm kích thước chữ */
            color: #333;
        }

        .voucher-info p {
            font-size: 13px;
            color: #555;
        }

        /* Hiệu ứng hover */
        .voucher-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        /* Bootstrap grid: 3 cột mỗi hàng */
        .voucher-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .voucher-col {
            flex: 1;
            max-width: 32%;
            /* Mỗi cột sẽ chiếm 1/3 chiều rộng */
            margin-bottom: 20px;
        }
    </style>

    <div class="container">
        <div class="col-md-12 mb-0">
            <strong class="text-black">Trang chủ</strong>
            <span class="mx-2 mb-0">/</span>
            <strong class="text-black">Kho Vouchers</strong>
        </div>
        <br>

        <div class="voucher-row">
            @foreach ($vouchers as $voucher)
                <div class="voucher-col">
                    <div class="voucher-card">
                        <div class="voucher-left">
                            <img src="https://cdn.iconscout.com/icon/free/png-256/free-rolex-logo-icon-download-in-svg-png-gif-file-formats--brand-fashion-pack-logos-icons-2854283.png?f=webp&w=256"
                                alt="Voucher Image">
                        </div>
                        <div class="voucher-right">
                            <div class="voucher-title">{{ $voucher->code }} -

                                @if ($voucher->discount_percent)
                                    Giảm {{ $voucher->discount_percent }}%
                                @elseif ($voucher->discount_amount)
                                    Giảm {{ number_format($voucher->discount_amount) }}
                                @else
                                    Không có giảm giá
                                @endif
                            </div>
                            <div class="voucher-info">
                                <p>Đơn Tối Thiểu {{ number_format($voucher->min_order_value) }} VND</p>
                                <p>Hạn sử dụng: {{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
