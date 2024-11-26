<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserClientRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric|regex:/^0\d{9}$/',
            'gender' => 'nullable',
            'birth_date' => 'nullable|date|before:today|after:1920-01-01|before:2010-12-31', // Sinh nhật phải từ năm 1920 đến 2010
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'name.string' => 'Tên phải là chuỗi ký tự.',
            'phone.numeric' => 'Số điện thoại chỉ được nhập số.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số.',
            'birth_date.before' => 'Ngày sinh không được sau ngày hiện tại.',
            'birth_date.after' => 'Ngày sinh phải sau ngày 01/01/1920.',
            'birth_date.before' => 'Ngày sinh phải trước ngày 31/12/2010.',
            'birth_date.date' => 'Ngày sinh phải có định dạng hợp lệ.',
        ];
    }
}
