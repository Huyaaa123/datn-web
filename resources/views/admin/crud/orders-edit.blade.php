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

            .form-inline {
                display: flex;
                justify-content: space-between;
            }

            .form-inline .form-group {
                flex: 1;
                margin-right: 10px;
            }

            .form-inline .form-group:last-child {
                margin-right: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table,
            th,
            td {
                border: 1px solid #ccc;
            }

            th,
            td {
                padding: 10px;
                text-align: left;
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
        </style>
    </head>

    <body>
        <h1>Edit Orders</h1>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="status">Status</label>
                <select name="order_status_id" id="order_status_id">
                    @foreach ($orderStatus as $status)
                        <option disabled value="{{ $status->id }}" {{ $order->order_status_id === $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-inline">
                <div class="form-group">
                    <label for="user">Username</label>
                    <input type="text" id="user" value="{{ $order->user->name ?? 'Không xác định' }}" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="text" id="total" value="{{ number_format($order->total_amount) }} VND" class="form-control" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="product">Product</label>
                @foreach ($order->orderDetails as $item)
                    <input type="text" id="product" value="{{ $item->product->name }} ( x{{ $item->quantity }} ) ({{ number_format($item->price) }} VND)" class="form-control" readonly>
                @endforeach
            </div>
            <div class="form-group">
                <label for="order_date">Date Order</label>
                <input type="text" id="order_date" value="{{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s d/m/Y') }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="shipping_address">Address Ship</label>
                <input type="text" id="shipping_address" value="{{ $order->telephone }} / {{ $order->shipping_address }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment</label>
                <input type="text" id="payment_method" value="{{ $order->payment_method }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="checkpay">Checkpay</label>
                <input type="text" id="checkpay" value="{{ $order->checkpay }}" class="form-control" readonly>
            </div>

            @if ($order->order_status_id == 1)
                <!-- 1: trạng thái ban đầu (Pending) -->
                <button type="submit" name="order_status_id" value="2" class="btn btn-secondary">Xác nhận</button>
                <button type="button" id="cancel-button" class="btn btn-danger">Hủy đơn</button>
            @elseif($order->order_status_id == 2)
                <!-- 2: trạng thái đã xác nhận -->
                <button type="submit" name="order_status_id" value="3" class="btn btn-info">Giao hàng</button>
                <button type="button" id="cancel-button" class="btn btn-danger">Hủy đơn</button>
            @elseif($order->order_status_id == 3)
                <!-- 3: đang giao hàng -->
                <button type="submit" name="order_status_id" value="4" class="btn btn-warning">Đã giao hàng</button>
            @elseif($order->order_status_id == 5)
                <!-- 4: đã giao hàng -->
                <button type="submit" name="order_status_id" value="6" class="btn btn-success">Hoàn thành</button>
            @elseif($order->order_status_id == 8)

                <button type="submit" name="order_status_id" value="9" class="btn btn-success">Đồng ý hủy</button>
            @elseif($order->order_status_id == 9)
                <!-- 9: đã hủy -->
                <p class="text-danger">Đơn hàng đã hủy</p>
            @elseif($order->order_status_id == 6)
                <!-- 6: đã hoàn thành -->
                <p class="text-success">Đơn hàng đã hoàn thành</p>
            @endif

            <!-- Ô nhập lý do hủy đơn, ẩn theo mặc định -->
            <div id="cancel-reason-container" style="display:none;">
                <label for="cancel_reason">Lý do hủy</label>
                <input type="text" name="cancel" id="cancel_reason" class="form-control">
                <button type="submit" name="order_status_id" value="9" class="btn btn-danger">Xác nhận hủy</button>
            </div>
        </form>

        <script>
            document.getElementById('cancel-button').addEventListener('click', function() {
                // Hiển thị ô nhập lý do hủy đơn
                document.getElementById('cancel-reason-container').style.display = 'block';
            });
        </script>

    </body>

    </html>
@endsection
