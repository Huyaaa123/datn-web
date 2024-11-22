<div class="toggle">
    <div class="logo">
        <img src="../../../admindb/images/lg.jpg">
        <h2>Rolex<span class="danger">Watch</span></h2>
    </div>
    <div class="close" id="close-btn">
        <span class="material-icons-sharp">
            close
        </span>
    </div>
</div>

<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}">
        <span class="material-icons-sharp">
            dashboard
        </span>
        <h3>Bảng điều khiển</h3>
    </a>
    <a href="{{ route('admin.users.index') }}">
        <span class="material-icons-sharp">
            account_circle
        </span>
        <h3>Người dùng</h3>
    </a>
    <a href="{{ route('admin.category.index') }}">
        <span class="material-icons-sharp">
            category
        </span>
        <h3>Danh mục</h3>
    </a>
    <a href="{{ route('admin.product.index') }}">
        <span class="material-icons-sharp">
            inventory_2
        </span>
        <h3>Sản phẩm</h3>
    </a>
    <a href="{{ route('admin.orders.index') }}">
        <span class="material-icons-sharp">
            shopping_bag
        </span>
        <h3>Đơn hàng</h3>
        {{-- <span class="message-count">27</span> --}}
    </a>
    <a href="{{ route('admin.vouchers.index') }}">
        <span class="material-icons-sharp">
            sell
        </span>
        <h3>Mã giảm giá</h3>
        {{-- <span class="message-count">27</span> --}}
    </a>
    <a href="{{ route('admin.statistic.index') }}">
        <span class="material-icons-sharp">
            trending_up
            </span>
        <h3>Thống kê</h3>
        {{-- <span class="message-count">27</span> --}}
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            settings
        </span>
        <h3>Cài đặt</h3>
    </a>
    {{--
    <a href="#">
        <span class="material-icons-sharp">
            report_gmailerrorred
        </span>
        <h3>Reports</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            settings
        </span>
        <h3>Settings</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            add
        </span>
        <h3>New Login</h3>
    </a> --}}
    <a class="dropdown-item" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <span class="material-icons-sharp">
            logout
        </span>
        <h3>Logout</h3>
    </a>

</div>
