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
                                    <li>
                                        <a href="../categories/{{ $category->slug }}">{{ $category->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="../client/blog.html">Tin Tức</a></li>
                        <li><a href="../client/blog.html">Liên Hệ</a></li>
                        <li><a href="{{route('index.vouchers')}}">Hướng dẫn mua hàng</a></li>
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
                                    <span class="icon icon-person"></span>
                                </a>
                            @endif
                        </li>

                        <li class="nav-link">
                            <a href="{{route('cart.index')}}" class="site-cart">
                                <span class="icon icon-shopping_cart"></span>
                                <span class="count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                            </a>
                        </li>
                        <li><a href=""><span class="icon icon-search"></span></a></li>
                        <li class="d-inline-block d-md-none ml-md-0">
                            <a href="../client/#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
