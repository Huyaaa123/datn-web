<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryDetailController extends Controller
{
public function show($slug)
{
    $categories = Category::all();
    return view('category', compact('categories'));
}
public function view($slug)
{
    // Lấy danh mục dựa trên slug
    $category = Category::where('slug', $slug)->firstOrFail();

    $products = Product::where('category_id', $category->id)->paginate(20);;

    // Lấy tất cả các danh mục
    $categories = Category::all();

    // Trả về view với danh mục, sản phẩm và danh sách các danh mục
    return view('category', compact('categories', 'category', 'products'));
}



}
