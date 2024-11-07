@extends('admin.layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
        <link rel="stylesheet" href="../../../admindb/style.css">
        <style>
            .form-group-container {
                display: flex;
                gap: 20px;
            }

            .form-group {
                flex: 1;
            }

            h1 {
                font-size: 24px;
                color: #333;
                margin-top: 20px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                font-size: 16px;
                margin-bottom: 5px;
                color: #555;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 5px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .form-group textarea {
                height: 100px;
                resize: vertical;
            }

            button {
                background-color: #28a745;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 12px;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .form-group img {
                margin-top: 10px;
                width: 50px;
                height: auto;
            }

            a.btn-success {
                background-color: #ff1616;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 10px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }

            a.btn-success:hover {
                background-color: #d2010c;
            }

            /* Hide groups by default */
            #discount_amount_group,
            #discount_percent_group {
                display: none;
            }
        </style>
    </head>

    <body style=" font-family: 'Playfair Display', serif;">

        <h1>Sửa mã giảm giá</h1> <br>

        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="code">Mã</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
            </div>

            <div class="form-group">
                <label for="discount_type">Loại giảm giá</label>
                <select id="discount_type" name="discount_type" class="form-control" onchange="toggleDiscountFields()">
                    <option value="">Chọn loại giảm giá</option>
                    <option value="amount" {{ old('discount_type', $voucher->discount_type) == 'amount' ? 'selected' : '' }}>Giảm theo giá tiền</option>
                    <option value="percent" {{ old('discount_type', $voucher->discount_type) == 'percent' ? 'selected' : '' }}>Giảm theo phần trăm</option>
                </select>
            </div>

            <div class="form-group-container">
                <!-- Discount Amount Group -->
                <div class="form-group" id="discount_amount_group">
                    <label for="discount_amount">Giảm theo giá (VND)</label>
                    <input type="number" name="discount_amount" id="discount_amount" class="form-control"
                        value="{{ old('discount_amount', $voucher->discount_amount) }}">
                </div>

                <!-- Discount Percent Group -->
                <div class="form-group" id="discount_percent_group">
                    <label for="discount_percent">Giảm theo phần trăm (%)</label>
                    <input type="number" name="discount_percent" id="discount_percent" class="form-control"
                        value="{{ old('discount_percent', $voucher->discount_percent) }}" min="0" max="100">
                </div>
            </div>

            <div class="form-group">
                <label for="min_order_value">Giá trị đơn hàng tối thiểu (VND)</label>
                <input type="number" name="min_order_value" id="min_order_value" class="form-control"
                    value="{{ old('min_order_value', $voucher->min_order_value) }}" required>
            </div>

            <div class="form-group">
                <label for="usage_limit">Lượt sử dụng</label>
                <input type="number" name="usage_limit" id="usage_limit" class="form-control"
                    value="{{ old('usage_limit', $voucher->usage_limit) }}" required>
            </div>

            <div class="form-group-container">
                <div class="form-group">
                    <label for="start_date">Thời gian bắt đầu</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                        value="{{ old('start_date', \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d\TH:i')) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="end_date">Thời gian kết thúc</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                        value="{{ old('end_date', \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d\TH:i')) }}"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
        </form>

        @if (session('success'))
            <p class="text-success">{{ session('success') }}</p>
        @endif

        <script>
            // Hàm xử lý khi người dùng chọn loại giảm giá
            function toggleDiscountFields() {
                var discountType = document.getElementById('discount_type').value;

                // Hiển thị hoặc ẩn các trường giảm giá theo giá hoặc phần trăm
                if (discountType === 'amount') {
                    document.getElementById('discount_amount_group').style.display = 'block';
                    document.getElementById('discount_percent_group').style.display = 'none';
                } else if (discountType === 'percent') {
                    document.getElementById('discount_amount_group').style.display = 'none';
                    document.getElementById('discount_percent_group').style.display = 'block';
                } else {
                    document.getElementById('discount_amount_group').style.display = 'none';
                    document.getElementById('discount_percent_group').style.display = 'none';
                }
            }

            // Đảm bảo các trường được hiển thị đúng khi trang tải lại
            document.addEventListener('DOMContentLoaded', toggleDiscountFields);
        </script>

    </html>
@endsection
