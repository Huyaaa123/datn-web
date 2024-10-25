<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Discount;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with(['category', 'galleries'])->latest('id')->paginate(5);
        return view('admin.product', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $discounts = Discount::all();
        return view('admin.crud.product-create', compact('categories','discounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $dataProduct = [
                'category_id' => $request->category_id,
                'discount_id' => $request->discount_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'sku' => $request->sku,
            ];
                if ($request->hasFile('image_path')) {
                    $dataProduct['image_path'] = Storage::put('products', $request->file('image_path'));
                }

            $product = Product::query()->create($dataProduct);

            foreach($request->galleries as $image){
                Gallery::query()->create([
                    'product_id' => $product->id,
                    'image_path' => Storage::put('galleries', $image),
                ]);
            }
        });
        return redirect()->route('admin.product.index')->with('success', 'Product add successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.show.product-show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('category','galleries','discount');

        $categories = Category::pluck('name', 'id')->all();

        $discounts = Discount::pluck('discount_percent', 'id')->all();


        return view('admin.crud.product-edit', compact('categories', 'product','discounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $dataProduct = [
                'category_id' => $request->category_id,
                'discount_id' => $request->discount_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'sku' => $request->sku,

            ];
            if ($request->hasFile('image_path')) {
                $dataProduct['image_path'] = Storage::put('products', $request->file('image_path'));
            }

            $product->update($dataProduct);

    // Kiểm tra nếu có hình ảnh galleries mới
    if ($request->hasFile('galleries')) {
        // Xóa tất cả hình ảnh galleries hiện tại
        foreach ($product->galleries as $gallery) {
            if ($gallery->image_path && Storage::exists($gallery->image_path)) {
                Storage::delete($gallery->image_path);
            }
            $gallery->delete();
        }

        // Thêm các hình ảnh galleries mới
        foreach ($request->galleries as $image) {
            Gallery::create([
                'product_id' => $product->id,
                'image_path' => Storage::put('galleries', $image),
            ]);
        }
    }
        });

        return redirect()->route('admin.product.index')->with('success', 'Product update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->galleries as $gallery) {
                if ($gallery->image_path && Storage::exists($gallery->image_path)) {
                    Storage::delete($gallery->image_path);
                }
                $gallery->delete();
            }

            $product->delete();
        });

        if ($product->image_path && Storage::exists($product->image_path)) {
            Storage::delete($product->image_path);
        }

        return redirect()->route('admin.product.index')->with('success', 'Product delete successfully!');
    }
}
