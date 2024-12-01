<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all(); // Lấy tất cả danh mục

        return view('index', compact('products','categories'));
    }

    public function show($slug)
    {
        $product = Product::with('galleries', 'colors', 'sizes', 'category')
                      ->where('slug', $slug)
                      ->firstOrFail();

        // Lấy danh mục của sản phẩm
        $category = $product->category; // Giả sử bạn đã định nghĩa quan hệ trong model Product

        // Lấy tất cả các danh mục
        $categories = Category::all();

        // Tính giá cuối cùng
        if ($product->discount) {
            $product->final_price = $product->price * (1 - $product->discount->discount_percent / 100);
        } else {
            $product->final_price = $product->price; // Nếu không có giảm giá, giữ giá gốc
        }

        $similarProducts = Product::where('category_id', $category->id)
        ->where('id', '!=', $product->id) // Loại trừ sản phẩm hiện tại
        ->limit(6) // Giới hạn số lượng
        ->get();
        return view('cart.product-detail', compact('product', 'categories', 'category','similarProducts'));
    }

}

