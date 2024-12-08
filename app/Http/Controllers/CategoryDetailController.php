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

    // Lấy giá sản phẩm cao nhất trong danh mục, nếu không có sản phẩm thì gán giá trị mặc định
    $maxPrice = Product::where('category_id', $category->id)->max('price') ?? 0;

    // Khởi tạo truy vấn sản phẩm
    $query = Product::where('category_id', $category->id);

    // Lọc theo khoảng giá nếu có
    $minPrice = request()->get('min_price', 0);
    $maxPriceFilter = request()->get('max_price', $maxPrice);

    if ($minPrice > 0) {
        $query->where('price', '>=', $minPrice);
    }
    if ($maxPriceFilter > 0) {
        $query->where('price', '<=', $maxPriceFilter);
    }

    // Lọc theo từ khóa tìm kiếm
    if ($search = request()->get('search')) {
        $query->where('name', 'like', '%' . $search . '%'); // Tìm kiếm trong tên sản phẩm
    }

    // Sắp xếp theo giá nếu có
    if (request()->get('sort') == 'price_desc') {
        $query->orderBy('price', 'desc');
    } elseif (request()->get('sort') == 'price_asc') {
        $query->orderBy('price', 'asc');
    }

    // Lấy sản phẩm với phân trang
    $products = $query->paginate(12);

    // Lấy tất cả các danh mục
    $categories = Category::all();

    // Trả về view với danh mục, sản phẩm và danh sách các danh mục
    return view('category', compact('categories', 'category', 'products', 'maxPrice', 'minPrice', 'maxPriceFilter'));
}

}
