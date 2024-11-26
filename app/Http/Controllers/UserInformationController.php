<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserInformationController extends Controller
{
    public function showChangePasswordForm()
    {
        $categories = Category::all();
        return view('user-client.editpassword', compact('categories'));
    }

    // Phương thức xử lý việc đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng.']);
        }

        // Cập nhật mật khẩu
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('password.change')->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }

    //dia chi

    public function address()
    {   $categories = Category::all();
        $addresses = Auth::user()->addresses; // Lấy địa chỉ của người dùng hiện tại
        return view('user-client.address', compact('addresses', 'categories'));
    }

    // Lưu địa chỉ mới
    public function addstore(StoreAddressRequest $request)
    {
        // Kiểm tra nếu người dùng đã có địa chỉ
        $existingAddress = Address::where('user_id', Auth::id())->first();
        if ($existingAddress) {
            return redirect()->route('addresses.index')->with('error', 'Bạn chỉ có thể thêm một địa chỉ duy nhất.');
        }

        // Tạo địa chỉ mới
        Address::create([
            'user_id' => Auth::id(), // Lưu ID người dùng
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'ward' => $request->ward,
        ]);

        return redirect()->route('addresses.index')->with('success', 'Địa chỉ đã được lưu thành công!');
    }


    public function destroy($id)
{
    $address = Address::findOrFail($id);

    // Kiểm tra xem địa chỉ có thuộc về người dùng hiện tại không
    if ($address->user_id === Auth::id()) {
        $address->delete();
        return redirect()->route('addresses.index')->with('success', 'Địa chỉ đã được xóa thành công!');
    }

    return redirect()->route('addresses.index')->with('error', 'Bạn không có quyền xóa địa chỉ này!');
}
}
