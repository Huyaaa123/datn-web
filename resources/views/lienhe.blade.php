@extends('layouts.master')

@section('content')
<div class="container">
    <div class="col-md-12 mb-0">
        <strong class="text-black">Trang chủ</strong>
        <span class="mx-2 mb-0">/</span>
        <strong class="text-black">Liên hệ</strong>
    </div>
    <br>
</div>

<body>
<div class="container">
    <h1 class="text-center mb-4 text-black">Liên hệ với chúng tôi</h1>

    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form liên hệ -->
    <div class="row">
        <div class="col-md-6">
            <form method="POST" action="{{ route('contacts.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label text-black">Họ và tên</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nhập họ tên">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-black">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Nhập email">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label text-black">Số điện thoại</label>
                    <input type="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Nhập số điện thoại">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label text-black">Nội dung</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" placeholder="Nhập nội dung liên hệ"></textarea>
                    @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-secondary">Gửi liên hệ</button>
            </form>
        </div>

        <!-- Địa chỉ và bản đồ -->
        <div class="col-md-6">
            <h5 class="text-black">Địa chỉ</h5>
            <p class="text-black">Tòa nhà FPT Polytechnic, 13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội</p>

            <h5 class="text-black">Bản đồ</h5>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10532.6841252426!2d105.7385233636142!3d21.03803964076257!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1733241297018!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> <br>
@endsection
