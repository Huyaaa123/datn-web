@extends('admin.layouts.master')
@section('content')

<h1>Bảng điều khiển</h1>
    <!-- Analyses -->
    <div class="analyse">
        <div class="sales">
            <div class="status">
                <div class="info">
                    <h3>Total Sales</h3>
                    <h1>$65,024</h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <p>+81%</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="visits">
            <div class="status">
                <div class="info">
                    <h3>Site Visit</h3>
                    <h1>24,981</h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <p>-48%</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="searches">
            <div class="status">
                <div class="info">
                    <h3>Searches</h3>
                    <h1>14,147</h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <p>+21%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Analyses -->

    <!-- New Users Section -->
    <div class="new-users">
        <h2>Người dùng mới</h2>
        <div class="user-list">
            @if ($newUsers->isEmpty())
                <div class="user">
                    <h2>No new members registered recently.</h2>
                </div>
            @else
                @foreach ($newUsers as $user)
                    <div class="user">
                        @php
                            // Array of available profile images
                            $images = [
                                'profile-2.jpg',
                                'profile-3.jpg',
                                'profile-4.jpg',
                                'profile-5.jpg',
                                'profile-6.jpg',
                                'profile-7.jpg',
                                'profile-8.jpg'
                            ];
                            // Select a random image from the array
                            $randomImage = $images[array_rand($images)];
                        @endphp
                        <img src="{{ asset('admindb/images/' . $randomImage) }}" alt="{{ $user->name }}">
                        <h2>{{ $user->name }}</h2>
                        <p>{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    <!-- End of New Users Section -->

    <!-- Recent Orders Table -->
    <div class="recent-orders">
        <h2>Mã giảm giá </h2>
        <table>
            <thead>
                <tr>
                    <th>Voucher Code</th>
                    <th>Discount Amount</th>
                    <th>Discount Percent</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activeVouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->discount_amount ? number_format($voucher->discount_amount) . ' VND' : '-' }}</td>
                        <td>{{ $voucher->discount_percent ? $voucher->discount_percent . '%' : '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('d-m-Y H:i') }}</td>
                        <td><span style="color: green; font-weight:bold;">Còn hạn</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No active vouchers available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('admin.vouchers.index') }}">Show All</a>
    </div>
    <!-- End of Recent Orders -->
@endsection
