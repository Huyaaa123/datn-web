<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        // Lấy giá trị từ thanh tìm kiếm và bộ lọc
        $search = $request->input('search');
        $rating = $request->input('rating');
        $order_by = $request->input('order_by'); // Thêm biến order_by để lấy giá trị sắp xếp

        // Lọc và tìm kiếm theo từ khóa trong bình luận và tên sản phẩm
        $commentsQuery = Comment::with('product', 'user'); // Eager load các sản phẩm và người dùng liên quan

        // Tìm kiếm theo từ khóa trong cả nội dung bình luận và tên sản phẩm
        if ($search) {
            $commentsQuery->where(function($query) use ($search) {
                $query->where('content', 'like', '%' . $search . '%')
                      ->orWhereHas('product', function($query) use ($search) {
                          $query->where('name', 'like', '%' . $search . '%');
                      });
            });
        }

        // Lọc theo đánh giá sao
        if ($rating) {
            $commentsQuery->where('rating', $rating);
        }

        // Sắp xếp theo "Mới nhất" hoặc "Cũ nhất"
        if ($order_by == 'latest') {
            $commentsQuery->orderBy('created_at', 'desc');
        } elseif ($order_by == 'oldest') {
            $commentsQuery->orderBy('created_at', 'asc');
        }

        // Lấy tất cả bình luận sau khi áp dụng tìm kiếm và bộ lọc
        $comments = $commentsQuery->get();

        // Trả về view với dữ liệu đã xử lý
        return view('admin.comments', compact('comments', 'search', 'rating', 'order_by'));
    }


    public function create()
    {
        // Lấy tất cả sản phẩm và người dùng để hiển thị trong form tạo bình luận
        $products = Product::all();
        $users = User::all();
        return view('admin.comments.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Tạo bình luận mới
        Comment::create($validated);

        // Quay lại trang danh sách bình luận
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được thêm thành công!');
    }

    public function edit($id)
    {
        // Lấy bình luận cần chỉnh sửa và dữ liệu sản phẩm, người dùng
        $comment = Comment::findOrFail($id);
        $products = Product::all();
        $users = User::all();
        return view('admin.comments.edit', compact('comment', 'products', 'users'));
    }

    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Cập nhật bình luận
        $comment = Comment::findOrFail($id);
        $comment->update($validated);

        // Quay lại trang danh sách bình luận
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được cập nhật!');
    }

    public function destroy($id)
    {
        // Xóa bình luận
        $comment = Comment::findOrFail($id);
        $comment->delete();

        // Quay lại trang danh sách bình luận
        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được xóa!');
    }
}

