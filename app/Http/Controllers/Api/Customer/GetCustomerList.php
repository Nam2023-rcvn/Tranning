<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Routing\Controller;
use App\Models\Customer;
use App\Http\Resources\Customer\CustomerResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Cusomter\GetCustomerListRequest;

class GetCustomerList extends Controller
{
    public function __invoke(GetCustomerListRequest $request)
    {
        $customers = $this->filterCustomers($request);

        return CustomerResource::collection($customers->paginate($request->perPage ?? 10));
    }

    public function filterCustomers(GetCustomerListRequest $request): Builder
    {
        $customers = Customer::latest();

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
}
