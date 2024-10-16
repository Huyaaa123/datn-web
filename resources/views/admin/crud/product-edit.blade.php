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
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            h1 {
                font-size: 28px;
                color: #333;
                text-align: center;
                margin-top: 20px;
                margin-bottom: 30px;
            }

            .form-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                font-size: 16px;
                margin-bottom: 8px;
                color: #555;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 12px;
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
                font-size: 16px;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #218838;
            }

            .btn-info {
                background-color: #17a2b8;
                margin-left: 10px;
            }

            .btn-info:hover {
                background-color: #138496;
            }

            .text-danger {
                color: #dc3545;
                font-size: 14px;
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
                font-size: 16px;
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

            <div class="form-group">
                <label for="image_path">Image</label>
                <input type="file" name="image_path" id="image_path">
                @if ($product->image_path && \Storage::exists($product->image_path))
                    <img src="{{ Storage::url($product->image_path) }}" width="100px" alt="Product Image">
                @endif
                @error('image_path')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" id="price" value="{{ $product->price }}">
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
    </body>

    </html>
@endsection
