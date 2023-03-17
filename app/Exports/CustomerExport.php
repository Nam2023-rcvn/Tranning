<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class CustomerExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $request = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return $this->filterCustomers(Customer::query());
    }

    public function filterCustomers($query)
    {
        $customers = $query->latest();
        $request = $this->request;

        if ($request->filled('customer_name')) {
            $customers->where('customer_name', 'like', '%'.$request->customer_name.'%');
        }

        if ($request->filled('email')) {
            $customers->where('email', $request->email);
        }

        if ($request->filled('is_active')) {
            $customers->where('is_active', $request->is_active);
        }

        if ($request->filled('address')) {
            $customers->where('address', 'like', '%'.$request->address.'%');
        }

        return $customers;
    }

    public function headings(): array
    {
        return [
            'Tên khách hàng',
            'Email',
            'TelNum',
            'Địa chỉ',
        ];
    }

    /**
    * @var Customer $customer
    */
    public function map($customer): array
    {
        return [
            $customer->customer_name,
            $customer->email,
            $customer->tel_num,
            $customer->address,
        ];
    }
}
