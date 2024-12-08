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
        $cart = session()->get('cart',[]);
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
        // Kiểm tra người dùng đã đăng nhập hay chưa
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Xác thực dữ liệu yêu cầu
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color_id' => 'required|integer|exists:colors,id',
            'size_id' => 'required|integer|exists:sizes,id',
        ]);

        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);

        // dd(session()->get('cart'));
        // Lấy thông tin sản phẩm
        $product = Product::find($request->product_id);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
        }

        $quantity = $request->quantity;
        $color_id = $request->color_id;
        $size_id = $request->size_id;

        // Tạo một key riêng cho sản phẩm với color_id và size_id để phân biệt
        $cartKey = $request->product_id . '-' . $color_id . '-' . $size_id;

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        if (isset($cart[$cartKey])) {
            // Tăng số lượng sản phẩm trong giỏ hàng
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $cart[$cartKey] = [
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

        // Quay lại trang giỏ hàng với thông báo thành công
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

        // Tạo khóa sản phẩm từ dữ liệu request
        $key = $request->product_id . '-' . $request->color_id . '-' . $request->size_id;

        // Kiểm tra sản phẩm có tồn tại trong giỏ hàng
        if (isset($cart[$key])) {
            // Xóa sản phẩm
            unset($cart[$key]);

            // Cập nhật lại session
            session()->put('cart', $cart);

            // Xóa các thông tin liên quan (voucher, nếu có)
            session()->forget('voucher_code');
            session()->forget('voucher_id');
            session()->forget('discount_amount');

            // Xóa sản phẩm khỏi cơ sở dữ liệu
            Cart::where('product_id', $request->product_id)
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->where('user_id', auth()->id())
                ->delete();

            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        }

        return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }

}
