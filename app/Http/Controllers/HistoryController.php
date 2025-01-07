<?php

namespace App\Http\Controllers;

use App\Models\Generate;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $generate_id = request('generate_id');
        $product_id = request('product_id');

        $products = Product::orderBy('name', 'ASC')->get();

        $q_stock_ins = StockIn::with(['product', 'generate'])->latest();
        $q_stock_outs = StockOut::with(['product', 'generate'])->latest();

        if ($generate_id) {
            $q_stock_ins->where('generate_id', $generate_id);
            $q_stock_outs->where('generate_id', $generate_id);
        }
        if ($product_id) {
            $q_stock_ins->where('product_id', $product_id);
            $q_stock_outs->where('product_id', $product_id);
        }

        $stock_ins = $q_stock_ins->get();
        $stock_ins->map(function ($item) {
            $item['type'] = 'In';
            return $item;
        });
        $stock_outs = $q_stock_outs->get();
        $stock_outs->map(function ($item) {
            $item['type'] = 'Out';
            return $item;
        });
        $stocks = $stock_ins->merge($stock_outs);
        $stocks = $stocks->sortByDesc('created_at');
        $stocks = $stocks->values();

        return view('pages.history.index', [
            'title' => 'History',
            'items' => $stocks,
            'products' => isset($products) ? $products : null,
            'generate_id' => $generate_id,
            'product_id' => $product_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'generate' => isset($generate) ? $generate : null
        ]);
    }
}
