<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
{
    // Xác thực dữ liệu
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required|string',
    ]);

    // Lưu dữ liệu vào bảng contacts
    \App\Models\Contact::create($request->all());

    // Chuyển hướng với thông báo thành công
    return redirect()->back()->with('success', 'Gửi thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!');
}

}
