@extends('admin.layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
        <link rel="stylesheet" href="../../admindb/style.css">
        <style>
            .form-group-container {
                display: flex;
                gap: 20px;
                /* Tạo khoảng cách giữa các cột */
            }

            .form-group {
                flex: 1;
                /* Mỗi form-group chiếm 50% chiều rộng */
            }

            h1 {
                font-size: 24px;
                margin-bottom: 20px;
                color: #333;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                font-size: 16px;
                margin-bottom: 8px;
                color: #555;
            }

            input.form-control {
                width: 100%;
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .text-danger {
                color: #dc3545;
                font-size: 14px;
                margin-top: 5px;
            }

            button.btn-primary {
                background-color: #007bff;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 12px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }

            a.btn-success {
                background-color: #ff1616;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 11px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }

            select.form-control {
                width: 100%;
                padding: 5px;
                font-size: 16px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;
            }
        </style>
    </head>

    <body style="font-family: 'Playfair Display', serif;">
        <h1>Thêm mã giảm giá</h1>

        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="code">Mã</label>
                <input type="text" id="code" name="code" class="form-control"  value="{{ old('code') }}">
            </div>

            <div class="form-group">
                <label for="discount_type">Loại giảm giá</label>
                <select id="discount_type" name="discount_type" class="form-control" onchange="toggleDiscountFields()">
                    <option value="">Chọn loại giảm giá</option>
                    <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Giảm theo giá tiền</option>
                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Giảm theo phần trăm</option>
                </select>
            </div>

            <div class="form-group-container">
                <div class="form-group" id="discount_amount_group" style="display: none;">
                    <label for="discount_amount">Giảm theo giá</label>
                    <input type="number" id="discount_amount" name="discount_amount" class="form-control" value="{{ old('discount_amount') }}">
                </div>

                <div class="form-group" id="discount_percent_group" style="display: none;">
                    <label for="discount_percent">Giảm theo phần trăm</label>
                    <input type="number" id="discount_percent" name="discount_percent" class="form-control" value="{{ old('discount_percent') }}" min="0" max="100">
                </div>
            </div>

            <div class="form-group">
                <label for="min_order_value">Giá trị đơn hàng tối thiểu</label>
                <input type="number" id="min_order_value" name="min_order_value" class="form-control"  value="{{ old('min_order_value') }}">
            </div>

            <div class="form-group">
                <label for="usage_limit">Lượt sử dụng</label>
                <input type="number" id="usage_limit" name="usage_limit" class="form-control"  value="{{ old('usage_limit') }}">
            </div>

            <div class="form-group-container">
                <div class="form-group">
                    <label for="start_date">Thời gian bắt đầu:</label>
                    <input type="datetime-local" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}" >
                    @error('start_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">Thời gian kết thúc:</label>
                    <input type="datetime-local" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}" >
                    @error('end_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-success">Cancel</a>
        </form>
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
    </body>
    <script>
        function toggleDiscountFields() {
            var discountType = document.getElementById('discount_type').value;
            document.getElementById('discount_amount_group').style.display = discountType === 'amount' ? 'block' : 'none';
            document.getElementById('discount_percent_group').style.display = discountType === 'percent' ? 'block' : 'none';
        }

        // Gọi hàm toggleDiscountFields khi tải trang để hiển thị đúng trường hợp chọn cũ
        document.addEventListener('DOMContentLoaded', toggleDiscountFields);
    </script>
    </html>
@endsection
