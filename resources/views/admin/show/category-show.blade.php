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
        max-width: 600px;
        margin: 20px auto; /* Căn giữa và tạo khoảng cách trên dưới */
        padding: 20px;
        background-color: #fff;
        text-align: center;
    }

    .card-body {
        padding: 20px;
    }

    .card-body p {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 10px;
    }

    .card-body p strong {
        color: #333;
    }

</style>
        <h1>Category Details</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>ID:</strong> {{ $category->id }}</p>
                <p><strong>Name:</strong> {{ $category->name }}</p>
                <p><strong>Slug:</strong> {{ $category->slug }}</p>
                <p><strong>Created At:</strong> {{ $category->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Updated At:</strong> {{ $category->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <a href="{{ route('admin.category.edit',$category->id) }}" class="btn btn-danger mt-3">Edit</a>
            <a href="{{ route('admin.category.index') }}" class="btn btn-primary mt-3">Cancel</a>
        </div>

@endsection
