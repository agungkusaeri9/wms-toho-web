<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
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
            'stock_in_today' => StockIn::whereDate('created_at', Carbon::now())->sum('qty'),
            'stock_out_today' => StockOut::whereDate('created_at', Carbon::now())->sum('qty'),
            'product' => Product::count()
        ];
        return view('pages.dashboard', [
            'title' => 'Dashboard',
            'count' => $count
        ]);
    }

    public function getStockData()
    {
        if (request()->ajax()) {
            $startDate = Carbon::now()->subDays(13)->startOfDay(); // Mulai dari 7 hari yang lalu termasuk hari ini
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


            $dates = collect(range(0, 13))
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
        $data = Product::select('products.name as product_name')
            ->get();  // Ambil semua produk

        // Persiapkan data untuk Doughnut chart
        $products = $data->mapWithKeys(function ($product) {
            // Gunakan metode remains() yang ada di model Product untuk menghitung sisa stok
            $remain = $product->remains2();  // Menggunakan remains() yang sudah ada di model
            return [$product->product_name => $remain];
        })->toArray();

        // Jika data yang ingin ditampilkan dalam chart adalah 'qty' dan 'product_name'
        return response()->json([
            'labels' => array_keys($products),
            'data' => array_values($products),
            'products' => $products
        ]);
    }
}
