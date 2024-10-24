
@extends('admin.layouts.master')
@section('content')
    <h1>Product</h1>
    <style>
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            font-size: 14px;
        }

        .text-center {
            text-align: center;
            display: block;
            margin-bottom: 20px;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #08d839;
        }

        .btn-danger {
            background-color: #dc3545;
            font-size: 14px;
        }

        .btn-danger:hover {
            background-color: #c82333;
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

        .text-danger {
            color: #dc3545;
            font-size: 14px;
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

        .btn-add {
            background-color: #28a745;
        }

        .btn-add:hover {
            background-color: #218838;
        }

        /* Bảng trong chế độ tối */
        .dark-mode-variables table {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
        }

        .dark-mode-variables th {
            background-color: #444;
            color: #fff;
        }

        .dark-mode-variables tbody tr:nth-child(even) {
            background-color: #3a3a3a;
        }

        .dark-mode-variables tbody tr:hover {
            background-color: #555;
        }
    </style>

    <a href="{{ route('admin.product.create') }}" class="text-center btn btn-add">Addnew</a>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Image</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
                <th>Discount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ Str::limit($product->name, 5, '...') }}</td>
                    <td>{{ Str::limit($product->name, 5, '...') }}</td>
                    <td>
                        <img src="{{ Storage::url($product->image_path) }}" style="width: 100px; height: auto;">
                    </td>
                    <td>{{ number_format($product->price) }}VND</td>
                    <td>{{ Str::limit($product->description, 10, '...') }}</td>
                    <td>{{ Str::limit($product->category->name, 10, '...') }}</td>
                    <td>{{ number_format($product->discount->discount_percent) }}%</td>
                    <td>
                        <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-success">Show</a>
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function confirmDelete(event) {
            if (!confirm('Are you sure?')) {
                event.preventDefault();
            }
        }
    </script>
@endsection
