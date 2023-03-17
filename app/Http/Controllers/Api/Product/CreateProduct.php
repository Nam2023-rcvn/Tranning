<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Http\Requests\Product\CreateProductRequest;
use Illuminate\Support\Facades\Storage;

class CreateProduct extends Controller
{
    public function __invoke(CreateProductRequest $request)
    {
        $data = $request->only([
            'product_name',
            'product_price',
            'is_sales',
            'description',
        ]);

        if ($request->hasFile('product_image')) {
            $data['product_image'] = Storage::disk('tmp')->put('/products', $request->product_image);
        } else {
            $data['product_image'] = null;
        }

        Product::create($data);

        return response()->json([
            'message' => 'successful',
            'error' => [],
            'created' => true
        ], 201);
    }
}
