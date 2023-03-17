<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Imports\CustomerImport;

class ImportCustomer extends Controller
{
    public function __invoke(Request $request)
    {
        $import = new CustomerImport();
        $import->import($request->customers_file);

        return response()->json([
            'message' => 'successful',
            'error' => $import->failures()->toArray(),
        ]);
    }
}
