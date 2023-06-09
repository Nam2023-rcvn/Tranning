<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Product\GetProductListRequest;

class GetProductList extends Controller
{
    public function __invoke(GetProductListRequest $request)
    {
        $products = $this->filterProducts($request);

        if ($request->page == 1) {
            $products = $products->paginate(20);
            if ($products->total() > 20) {
                $products->setCollection($products->getCollection()->take(10));
                $products->resolveCurrentPage('page', 1);
            }
        } else {
            $products = $products->paginate($request->page_size ?? 10);
        }

        return ProductResource::collection($products);
    }

    public function filterProducts(GetProductListRequest $request): Builder
    {
        $products = Product::latest();

        if ($request->filled('product_name')) {
            $products->where('product_name', 'like', '%'.$request->product_name.'%');
        }

        if ($request->filled('is_sales')) {
            $products->where('is_sales', $request->is_sales);
        }

        if ($request->filled('product_price_from')) {
            $products->where('product_price', '>=', $request->product_price_from);
        }

        if ($request->filled('product_price_to')) {
            $products->where('product_price', '<=', $request->product_price_to);
        }

        return $products;
    }
}
