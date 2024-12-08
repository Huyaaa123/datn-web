<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
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

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $products = Product::paginate(4);
        $categories = Category::all();
        $vouchers = Voucher::all();

         // Lấy danh mục 'Đồng hồ nam'
         $menCategory = Category::where('name', 'Đồng hồ nam')->first();

         // Lấy sản phẩm thuộc danh mục 'Đồng hồ nam'
         $menProducts = $menCategory ? Product::where('category_id', $menCategory->id)->get() : [];

         // Lấy danh mục 'Đồng hồ nữ'
         $womenCategory = Category::where('name', 'Đồng hồ nữ')->first();

         // Lấy sản phẩm thuộc danh mục 'Đồng hồ nữ'
         $womenProducts = $womenCategory ? Product::where('category_id', $womenCategory->id)->get() : [];

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

        return view('index', compact('products', 'categories','vouchers','menProducts','womenProducts'));
    }

}

