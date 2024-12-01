<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Lấy tất cả bình luận của sản phẩm
        $comments = $product->comments; // giả sử mô hình Product có quan hệ với Comment

        // Trả về view với các bình luận
        return view('product.comments', compact('product', 'comments'));
    }

    // Lưu bình luận mới cho sản phẩm
    public function store(Request $request, $slug)
    {
        // Xác thực dữ liệu bình luận
        $validated = $request->validate([
            'content' => 'required|max:1000',
        ]);

        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Tạo bình luận mới
        $comment = new Comment();
        $comment->content = $validated['content'];
        $comment->user_id = auth()->id(); // giả sử người dùng đã đăng nhập
        $comment->product_id = $product->id;
        $comment->save();

        // Chuyển hướng trở lại trang sản phẩm và hiển thị thông báo thành công
        return redirect()->route('product.comments', ['slug' => $slug])->with('success', 'Bình luận của bạn đã được đăng.');
    }

}
