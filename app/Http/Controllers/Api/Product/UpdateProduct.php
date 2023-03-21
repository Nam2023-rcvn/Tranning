<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

class UpdateProduct extends Controller
{
    public function __invoke($id, UpdateProductRequest $request)
    {
        $product = Product::findOrFail($id);

        $data = $request->only([
            'product_name',
            'product_price',
            'is_sales',
            'description',
        ]);


        if ($request->filled('product_image') && is_string($request->product_image)) {
            $path = parse_url($request->product_image)['path'];
            $data['product_image'] = str_replace('/tmp/', '', $path);
        } elseif ($request->hasFile('product_image')) {
            $data['product_image'] = Storage::disk('tmp')->put('/products', $request->product_image);
        } else {
            $data['product_image'] = null;
        }

        $product->update($data);

        return response()->json([
            'message' => 'successful',
            'error' => [],
        ]);
    }
}
