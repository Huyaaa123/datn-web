@extends('admin.layouts.master')
@section('content')
    <h1>Users</h1>
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

        .btn-success {
            background-color: #08d839;
        }

        .btn-primary:hover {
            background-color: #0056b3;
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
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
        }


        .btn-add {
            background-color: #28a745;
        }
    </style>
     <br>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Birth date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @if ($user->type === 'member')
                    <tr>
                        <td>{{ Str::limit($user->name, 15, '...') }}</td>
                        <td>{{ Str::limit($user->email, 15, '...') }}</td>
                        <td>{{ Str::limit($user->type, 15, '...') }}</td>
                        <td>
                            @if ($user->phone)
                                {{ Str::limit($user->phone, 15, '...') }}
                            @else
                                ...
                            @endif
                        </td>
                        <td>
                            @if ($user->gender)
                                {{ Str::limit($user->gender, 15, '...') }}
                            @else
                                ...
                            @endif
                        </td>
                        <td>
                            @if ($user->birth_date)
                                {{ Str::limit($user->birth_date, 15, '...') }}
                            @else
                                ...
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

@endsection
