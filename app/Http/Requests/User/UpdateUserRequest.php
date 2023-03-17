<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateUserRequest extends FormRequest
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
            'password' => [
                'confirmed',
                'required',
                'min:5',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/i',
            ],
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', 'unique:'.get_class(new User()).',email,'.request()->id ],
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
            'name.required' => 'Vui lòng nhập tên người sử dụng',
            'name.min' => 'Tên phải lớn hơn 5 ký tự.',

            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được đăng ký.',

            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải hơn 5 ký tự.',
            'password.regex' => 'Mật khẩu không bảo mật.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không chính xác.',
        ];
    }
}
