@extends('admin.layouts.master')
@section('content')
<style>
    h1 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        font-size: 14px;
        border: none;
        border-radius: 4px;
        color: #fff;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #ff0000;
    }
    .btn-danger {
        background-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 1000px;
        margin: 20px auto; /* Căn giữa và tạo khoảng cách trên dưới */
        padding: 20px;
        background-color: #fff;
        text-align: center;
    }

    .card-body {
        padding: 20px;
    }

    .card-body p {
        font-size: 12px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 10px;
    }

    .card-body p strong {
        color: #333;
    }

</style>
        <h1>Product Details</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>SKU:</strong> {{ $product->sku }}</p>
                <p><strong>Name:</strong> {{ $product->name }}</p>
                <p><strong>Slug:</strong> {{ $product->slug }}</p>
                <p style="text-align: center;">
                    <strong>Image:</strong>
                    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;">
                        <img src="{{ Storage::url($product->image_path) }}" style="width: 100px; height: auto;" onclick="openModal(this)">
                    </div>
                </p>

                <!-- Galleries Section -->
                <p style="text-align: center;">
                    <strong>Galleries:</strong>
                    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;">
                        @foreach ($product->galleries as $gallery)
                            <div>
                                <img src="{{ Storage::url($gallery->image_path) }}" style="width: 100px; height: auto;" onclick="openModal(this)">
                            </div>
                        @endforeach
                    </div>
                </p>

                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p><strong>Price:</strong> {{ number_format($product->price) }}VND</p>
                <p><strong>Categories:</strong> {{ $product->category->name }}</p>
                <p><strong>Discounts:</strong> {{ number_format($product->discount->discount_percent) }}%</p>
            </div>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-danger mt-3">Edit</a>
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary mt-3">Cancel</a>
        </div>

        <div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); flex-direction: column; justify-content: center; align-items: center;">
            <img id="modal-img" src="" style="max-width: 80%; max-height: 80%; border-radius: 10px;">
        </div>

        <!-- JavaScript for modal functionality -->
        <script>
            function openModal(img) {
                // Get the modal and modal image elements
                var modal = document.getElementById('modal');
                var modalImg = document.getElementById('modal-img');

                // Set the image source of the modal to the clicked image
                modalImg.src = img.src;

                // Show the modal
                modal.style.display = 'flex';
            }

            modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none'; // Hide modal if clicking outside the image
            }
        });
        </script>
@endsection
