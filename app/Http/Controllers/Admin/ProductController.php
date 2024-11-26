<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Gallery;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort');

        $data = Product::with(['category', 'galleries'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($sort, function ($query, $sort) {
                if ($sort == 'price_asc') {
                    $query->orderBy('price', 'asc');
                } elseif ($sort == 'price_desc') {
                    $query->orderBy('price', 'desc');
                }
            })
            ->latest('id')
            ->paginate(4);

        return view('admin.product', compact('data', 'search', 'sort'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        $colors = Color::pluck('name', 'id')->all();
        $sizes = Size::pluck('name', 'id')->all();
        return view('admin.crud.product-create', compact('categories', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $dataProduct = [
                'category_id' => $request->category_id,
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

            foreach ($request->galleries as $image) {
                Gallery::query()->create([
                    'product_id' => $product->id,
                    'image_path' => Storage::put('galleries', $image),
                ]);
            }
            $product->colors()->attach($request->colors);
            $product->sizes()->attach($request->sizes);
        });
        return redirect()->route('admin.product.index')->with('success', 'Thêm mới sản phẩm thành công!');
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
        $product->load('category', 'galleries', 'colors', 'sizes');

        $productColors = $product->colors->pluck('id')->all();
        $productSizes = $product->sizes->pluck('id')->all();

        $categories = Category::pluck('name', 'id')->all();
        $colors = Color::pluck('name', 'id')->all();
        $sizes = Size::pluck('name', 'id')->all();

        return view('admin.crud.product-edit', compact('categories', 'product', 'colors', 'sizes', 'productColors', 'productSizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $dataProduct = [
                'category_id' => $request->category_id,
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
            $product->colors()->sync($request->colors);
            $product->sizes()->sync($request->sizes);
        });

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->orders()->exists()) {
            return redirect()->route('admin.product.index')->with('error', 'Sản phẩm này đang trong quá trình đặt hàng, không thể xóa!');
        }
        DB::transaction(function () use ($product) {
            $product->colors()->sync([]);
            $product->sizes()->sync([]);

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

        return redirect()->route('admin.product.index')->with('success', 'Xóa thành công sản phẩm!');
    }
}
