<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function getInfoToday()
    {
        try {
            $data = [
                'total_product' => Product::count(),
                'total_stock_in' => StockIn::whereDate('created_at', now()->format('Y-m-d'))->sum('qty'),
                'total_stock_out' => StockOut::whereDate('created_at', now()->format('Y-m-d'))->sum('qty'),
            ];
            return ResponseFormatter::success($data, 'Info Today', 200);
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }
}
