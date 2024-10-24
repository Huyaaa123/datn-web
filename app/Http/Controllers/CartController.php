<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart');
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('cart.cart', compact('cart','categories'));
    }

    public function add(Request $request)
    {
        // Xác thực yêu cầu
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // Tìm sản phẩm trong database
        $product = Product::find($request->product_id);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
        }

        $quantity = $request->quantity;

        if (isset($cart[$request->product_id])) {
            // Tăng số lượng trong session
            $cart[$request->product_id]['quantity'] += $quantity;

            // Cập nhật số lượng trong cơ sở dữ liệu
            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                // Nếu sản phẩm đã tồn tại trong database, cập nhật số lượng
                $cartItem->quantity += $quantity;
                $cartItem->total_price = $cartItem->quantity * $product->price;
                $cartItem->save();
            }
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $cart[$request->product_id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image_path,
            ];

            // Lưu sản phẩm vào cơ sở dữ liệu
            try {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $request->product_id,
                    'quantity' => $quantity,
                    'total_price' => $product->price * $quantity,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage());
            }
        }

        // Cập nhật lại giỏ hàng trong session
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }




    public function remove(Request $request)
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart');

        // Nếu giỏ hàng có sản phẩm
        if (isset($cart[$request->product_id])) {
            // Lấy ID sản phẩm
            $productId = $request->product_id;

            // Xóa sản phẩm khỏi giỏ hàng trong session
            unset($cart[$productId]);

            // Cập nhật lại giỏ hàng trong session
            session()->put('cart', $cart);

            // Xóa sản phẩm khỏi cơ sở dữ liệu (giả sử bạn đã lưu thông tin giỏ hàng trong database)
            Cart::where('product_id', $productId)->where('user_id', auth()->id())->delete();
        }

        // Quay lại trang giỏ hàng với thông báo thành công
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function removeAll(Request $request)
{
    // Xóa tất cả sản phẩm trong giỏ hàng
    session()->forget('cart');

    return redirect()->route('cart.index')->with('success', 'Đã xóa tất cả sản phẩm trong giỏ hàng.');
}

}
