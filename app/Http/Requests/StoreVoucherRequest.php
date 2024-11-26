<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'code' => 'required|unique:vouchers,code|regex:/^[a-zA-Z0-9]+$/',
            'discount_type' => 'required|in:amount,percent',
            'discount_amount' => 'nullable|numeric|min:0|max:1000000|required_if:discount_type,amount',
            'discount_percent' => 'nullable|numeric|min:0|max:50|required_if:discount_type,percent',
            'min_order_value' => 'required|numeric|min:0|max:1000000',
            'usage_limit' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.regex' => 'Mã giảm giá chỉ được chứa chữ cái không dấu và số.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_amount.required_if' => 'Giá trị giảm giá là bắt buộc .',
            'discount_amount.numeric' => 'Giá trị giảm giá phải là số.',
            'discount_amount.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_amount.max' => 'Giá trị giảm giá tối đa là 1,000,000 .',
            'discount_percent.required_if' => 'Phần trăm giảm giá là bắt buộc .',
            'discount_percent.numeric' => 'Phần trăm giảm giá phải là số.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0.',
            'discount_percent.max' => 'Phần trăm giảm giá không thể vượt quá 50.',
            'min_order_value.required' => 'Giá trị đơn hàng tối thiểu là bắt buộc.',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu lớn hơn hoặc bằng 0.',
            'min_order_value.max' => 'Giá trị đơn hàng tối thiểu tối đa là 1,000,000.',
            'usage_limit.required' => 'Giới hạn sử dụng là bắt buộc.',
            'usage_limit.numeric' => 'Giới hạn sử dụng phải là số.',
            'usage_limit.min' => 'Giới hạn sử dụng lớn hơn 0.',
            'usage_limit.max' => 'Giới hạn sử dụng nhỏ hơn 100.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải là ngày hôm nay hoặc tương lai.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
        ];
    }
        /**
     * Các thuộc tính xác thực.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'code' => 'Mã giảm giá',
            'discount_type' => 'Loại giảm giá',
            'discount_amount' => 'Giá trị giảm giá',
            'discount_percent' => 'Phần trăm giảm giá',
            'min_order_value' => 'Giá trị đơn hàng tối thiểu',
            'usage_limit' => 'Giới hạn sử dụng',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
        ];
    }

}
