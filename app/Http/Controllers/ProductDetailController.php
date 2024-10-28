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

        foreach ($products as $product) {
            if ($product->discount) {
                $product->final_price = $product->price * (1 - $product->discount->discount_percent / 100);
            } else {
                $product->final_price = $product->price;
            }
        }
        return view('index', compact('products','categories'));
    }

    public function show($slug)
    {
        // Lấy sản phẩm dựa trên slug
        $product = Product::with(['discount', 'galleries'])->where('slug', $slug)->firstOrFail();

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

        // Truyền $category vào view
        return view('cart.product-detail', compact('product', 'categories', 'category'));
    }

}

