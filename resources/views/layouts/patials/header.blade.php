<style>

</style>
<div class="site-navbar-top">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <a href="/">
                    <div class="site-logo">
                        <img src="../client/images/logo-8.png" width="150px" height="auto">
                    </div>
                </a>
            </div>

            <div class="col">
                <nav class="site-navigation text-right text-md-center" role="navigation">
                    <ul class="site-menu js-clone-nav d-none d-md-flex justify-content-center" style="font-weight: 500;">
                        <li><a href="/">Trang chủ</a></li>
                        <li class="has-children">
                            <a href="">Menu</a>
                            <ul class="dropdown" style="font-weight: 500;">
                                @foreach ($categories as $category)
                                    @if ($category->name !== 'Chưa phân loại') {{-- Kiểm tra tên danh mục --}}
                                        <li>
                                            <a href="../categories/{{ $category->slug }}">{{ $category->name }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="{{route('index.gioithieu')}}">Giới thiệu</a></li>
                        <li><a href="{{route('index.lienhe')}}">Liên Hệ</a></li>
                        <li><a href="{{route('index.huongdan')}}">Hướng dẫn mua hàng</a></li>
                    </ul>
                </nav>
            </div>

            <div class="col-auto">
                <div class="site-top-icons d-flex align-items-center">
                    <ul class="d-flex align-items-center" style="font-weight: 500;">
                        <li class="nav-link dropdown">
                            @if (auth()->check())
                                <a href="" data-toggle="dropdown">
                                    <span class="icon icon-person"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('user.index') }}">Thông tin tài khoản</a>
                                    <a class="dropdown-item" href="{{ route('order.client.user') }}">Đơn hàng của tôi</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="nav-link">
                                    <span style="margin-right: -15px" class="icon icon-person"></span>
                                </a>
                            @endif
                        </li>

                        <li class="nav-link">
                            <a href="{{route('cart.index')}}" class="site-cart">
                                <span class="icon icon-shopping_cart"></span>
                                <span class="count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item position-relative" style="margin-left: -10px">
                            <a href="#" class="nav-link" id="searchIcon" onclick="toggleSearchInput(event)">
                                <span class="icon icon-search"></span>
                            </a>
                            <div id="searchInputContainer" class="position-absolute d-none" style="top: 100%; right: 0; z-index: 1000; min-width: 300px;">
                                <form action="{{ route('product.search') }}" method="GET" class="p-2 bg-white border rounded">
                                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm..." required>
                                </form>
                            </div>
                        </li>

                        <li class="d-inline-block d-md-none ml-md-0">
                            <a href="../client/#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleSearchInput(event) {
        event.preventDefault();
        const searchInputContainer = document.getElementById('searchInputContainer');
        searchInputContainer.classList.toggle('d-none'); // Hiển thị hoặc ẩn input
    }

    // Ẩn input khi nhấn ra ngoài
    document.addEventListener('click', function (event) {
        const searchIcon = document.getElementById('searchIcon');
        const searchInputContainer = document.getElementById('searchInputContainer');
        if (!searchIcon.contains(event.target) && !searchInputContainer.contains(event.target)) {
            searchInputContainer.classList.add('d-none');
        }
    });
</script>
