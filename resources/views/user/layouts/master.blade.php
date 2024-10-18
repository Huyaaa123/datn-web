<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>RolexWatch</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Free HTML Templates" name="keywords" />
    <meta content="Free HTML Templates" name="description" />

    @include('user.layouts.partials.css')

</head>

<body>
    <header>
        @include('user.layouts.partials.header-top')

        @include('user.layouts.partials.header-nav')

    </header>

    @yield('content')

    <footer>
        @include('user.layouts.partials.footer')
    </footer>


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    @include('user.layouts.partials.js')


</body>

</html>
