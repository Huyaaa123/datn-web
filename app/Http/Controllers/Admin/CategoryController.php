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

        return redirect()->route('admin.category.index')->with('success', 'Category added successfully!');
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

        return redirect()->route('admin.category.index')->with('success', 'Category update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Xóa danh mục
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category delete successfully!');
    }
}
