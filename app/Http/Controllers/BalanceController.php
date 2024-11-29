<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        $product_id = request('product_id');
        $items = Product::get();
        return view('pages.balance.report', [
            'title' => 'Balance',
            'items' => $items
        ]);
    }
}
