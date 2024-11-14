<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'name' => 'required|string|max:255',  // Ensure phone number is required and has a max length
            'phone' => 'required|string|max:15',  // Ensure phone number is required and has a max length
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'city.required' => 'Thành phố là bắt buộc.',
            'district.required' => 'Quận huyện là bắt buộc.',
            'ward.required' => 'Phường xã là bắt buộc.',
            'address.required' => 'Số nhà, tên đường là bắt buộc.',
        ];
    }
}
