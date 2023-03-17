<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;

class ExportCustomer extends Controller
{
    public function __invoke(Request $request)
    {
        return (new CustomerExport($request))->download('customers.csv');
    }
}
