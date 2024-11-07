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
                padding: 10px;
                font-size: 10px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .text-danger {
                color: #dc3545;
                margin-top: 5px;
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

            a.btn-success {
                background-color: #ff1616;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 11px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }

        </style>
    </head>

    <body style=" font-family: 'Playfair Display', serif;">
        <h1>Thêm danh mục</h1>

        <form action="{{ route('admin.category.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Tên</label>
                <input type="text" id="name" name="name" class="form-control" >
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="{{ route('admin.category.index') }}" class="btn btn-success">Cancel</a>
        </form>
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
    </body>

    </html>
@endsection
