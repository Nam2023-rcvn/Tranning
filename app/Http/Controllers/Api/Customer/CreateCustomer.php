<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Routing\Controller;
use App\Models\Customer;
use App\Http\Requests\Cusomter\CreateCustomerRequest;

class CreateCustomer extends Controller
{
    public function __invoke(CreateCustomerRequest $request)
    {
        Customer::create($request->only([
            'customer_name',
            'email',
            'tel_num',
            'address',
            'is_active',
        ]));

        return response()->json([
            'message' => 'successful',
            'error' => [],
            'created' => true
        ], 201);
    }
}
