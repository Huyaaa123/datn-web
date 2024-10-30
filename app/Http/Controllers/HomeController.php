<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        // Nếu người dùng là admin, chuyển hướng tới trang admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Lấy sản phẩm và danh mục
        $products = Product::paginate(4);
        $categories = Category::all();

        // Cập nhật giỏ hàng trong session từ cơ sở dữ liệu
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            $cartItems = Cart::where('user_id', $user->id)->get();
            foreach ($cartItems as $item) {
                $cart[$item->product_id] = [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->image_path,
                ];
            }
            session()->put('cart', $cart);
        }

        return view('index', compact('products', 'categories'));
    }


}

