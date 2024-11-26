<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'galleries' => 'required|array',
            'galleries.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'colors' => 'required|array',  // Yêu cầu mảng màu sắc
            'sizes' => 'required|array',   // Yêu cầu mảng kích thước

        ];
    }
    public function messages()
    {
        return [
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'image_path.required' => 'Ảnh sản phẩm là bắt buộc.',
            'galleries.required' => 'Ảnh sản phẩm là bắt buộc.',
            'description.required' => 'Mô sản phẩm là bắt buộc.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'sku.unique' => 'Mã sản phẩm đã tồn tại.',
            'sku.required' => 'Mã sản phẩm là bắt buộc.',
            'image_path.image' => 'Ảnh sản phẩm phải là một ảnh hợp lệ.',
            'galleries.array' => 'Galleries phải là một mảng ảnh.',
            'galleries.*.image' => 'Ảnh trong gallery phải là một ảnh hợp lệ.',
            'colors.required' => 'Màu sắc là bắt buộc.',
            'colors.array' => 'Màu sắc phải là một mảng.',
            'sizes.required' => 'Kích thước là bắt buộc.',
            'sizes.array' => 'Kích thước phải là một mảng.',

        ];
    }
}
