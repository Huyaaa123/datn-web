@extends('admin.layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
        <link rel="stylesheet" href="../../../admindb/style.css">
        <style>


            h1 {
                font-size: 24px;
                color: #333;
                margin-top: 20px;
            }


            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                font-size: 16px;
                margin-bottom: 5px;
                color: #555;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 5px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .form-group textarea {
                height: 100px;
                resize: vertical;
            }

            button {
                background-color: #28a745;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 12px;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }


            .form-group img {
                margin-top: 10px;
                width: 50px;
                /* Giảm kích thước ảnh xuống 50px */
                height: auto;
                /* Giữ tỷ lệ ảnh */
            }

            a.btn-success {
                background-color: #ff1616;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 10px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }

            a.btn-success:hover {
                background-color: #d2010c;
            }

        </style>

    </head>

    <body>

        <h1>Edit Product</h1>

        <form action="{{ route('admin.product.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" value="{{ $product->sku }}">
                @error('sku')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="discount">Discount</label>
                               <select class="form-control" id="discount" name="discount_id">
                    @foreach ($discounts as $id => $discount_percent)
                        <option @selected($product->discount_id == $id) value="{{ $id }}">{{ number_format($discount_percent) }}%</option>
                    @endforeach
                </select>
                @error('discount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id">
                    @foreach ($categories as $id => $name)
                        <option @selected($product->category_id == $id) value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ $product->name }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group d-flex">
                <div class="col-md-6">
                    <label for="image_path">Image</label>
                    <input type="file" name="image_path" id="image_path" class="form-control">
                    @if ($product->image_path && \Storage::exists($product->image_path))
                        <img src="{{ Storage::url($product->image_path) }}" width="100px" alt="Product Image" onclick="openModal(this)">
                    @endif
                    @error('image_path')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="galleries">Galleries</label>
                    <input type="file" name="galleries[]" id="galleries" class="form-control" multiple>
                    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;">
                        @foreach ($product->galleries as $gallery)
                            <img src="{{ Storage::url($gallery->image_path) }}" width="100px" alt="Gallery Image" onclick="openModal(this)">
                        @endforeach
                    </div>
                    @error('galleries')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price"
                    value="{{ old('price', Str::replaceLast('.00', '', $product->price)) }} "
                    class="form-control">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description">{{ $product->description }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-danger">Submit</button>
                <a href="{{ route('admin.product.index') }}" class="btn btn-success">Cancel</a>
            </div>
        </form>
        @if (session('success'))
            <p class="text-success">{{ session('success') }}</p>
        @endif

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
    </body>

    </html>
@endsection
