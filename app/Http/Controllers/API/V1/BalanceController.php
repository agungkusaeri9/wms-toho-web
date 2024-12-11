<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');

        $items = Product::with(['part_number', 'unit', 'department']);

        if ($start_date && $end_date) {
            $items->whereHas('stock_in', function ($q) use ($start_date, $end_date) {
                $q->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            });
        } elseif ($start_date && !$end_date) {
            $items->whereDate('created_at', $start_date);
        }

        $data = $items->whereDoesntHave('stock_in')->orderBy('id', 'DESC')->get();

        return ResponseFormatter::success();
    }
}
