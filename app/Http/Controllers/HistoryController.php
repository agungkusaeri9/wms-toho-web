<?php

namespace App\Http\Controllers;

use App\Models\Generate;
use App\Models\Product;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $generate_id = request('generate_id');
        $product_id = request('product_id');
        $items = Generate::with(['product', 'product.part_number', 'product.unit', 'product.department']);
        if ($generate_id) {
            $items->where('id', $generate_id);
            $generate = Generate::find($generate_id);
        }
        if ($product_id) {
            $items->where('product_id', $product_id);
        }
        $products = Product::orderBy('name', 'ASC')->get();
        $data = $items->latest()->get();

        return view('pages.history.index', [
            'title' => 'History',
            'items' => $data,
            'products' => isset($products) ? $products : null,
            'generate_id' => $generate_id,
            'product_id' => $product_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'generate' => isset($generate) ? $generate : null
        ]);
    }
}
