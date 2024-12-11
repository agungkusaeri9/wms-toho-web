<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\ResponseFormatter;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $keyword = $request->get('keyword', '');
        $items = Product::orderBy('name', 'asc');
        if ($keyword) {
            $items->where('name', 'like', '%' . $keyword . '%');
        }
        $products = $items->paginate($limit);
        $pagination = [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ];
        $products = $products->getCollection()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock(),
            ];
        });
        return ResponseFormatter::success($products, 'Products fetched', 200, $pagination);
    }
}
