<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CustomerImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure
{
    use Importable;
    use SkipsFailures;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new Customer([
            'customer_name' => $row[0],
            'email' => $row[1],
            'tel_num' => $row[2],
            'address' => $row[3],
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => ['required', 'min:5'],
            '1' => ['required', 'email', 'unique:'.get_class(new Customer()).',email'],
            '2' => ['required'],
            '3' => ['required']
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Vui lòng nhập tên khách hàng.',
            '0.min' => 'Tên khách hàng phải lớn hơn 5 ký tự.',

            '1.required' => 'Email không được để trống.',
            '1.email' => 'Email không đúng định dạng.',
            '1.unique' => 'Email đã được đăng ký.',

            '2.required' => 'Điện thoại không được để trống.',
            '3.unique' => 'Địa chỉ không được để trống.',
        ];
    }
}
