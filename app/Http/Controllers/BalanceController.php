<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Report Balance')->only('index');
    }
    public function index()
    {
        $items = Product::get();
        return view('pages.balance.report', [
            'title' => 'Balance',
            'items' => $items
        ]);
    }
}
