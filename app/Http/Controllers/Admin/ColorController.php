<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    // Hiển thị danh sách màu sắc
    public function index()
    {
        $colors = Color::all();  // Lấy tất cả màu sắc
        return view('admin.colors', compact('colors'));
    }

    // Hiển thị form tạo màu sắc mới
    public function create()
    {
        return view('admin.crud.color-create');  // Chuyển tới view để tạo màu mới
    }

    // Lưu màu sắc mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:7',  // Giới hạn độ dài cho mã màu (ví dụ: mã hex #FFFFFF)
        ]);

        Color::create([
            'name' => $request->name,
            'code' => $request->code,  // Lưu mã màu
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Thêm màu sắc thành công');
    }

    // Hiển thị form chỉnh sửa màu sắc
    public function edit($id)
    {
        $color = Color::findOrFail($id);  // Lấy màu sắc theo ID
        return view('admin.crud.color-edit', compact('color'));
    }

    // Cập nhật thông tin màu sắc
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:7',  // Giới hạn độ dài cho mã màu (ví dụ: mã hex #FFFFFF)
        ]);

        $color = Color::findOrFail($id);
        $color->update([
            'name' => $request->name,
            'code' => $request->code,  // Cập nhật mã màu
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Cập nhật màu sắc thành công');
    }

    // Xóa màu sắc
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();  // Xóa màu sắc

        return redirect()->route('admin.colors.index')->with('success', 'Xóa màu sắc thành công');
    }
}

