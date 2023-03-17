<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'product_name' => ['required', 'min:5'],
            'product_price' => ['required', 'numeric', 'min:0'],
            'is_sales' => ['required'],
            'product_image' => [
                'nullable',
                'file',
                'mimes:jpg,png,jpeg',
                'max:2048',
                'dimensions:max_width=1024'
            ],
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
            'product_name.name' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.min' => 'Tên phải lớn hơn 5 ký tự.',
            'product_price.required' => 'Giá bán không được để trống.',
            'product_price.numeric' => 'Giá bán chỉ được nhập số.',
            'product_price.min' => 'Giá bán không được nhỏ hơn 0.',
            'is_sales.required' => 'Trạng thái không được để trống.',
            'product_image.mimes' => 'Chỉ cho upload các file hình png, jpg, jpeg.',
            'product_image.max' => 'Dung lượng hình không quá 2Mb.',
            'product_image.dimensions' => 'kích thước không quá 1024px.',
        ];
    }
}
