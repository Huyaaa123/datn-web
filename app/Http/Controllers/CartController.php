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
        $categories = Category::all();
        return view('cart.cart', compact('cart','categories'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

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
                $cartItem->quantity += $quantity;
                $cartItem->total_price = $cartItem->quantity * $product->price;
                $cartItem->save();
            }
        } else {
            $cart[$request->product_id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image_path,
            ];

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

        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
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
    session()->forget('cart');

    return redirect()->route('cart.index')->with('success', 'Đã xóa tất cả sản phẩm trong giỏ hàng.');
}

}
