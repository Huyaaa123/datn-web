<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category', compact('categories'));
    }

    public function create()
    {
        return view('admin.crud.category-create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $slug = Str::slug($request->name);

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function show(Category $category)
    {
        return view('admin.show.category-show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.crud.category-edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]+$request->except('name', 'slug')
        );

        return redirect()->route('admin.category.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->product()->exists()) {
            // Nếu có sản phẩm, không cho xóa và trả về thông báo lỗi
            return redirect()->route('admin.category.index')->with('error', 'Không thể xóa danh mục vì nó đang chứa các sản phẩm liên quan!');
        }
        // Xóa danh mục
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Xóa danh mục thành công!');
    }
}
