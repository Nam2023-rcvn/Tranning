<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Routing\Controller;
use App\Models\Customer;
use App\Http\Requests\Cusomter\UpdateCustomerRequest;

class UpdateCustomer extends Controller
{
    public function __invoke($id, UpdateCustomerRequest $request)
    {
        $customer = Customer::findOrFail($id);

        $customer->update($request->only([
            'customer_name',
            'email',
            'tel_num',
            'address',
            'is_active',
        ]));

        return response()->json([
            'message' => 'successful',
            'error' => []
        ]);
    }
}
