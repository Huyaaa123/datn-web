<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart');
        $vouchers =Voucher::all();
        // dd($cart);
        $products = Product::inRandomOrder()->take(8)->get();
        $categories = Category::all();

        $vouchers = $vouchers->filter(function ($voucher) {
            return Carbon::parse($voucher->end_date)->isAfter(Carbon::now());
        });
        return view('cart.cart', compact('cart', 'categories', 'products', 'vouchers'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color_id' => 'required|integer|exists:colors,id',
            'size_id' => 'required|integer|exists:sizes,id',
        ]);

        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);

        $product = Product::find($request->product_id);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
        }

        $quantity = $request->quantity;
        $color_id = $request->color_id;
        $size_id = $request->size_id;

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        if (isset($cart[$request->product_id])) {
            // Tăng số lượng sản phẩm trong giỏ hàng
            $cart[$request->product_id]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $cart[$request->product_id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image_path,
                'color_id' => $color_id,  // Lưu màu sắc người dùng chọn vào giỏ hàng
                'size_id' => $size_id,    // Lưu kích thước người dùng chọn vào giỏ hàng
            ];
        }

        // Cập nhật lại giỏ hàng trong session
        session()->put('cart', $cart);

        // Nếu bạn muốn lưu vào cơ sở dữ liệu thì có thể thêm phần này
        try {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
                'color_id' => $color_id,  // Lưu màu sắc vào giỏ hàng
                'size_id' => $size_id,    // Lưu kích thước vào giỏ hàng
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng: ' . $e->getMessage());
        }

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }


    public function cong(Request $request)
    {
        $productId = $request->input('product_id');
        // Tăng số lượng sản phẩm trong giỏ hàng
        session()->increment("cart.$productId.quantity");
        return redirect()->back();
    }

    public function tru(Request $request)
    {
        $productId = $request->input('product_id');
        // Giảm số lượng sản phẩm trong giỏ hàng
        if (session("cart.$productId.quantity") > 1) {
            session()->decrement("cart.$productId.quantity");
        }
        return redirect()->back();
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
            session()->forget('voucher_code');
            session()->forget('voucher_id');
            session()->forget('discount_amount');

            // Xóa sản phẩm khỏi cơ sở dữ liệu (giả sử bạn đã lưu thông tin giỏ hàng trong database)
            Cart::where('product_id', $productId)->where('user_id', auth()->id())->delete();
        }

        // Quay lại trang giỏ hàng với thông báo thành công
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

}
