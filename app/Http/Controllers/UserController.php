<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();
        return view("user-client.user",compact('user', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15', // Nếu có số điện thoại
            'gender' => 'nullable|string|in:male,female,other', // Giới tính
            'birth_date' => 'nullable|date', // Ngày sinh
        ]);

        // Tìm người dùng bằng ID
        $user = User::findOrFail($id);

        // Cập nhật thông tin người dùng
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
        $user->birth_date = $request->input('birth_date');

        // Lưu thay đổi vào cơ sở dữ liệu
        $user->save();

        // Trả về thông báo thành công
        return redirect()->route('user.index')->with('success', 'Thông tin hồ sơ đã được cập nhật.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
