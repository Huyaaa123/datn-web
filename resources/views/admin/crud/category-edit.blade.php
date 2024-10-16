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
                margin-bottom: 20px;
                color: #333;
                text-align: center;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                display: block;
                font-size: 16px;
                margin-bottom: 5px;
            }

            .form-group input {
                width: 100%;
                padding: 8px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 15px;
                font-size: 14px;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #0056b3;
            }

            .text-danger {
                color: #dc3545;
                font-size: 14px;
            }
        </style>
    </head>

    <body>

        <h1>Edit Category</h1>

        <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
        @if (session('success'))
            <p class="text-success">{{ session('success') }}</p>
        @endif
    </body>

    </html>
@endsection
