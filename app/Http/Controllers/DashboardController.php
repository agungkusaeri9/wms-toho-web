<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Dashboard')->only('index');
    }

    public function index()
    {
        $count = [
            'user' => User::count(),
            'role' => Role::count(),
            'permission' => Permission::count()
        ];
        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'count' => $count
        ]);
    }

    public function getStockData()
    {
        if (request()->ajax()) {
            $startDate = Carbon::now()->subDays(6)->startOfDay(); // Mulai dari 7 hari yang lalu termasuk hari ini
            $endDate = Carbon::now()->endOfDay();

            $stockIn = DB::table('stock_ins')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(qty) as total')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->pluck('total', 'date');

            $stockOut = DB::table('stock_outs')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(qty) as total')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->pluck('total', 'date');


            $dates = collect(range(0, 6))
                ->map(function ($day) {
                    return Carbon::now()->subDays($day)->format('Y-m-d');
                })->sort()->values()->toArray();

            $data = [
                'dates' => $dates,
                'stockIn' => array_map(fn($date) => $stockIn->get($date, 0), $dates),
                'stockOut' => array_map(fn($date) => $stockOut->get($date, 0), $dates),
            ];

            return response()->json($data);
        }
    }

    public function getProductQtyData()
    {
        // Ambil data qty per product
        $data = DB::table('products')
            ->join('stock_ins', 'products.id', '=', 'stock_ins.product_id')
            ->select('products.name as product_name')
            ->groupBy('products.id')
            ->get();

        $data->map(function ($product) {
            $data['qty'] = $product->remains();
        });

        // Persiapkan data dalam format yang sesuai untuk Doughnut chart
        $products = $data->pluck('qty', 'product_name')->toArray();

        return response()->json($products);
    }
}
