<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Routing\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DeleteProduct extends Controller
{
    public function __invoke($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'successful',
            'error' => [],
        ]);
    }
}
