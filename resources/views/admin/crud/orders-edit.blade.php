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
        </style>
    </head>

    <body>
        <h1>Edit Orders</h1>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select name="order_status_id" id="order_status_id" required>
                    @foreach ($orderStatus as $status)
                        <option value="{{ $status->id }}" {{ $order->order_status_id === $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="form-group" id="cancel-reason-group" style="{{ $order->status === 'Đã huỷ' ? '' : 'display: none;' }}">
                <label for="cancel">Lý do hủy</label>
                <input type="text" name="cancel" id="cancel" value="{{ $order->cancel }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
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
