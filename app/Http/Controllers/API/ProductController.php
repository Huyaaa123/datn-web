<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query()->latest('id')->paginate(5);

        return response()->json($products);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Chuẩn bị dữ liệu
        $dataProduct = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ];

        // Xử lý upload file ảnh (nếu có)
        if ($request->hasFile('image_path')) {
            $dataProduct['image_path'] = Storage::put('products', $request->file('image_path'));
        }

        // Tạo mới sản phẩm
        $product = Product::create($dataProduct);

        return response()->json($product, 201); // Trả về sản phẩm mới với mã trạng thái 201 (Created)
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Lấy thông tin sản phẩm cụ thể
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Tìm sản phẩm cần cập nhật
        $product = Product::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cập nhật dữ liệu
        $dataProduct = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ];

        // Xử lý upload file ảnh mới (nếu có)
        if ($request->hasFile('image_path')) {
            // Xóa ảnh cũ
            if ($product->image_path && Storage::exists($product->image_path)) {
                Storage::delete($product->image_path);
            }
            // Lưu ảnh mới
            $dataProduct['image_path'] = Storage::put('products', $request->file('image_path'));
        }

        // Lưu thay đổi
        $product->update($dataProduct);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Tìm và xóa sản phẩm
        $product = Product::findOrFail($id);

        // Xóa ảnh khỏi storage (nếu có)
        if ($product->image_path && Storage::exists($product->image_path)) {
            Storage::delete($product->image_path);
        }

        // Xóa sản phẩm
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}

