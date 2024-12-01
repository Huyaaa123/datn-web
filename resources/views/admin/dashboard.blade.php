@extends('admin.layouts.master')
@section('content')
<style>
    circle.positive {
    stroke: green; /* Màu xanh cho giá trị dương */
    transition: stroke 0.3s ease;
}

circle.negative {
    stroke: red; /* Màu đỏ cho giá trị âm */
    transition: stroke 0.3s ease;
}

</style>
<h1>Bảng điều khiển</h1>
    <!-- Analyses -->
    <div class="analyse">
        <div class="sales">
            <div class="status">
                <div class="info">
                    <h3>Doanh thu hôm nay</h3>
                    <h1>{{ number_format($totalSales, 0, ',', '.') }}₫</h1> <!-- Hiển thị tổng doanh thu của ngày hôm nay -->
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <p style="font-size: 16px; color: {{ $salesChangePercentage < 0 ? 'red' : 'green' }};">
                            @if($salesChangePercentage > 0)
                                +{{ number_format($salesChangePercentage, 0) }}%
                            @elseif($salesChangePercentage < 0)
                                {{ number_format($salesChangePercentage, 0) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sales">
            <div class="status">
                <div class="info">
                    <h3>Đơn hàng mới hôm nay</h3>
                    <h1 style="text-align: center;">{{ $newOrdersToday }}</h1> <!-- Hiển thị số đơn hàng hôm nay -->
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <p style="font-size: 16px; color: {{ $orderChangePercentage < 0 ? 'red' : 'green' }}">
                            {{ $orderChangePercentage > 0 ? '+' : '' }}{{ number_format($orderChangePercentage, 0) }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="visits">
            <div class="status">
                <div class="info">
                    <h3>Người dùng mới đăng ký</h3>
                    <h1 style="text-align: center;">{{ number_format($totalUsersToday) }}</h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <p style="font-size: 16px; color: {{ $usersGrowthPercentage < 0 ? 'red' : 'green' }}">
                            {{ number_format($usersGrowthPercentage, 0) }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Analyses -->

    <!-- New Users Section -->
    <div class="new-users">
        <h2>Top khách hàng tiềm năng</h2>
        <div class="user-list">
            @if ($topCustomers->isEmpty())
                <div class="user">
                    <h2>Chưa có khách hàng tiềm năng mới.</h2>
                </div>
            @else
                @foreach ($topCustomers as $key => $user)
                    <div class="user">
                        <h2 style="color:black;">(#{{$user->id}}){{ $user->name }}</h2>
                        <p style="font-size:16px; color:black; font-weight:500">Đã tham gia: <span style="color:rgb(54, 54, 55); font-weight:bold;font-size:16px;">{{ $user->created_at->diffForHumans() }}</span></p>
                        <p style="font-size:16px; color:black; font-weight:500">Đã mua: <span  style="color:rgb(0, 38, 255); font-weight:bold;font-size:16px;">{{ $user->orders_count }} đơn hàng</span></p> <!-- Số lượng đơn hàng -->
                        <p style="font-size:16px; color:black; font-weight:500">Tổng: <span style="color:red; font-weight:bold;font-size:16px;">{{ number_format($user->orders_sum_total_amount, 0, ',', '.') }}₫</span></p> <!-- Tổng giá trị đơn hàng -->
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <!-- End of New Users Section -->

    <!-- Recent Orders Table -->
    <div class="recent-orders">
        <h2>Mã giảm giá</h2>
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Mức giảm</th>
                    <th>Ngày hết hạn</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activeVouchers as $voucher)
                    @if ($voucher->discount_amount || $voucher->discount_percent)
                        <tr>
                            <td>{{ $voucher->code }}</td>
                            <td>
                                @if ($voucher->discount_amount)
                                    {{ number_format($voucher->discount_amount) }} VND
                                @elseif ($voucher->discount_percent)
                                    {{ $voucher->discount_percent }}%
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('H:i:s d-m-Y ') }}</td>
                            <td><span style="color: green; font-weight:bold;">Còn hạn</span></td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="4">No active vouchers available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('admin.vouchers.index') }}">Show All</a>
    </div>

    <!-- End of Recent Orders -->
@endsection
