@extends('admin.layouts.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
        <link rel="stylesheet" href="../../admindb/style.css">
        <style>
            .form-group-container {
                display: flex;
                gap: 20px;
                /* Tạo khoảng cách giữa các cột */
            }

            .form-group {
                flex: 1;
                /* Mỗi form-group chiếm 50% chiều rộng */
            }

            h1 {
                font-size: 24px;
                margin-bottom: 20px;
                color: #333;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                font-size: 16px;
                margin-bottom: 8px;
                color: #555;
            }

            input.form-control {
                width: 100%;
                padding: 5px;
                font-size: 14px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;
            }


            button.btn-primary {
                background-color: #007bff;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 12px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }

            button.btn-primary:hover {
                background-color: #0056b3;
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

            textarea.form-control {
                width: 100%;
                /* Giới hạn chiều rộng của textarea ở 50% */
                resize: none;
                /* Tắt chức năng kéo dãn textarea */
            }

            select.form-control {
                width: 100%;
                padding: 5px;
                font-size: 16px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;
            }
        </style>
    </head>

    <body>
        <h1>Create Product</h1>

        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}"
                    required>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            </div>

            <div class="form-group-container">
                <div class="form-group">
                    <label for="image_path">Image</label>
                    <input type="file" class="form-control" id="image_path" name="image_path">
                </div>

                <div class="form-group">
                    <label for="galleries">Galleries</label>
                    <input type="file" class="form-control" id="galleries" name="galleries[]" multiple>
                </div>
            </div>


            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="discount">Discount</label>
                <select class="form-control" id="discount" name="discount_id">
                    @foreach ($discounts as $discount)
                        <option value="{{ $discount->id }}" {{ old('discount_id') == $discount->id ? 'selected' : '' }}>
                            {{ round($discount->discount_percent, 2) }}%
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="{{ route('admin.product.index') }}" class="btn btn-success">Cancel</a>
        </form>
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
    </body>

    </html>
@endsection
