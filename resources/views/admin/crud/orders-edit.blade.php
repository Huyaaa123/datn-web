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

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="status">Status</label>
                <select name="order_status_id" id="order_status_id" required>
                    @foreach ($orderStatus as $status)
                        <option value="{{ $status->id }}" {{ $order->order_status_id === $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="cancel-reason-group" style="{{ $order->cancel ? '' : 'display: none;' }}">
                <label for="cancel">Reason</label>
                <input type="text" name="cancel" id="cancel" value="{{ $order->cancel }}" class="form-control">
            </div>

            <div class="form-inline">
                <div class="form-group">
                    <label for="user">Username</label>
                    <input type="text" id="user" value="{{ $order->user->name ?? 'Không xác định' }}"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="text" id="total" value="{{ number_format($order->total_amount) }} VND"
                        class="form-control" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="product">Product</label>
                @foreach ($order->orderDetails as $item)
                    <input type="text" id="product" value="{{ $item->product->name }} (SL x{{ $item->quantity }}) "
                        class="form-control" readonly>
                @endforeach
            </div>
            <div class="form-group">
                <label for="order_date">Date Order</label>
                <input type="text" id="order_date"
                    value="{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="shipping_address">Address Ship</label>
                <input type="text" id="shipping_address" value="{{ $order->shipping_address }}" class="form-control"
                    readonly>
            </div>

            <div class="form-group">
                <label for="telephone">Telephone</label>
                <input type="text" id="telephone" value="{{ $order->telephone }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment</label>
                <input type="text" id="payment_method" value="{{ $order->payment_method }}" class="form-control"
                    readonly>
            </div>



            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <script>
            document.getElementById('order_status_id').addEventListener('change', function() {
                const cancelReasonGroup = document.getElementById('cancel-reason-group');
                cancelReasonGroup.style.display = this.options[this.selectedIndex].text === 'Đã huỷ' ? '' : 'none';
            });
        </script>
    </body>

    </html>
@endsection
