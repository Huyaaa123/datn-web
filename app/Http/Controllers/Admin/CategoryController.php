<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả danh mục
        $categories = Category::all();
        return view('admin.category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crud.category-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Lưu danh mục mới vào cơ sở dữ liệu
        Category::create($request->validated());

        // Quay lại trang danh sách với thông báo thành công
        return redirect()->route('admin.category.index')->with('success', 'Category add successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.crud.category-edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Cập nhật danh mục hiện tại
        $category->update($request->all());

        // Quay lại trang danh sách với thông báo thành công
        return redirect()->route('admin.category.index')->with('success', 'Category update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Xóa danh mục
        $category->delete();

        // Quay lại trang danh sách với thông báo thành công
        return redirect()->route('admin.category.index')->with('success', 'Category delete successfully!');
    }
}
