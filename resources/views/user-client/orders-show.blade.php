@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="container my-5">
        <div class="RCnc9v bg-white p-4 rounded shadow">
            <h2 class="h4 mb-4" style="color:rgb(37, 36, 36); font-weight: bold">Chi tiết Đơn hàng</h2>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Ngày đặt</th>
                        <th>Giá</th>
                        <th>Địa chỉ</th>
                        <th>Thanh toán</th>
                        <th>Ghi chú</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $item)
                        <tr>
                            <td>
                                @if ($item->product->image_path)
                                    <img src="{{ Storage::url($item->product->image_path) }}"
                                        alt="{{ $item->product->name }}" style="height: auto; width:100px;">
                                @else
                                    Không có hình ảnh
                                @endif
                            </td>
                            <td>{{ $item->product->name }} (x{{ $item->quantity }})</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s d/m/Y ') }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                            <td>{{ $order->telephone }} / {{ $order->shipping_address }} </td>
                            <td>{{ $order->payment_method }}</td>
                            <td>{{ $order->checkpay }}</td>
                            <td>
                                <strong>{{ $order->orderStatus->name }}</strong>
                                @if ($order->orderStatus->id == 9)
                                    bởi
                                    @if(strpos($order->notes, 'Admin') !== false)
                                        Người bán hàng
                                    @else
                                        Bạn
                                    @endif
                                    <button type="button" class="btn btn-link p-0 text-primary" data-toggle="modal"
                                        data-target="#cancelInfoModal">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <!-- Modal hiển thị lý do hủy -->
                                    <div class="modal fade" id="cancelInfoModal" tabindex="-1" role="dialog"
                                        aria-labelledby="cancelInfoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelInfoModalLabel">Lý do hủy</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ $order->cancel ?? 'Không có' }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Đóng</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>



                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div style="color:rgb(37, 36, 36); font-weight: bold">
                    Tổng thanh toán ({{ $item->quantity }} Sản phẩm):
                    <span style="color: #AA0000;">{{ number_format($order->total_amount, 0, ',', '.') }} VND</span>
                </div>


                @if (in_array($order->orderStatus->id, [1, 2]))
                    <!-- 1: Chờ xác nhận, 2: Đã xác nhận -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelOrderModal">
                        Hủy đơn
                    </button>
                @endif

                @if ($order->orderStatus->id === 4)
                    <!-- 4: Đã giao hàng -->
                    <form action="{{ route('order.client.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_status_id" value="5"> <!-- ID trạng thái 'Đã nhận hàng' -->
                        <button type="submit" class="btn btn-success">Đã nhận hàng</button>
                    </form>
                @endif
            </div>
            <br><br>
            <a href="{{ route('order.client.user') }}" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Quay lại Đơn hàng</a>
        </div>
    </div>

    <!-- Modal xác nhận hủy đơn hàng -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

<!-- Đảm bảo đã thêm jQuery và Bootstrap JS trong phần footer của layout -->
<script>
    // No need for additional JavaScript for modal handling, Bootstrap will take care of it.
</script>
