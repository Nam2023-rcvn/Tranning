<?php

namespace App\Http\Requests\Cusomter;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer;

class CreateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_name' => ['required', 'min:5'],
            'email' => ['required', 'email', 'unique:'.get_class(new Customer()).',email'],
            'address' => ['required'],
            'tel_num' => ['required']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập tên khách hàng.',
            'customer_name.min' => 'Tên khách hàng phải lớn hơn 5 ký tự.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được đăng ký.',

            'address.unique' => 'Địa chỉ không được để trống.',

            'tel_num.required' => 'Điện thoại không được để trống.',
        ];
    }
}
