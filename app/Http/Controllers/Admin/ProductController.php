<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.product', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.crud.product-create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function() use ($request){
            $dataProduct = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'product_code' => $request->product_code,
            ];
            if($request->hasFile('image_path')){
                $dataProduct['image_path'] = Storage::put('products', $request->file('image_path'));
            }

            $product = Product::query()->create($dataProduct);
        });
        return redirect()->route('admin.product.index')->with('success', 'Product add successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('category');

        $categories = Category::pluck('name','id')->all();

        return view('admin.crud.product-edit',compact('categories','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::transaction(function() use ($request,$product){
            $dataProduct = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,

            ];
            if($request->hasFile('image_path')){
                $dataProduct['image_path'] = Storage::put('products', $request->file('image_path'));
            }

            $product->update($dataProduct);
        });

        return redirect()->route('admin.product.index')->with('success', 'Product update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::transaction(function() use ($product){
            $product->delete();
        });

        if($product->image_path && Storage::exists($product->image_path)){
            Storage::delete($product->image_path);
        }

        return redirect()->route('admin.product.index')->with('success','Product delete successfully!');
    }
}
