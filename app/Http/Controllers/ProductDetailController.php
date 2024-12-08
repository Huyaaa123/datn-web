<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Lấy tất cả sản phẩm
        $categories = Category::all(); // Lấy tất cả danh mục

        // Lấy danh mục 'Đồng hồ nam'
        $menCategory = Category::where('name', 'Đồng hồ nam')->first();

        // Lấy sản phẩm thuộc danh mục 'Đồng hồ nam'
        $menProducts = $menCategory ? Product::where('category_id', $menCategory->id)->get() : [];

        // Lấy danh mục 'Đồng hồ nữ'
        $womenCategory = Category::where('name', 'Đồng hồ nữ')->first();

        // Lấy sản phẩm thuộc danh mục 'Đồng hồ nữ'
        $womenProducts = $womenCategory ? Product::where('category_id', $womenCategory->id)->get() : [];

        return view('index', compact('products', 'categories', 'menProducts', 'womenProducts'));
    }

    public function show(Request $request, $slug)
    {
        // Lấy sản phẩm cùng với các quan hệ
        $product = Product::with('galleries', 'colors', 'sizes', 'category')
            ->where('slug', $slug)
            ->firstOrFail();

        $category = $product->category;
        $categories = Category::all();

        // Tính giá cuối cùng của sản phẩm
        if ($product->discount) {
            $product->final_price = $product->price * (1 - $product->discount->discount_percent / 100);
        } else {
            $product->final_price = $product->price;
        }

        // Xử lý bình luận (nếu form được gửi)
        if ($request->isMethod('post')) {
            // Kiểm tra xem người dùng đã mua hàng thành công hay chưa
            $hasPurchased = Order::where('user_id', auth()->id())
                ->where('order_status_id', 6)
                ->exists();

            if (!$hasPurchased) {
                return redirect()->back()->withErrors(['Bạn cần mua sản phẩm này để có thể bình luận.']);
            }

            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'content' => 'required|string|max:1000',
            ]);

            // Thêm bình luận
            $product->comments()->create([
                'user_id' => auth()->id(),
                'rating' => $request->rating,
                'content' => $request->content,
            ]);

            return redirect()->route('product.show', $slug)
                ->with('success', 'Bình luận của bạn đã được thêm thành công.');
        }

        // Lọc bình luận theo rating nếu có
        $rating = $request->get('rating');
        $comments = $product->comments()->with('user');
        if ($rating) {
            $comments = $comments->where('rating', $rating);
        }
        $comments = $comments->get();

        // Lấy các sản phẩm tương tự (cùng danh mục nhưng khác sản phẩm hiện tại) ngẫu nhiên
        $similarProducts = Product::where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder() // Lấy sản phẩm ngẫu nhiên
            ->take(6) // Lấy tối đa 6 sản phẩm
            ->get();


        return view('cart.product-detail', compact('product', 'categories', 'category', 'comments', 'similarProducts'));
    }

    public function search(Request $request)
    {
        $categories = Category::all();
        $query = $request->input('q');

        $products = Product::where('name', 'like', '%' . $query . '%')->paginate(10);

        return view('search', compact('products', 'query', 'categories'));
    }
}

