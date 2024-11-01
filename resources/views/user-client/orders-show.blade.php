@extends('layouts.master')

@section('content')
    <div class="container my-5">
        <div class="RCnc9v bg-white p-4 rounded shadow">
            <h2 class="h4 mb-4" style="color:rgb(37, 36, 36); font-weight: bold">Chi tiết </h2>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Ngày đặt</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Địa chỉ</th>
                        <th>Phương thức thanh toán</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $item)
                        <tr>
                            <td>
                                @if ($item->product->image_path)
                                    <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product->name }}"
                                        style="height: auto; width:100px;">
                                @else
                                    Không có hình ảnh
                                @endif
                            </td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $order->telephone }} / {{ $order->shipping_address }} </td>
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                <strong class="order-status" data-toggle="modal" data-target="#orderStatusModal"
                                    data-cancel="{{ $order->cancel ?? 'Không có' }}"
                                    data-notes="{{ $order->notes ?? 'Không xác định' }}">
                                    {{ $order->orderStatus->name }} <br>
                                </strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <h4>Tổng tiền: <span class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} VND</span>
                </h4>

                @if ($order->orderStatus->id !== 7)
                    <form action="{{ route('order.client.update', $order->id) }}" method="POST" class="mr-2">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelOrderModal"
                            data-order-id="{{ $order->id }}">
                            Hủy đơn
                        </button>
                    </form>
                @endif

                @if ($order->orderStatus->id === 6)
                    <form action="{{ route('order.client.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Đã nhận hàng</button>
                    </form>
                @endif
            </div>

            <a href="{{ route('order.client.user') }}" class="btn btn-secondary mt-3">Quay lại Đơn hàng</a>
        </div>
    </div>

    <!-- Modal để hủy đơn -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Thông tin hủy đơn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="cancelOrderForm" action="{{ route('order.client.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_status_id" value="7"> <!-- ID trạng thái 'Hủy' -->
                        <div class="form-group">
                            <label for="cancel">Lý do hủy:</label>
                            <textarea class="form-control" id="cancel" name="cancel" required></textarea>
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

    <!-- Modal Hiển Thị Lý Do Hủy -->
    <div class="modal fade" id="orderStatusModal" tabindex="-1" role="dialog"
        aria-labelledby="orderStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderStatusModalLabel">Thông tin đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong style="font-weight: bold; color: #333;">Người hủy:</strong> <span
                            style="font-weight: bold; color: #840000;" id="cancelUser">{{$order->notes}}</span>
                    </p>
                    <p><strong style="font-weight: bold; color: #333;">Lý do hủy:</strong><br> <span
                            style="font-weight: bold; color: #292929;" id="cancelReason">{{$order->cancel}}</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    // Thêm sự kiện khi modal hiển thị cho modal hủy đơn
    $('#cancelOrderModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Lấy đối tượng đã click
        var orderId = button.data('order-id'); // Lấy ID đơn hàng

        // Cập nhật ID đơn hàng trong form
        $('#cancelOrderForm').attr('action', '/orders/' + orderId); // Thay đổi URL cho form
    });

    // Thêm sự kiện khi modal hiển thị cho modal hiển thị lý do hủy
    $('#orderStatusModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Lấy đối tượng đã click
        var cancelReason = button.data('cancel'); // Lấy dữ liệu lý do hủy
        var cancelUser = button.data('notes'); // Lấy dữ liệu người hủy

        // Cập nhật nội dung modal
        var modal = $(this);
        modal.find('#cancelReason').text(cancelReason);
        modal.find('#cancelUser').text(cancelUser);
    });
</script>
