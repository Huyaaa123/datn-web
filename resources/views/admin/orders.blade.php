@extends('admin.layouts.master')
@section('content')
    <h1>Orders</h1>
    <style>
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            font-size: 14px;
        }

        .text-center {
            text-align: center;
            display: block;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
        }
        .btn-success {
            background-color: #08d839;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            font-size: 14px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .text-danger {
            color: #dc3545;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-add {
            background-color: #28a745;
        }
    </style>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Cancel</th>
                <th>User</th>
                <th>Products</th>
                <th>Total</th>
                <th>Date Order</th>
                <th>Ship</th>
                <th>Phone</th>
                <th>Payment</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ Str::limit($order->orderStatus->name, 15, '...') }}</td>
                    <td>
                        @if($order->cancel)
                            {{ Str::limit($order->cancel, 15, '...') }}
                        @else
                            No Problem
                        @endif
                    </td>
                    <td>{{ Str::limit($order->user->name, 10, '...') }}</td>
                    <td>
                        @foreach ($order->orderDetails as $item)
                            <div>
                                {{ Str::limit($item->product->name, 10, '...') }}  (SL: x{{ $item->quantity }})
                            </div>
                        @endforeach
                    </td>
                    <td>{{ number_format($order->total_amount) }} VND</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($order->shipping_address, 15, '...') }}</td>
                    <td>{{ Str::limit($order->telephone, 15, '...') }}</td>
                    <td>{{ Str::limit($order->payment_method, 15, '...') }}</td>
                    <td>
                        @if($order->notes)
                            {{ Str::limit($order->notes, 15, '...') }}
                        @else
                            No Problem
                        @endif
                    </td>
                    <td>
                        @if($order->order_status_id === 7) <!-- Kiểm tra order_status_id -->
                        <span style="color: #c82333; font-weight:bold;">Undefined</span>

                        @else
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-success">Edit</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

@endsection
