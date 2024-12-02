@extends('admin.layouts.master')
@section('content')
    <style>
        /* Tùy chỉnh bảng */
        .custom-table th,
        .custom-table td {
            font-size: 14px;
            text-align: center;
            vertical-align: middle;
        }

        /* Chỉnh khoảng cách */
        .table {
            margin-bottom: 1rem;
        }

        /* Biểu đồ */
        .chart-container {
            position: relative;
            height: 300px;
            /* Chiều cao tối đa cho container */
            width: 100%;
            overflow: hidden;
            /* Tránh kéo dài */
        }

        canvas {
            max-width: 100%;
            max-height: 100%;
            /* Tránh canvas vượt khỏi container */
        }

        /* Định dạng bố cục */
        .col-md-8,
        .col-md-4 {
            margin-bottom: 20px;
        }
    </style>

    <div class="container-fluid">
        <h1 class="mt-4" style="margin-bottom: 10px;">Thống kê</h1>
        <div class="row mb-4" style="display: flex; align-items: center;">
            <!-- Form lọc theo ngày -->
            <div class="col-md-12" style="flex: 1; margin-bottom: 10px;">
                <form method="GET" action="{{ route('admin.statistic') }}"
                    style="display: flex; align-items: center; background-color: #f8f9fa; padding: 10px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                    <div class="row" style="display: flex; width: 100%; gap: 5px;"> <!-- Giảm gap -->
                        <div class="col" style="flex: 1; font-size: 16px; padding-right: 5px;">
                            <!-- Giảm khoảng cách bên phải -->
                            <label for="start_date" style="font-weight: bold; color: #495057;">Từ ngày:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ $startDate->format('Y-m-d') }}"
                                style="width: 110px; padding: 5px; border: 1px solid #ced4da; border-radius: 5px; background-color: #ffffff;">
                        </div>
                        <div class="col" style="flex: 1; padding-right: 5px;"> <!-- Giảm khoảng cách bên phải -->
                            <label for="end_date" style="font-weight: bold; font-size: 16px; color: #495057;">Đến
                                ngày:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ $endDate->format('Y-m-d') }}"
                                style="width: 110px; padding: 5px; border: 1px solid #ced4da; border-radius: 5px; background-color: #ffffff;">
                        </div>
                        <div class="col-auto" style="flex-shrink: 0; margin-right: 50%;">
                            <button type="submit" class="btn btn-primary"
                                style="padding: 6px 12px; font-size: 14px; border-radius: 5px; display: flex; justify-content: flex-start; background-color: rgb(141, 254, 158); transition: background-color 0.3s;">
                                Lọc
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <!-- Bố cục biểu đồ và top 5 -->
        <div class="row">
            <!-- Biểu đồ doanh thu -->
            <div class="col-md-8">
                <h4 style="font-size: 16px;">Tổng doanh thu</h4>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; gap: 20px; padding: 10px;">
                <!-- Top sản phẩm bán chạy -->
                <div style="flex: 1; padding: 10px; margin-top: -20px;">
                    <h4 style="font-size: 16px;">Top sản phẩm bán chạy</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    STT</th>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    Tên sản phẩm</th>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    Số lượng bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topProducts as $index => $product)
                                <tr>
                                    <td style="color:black;padding: 8px 12px; border: 1px solid #ddd; text-align: center;">
                                        {{ $index + 1 }}</td>
                                    <td style="color:black;padding: 8px 12px; border: 1px solid #ddd; text-align: center;">
                                        {{ $product->product->name }}</td>
                                    <td style="color:black;padding: 8px 12px; border: 1px solid #ddd; text-align: center;">
                                        {{ $product->total_sold }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        style="color:black;padding: 8px 12px; text-align: center; border: 1px solid #ddd;">Không có dữ
                                        liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="flex: 1; padding: 10px; margin-top: -20px;">
                    <h4 style="font-size: 16px;">Top đơn hàng mới nhất</h4>
                    <table style="width: 100%; border-collapse: collapse; ">
                        <thead>
                            <tr>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    STT</th>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    Sản phẩm</th>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    Ngày đặt hàng</th>
                                <th
                                    style="padding: 8px 12px; text-align: center; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">
                                    Tổng tiền (₫)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $index => $order)
                                <tr>
                                    <td style="padding: 8px 12px;color:black; border: 1px solid #ddd; text-align: center;">
                                        {{ $index + 1 }}</td>
                                    <td style="padding: 8px 12px;color:black; border: 1px solid #ddd; text-align: center;">
                                        <!-- Hiển thị các sản phẩm trong đơn hàng từ order_details -->
                                        @foreach ($order->orderDetails as $detail)
                                            <div>{{ $detail->product->name }} (SL: {{ $detail->quantity }})</div>
                                        @endforeach
                                    </td>
                                    <td style="padding: 8px 12px;color:black; border: 1px solid #ddd; text-align: center;">
                                        {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                    <td style="padding: 8px 12px;color:black; border: 1px solid #ddd; text-align: center;">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="color:black;padding: 8px 12px; text-align: center; border: 1px solid #ddd;">Không có dữ
                                        liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

        <!-- Biểu đồ doanh thu -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const revenueChartCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueData = @json($totalRevenueData);

            new Chart(revenueChartCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(data => {
                        // Chuyển đổi ngày sang định dạng ngày/tháng/năm
                        const date = new Date(data.date);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
                        const year = date.getFullYear();
                        return `${day}/${month}/${year}`; // Định dạng ngày/tháng/năm
                    }),
                    datasets: [{
                        label: 'Tổng doanh thu (₫)',
                        data: revenueData.map(data => data.total_revenue),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true, // Giữ đúng tỷ lệ trong container
                    plugins: {
                        legend: {
                            labels: {
                                color: 'black' // Màu chữ của nhãn trong legend
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: 'black' // Màu chữ của trục X
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'black', // Màu chữ của trục Y
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        </script>


    @endsection
