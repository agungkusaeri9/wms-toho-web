<?php

namespace App\Http\Controllers;

use App\Exports\StockOutExport;
use App\Models\Department;
use App\Models\Generate;
use App\Models\Product;
use App\Models\StockOut;
use App\Models\StockOutDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StockOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Stock Out Index')->only('index');
        $this->middleware('can:Stock Out Create')->only(['create', 'store']);
        $this->middleware('can:Report Stock Out')->only(['report_index', 'report_action']);
    }

    public function index()
    {
        $items = StockOut::orderBy('id', 'DESC')->get();
        return view('pages.stock-out.index', [
            'title' => 'Stock Out',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.stock-out.create', [
            'title' => 'Create Stock Out',
            'latest_code' => StockOut::getNewCode(),
            'products' => Product::orderBy('name', 'ASC')->get(),
            'departments' => Department::orderBy('name', 'ASC')->get()
        ]);
    }

    public function store()
    {
        request()->validate([
            'generate_code' => ['required'],
            'qty' => ['required', 'numeric']
        ]);
        $generate = Generate::where('code', request('generate_code'))->first();

        if ($generate->qty < request('qty')) {
            return redirect()->back()->with('error', 'Qty melebihi stock');
        }
        DB::beginTransaction();
        try {
            $data = request()->only(['qty']);
            $data['product_id'] = $generate->product_id;
            $data['date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth()->id();
            $data['code'] = StockOut::getNewCode();
            $data['department_id'] = $generate->product->department_id;
            $data['generate_id'] = $generate->id;
            $stockout = StockOut::create($data);

            if ($generate->qty != request('qty')) {
                // jika qty tidak sama dengan stock
                // update sisa stok di generate
                $sisa = $generate->qty - request('qty');
                $generate->update([
                    'qty' => $sisa
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Stock Out has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $item = StockOut::with('details.product')->findOrFail($id);
        return view('pages.stock-out.show', [
            'title' => 'Detail Stock Out',
            'item' => $item
        ]);
    }

    public function report_index()
    {
        return view('pages.stock-out.report', [
            'title' => 'Stock Out Report',
            'items' => [],
            'products' => Product::orderBy('name', 'ASC')->get(),
            'start_date' => null,
            'end_date' => null,
            'product_id' => null,
        ]);
    }

    public function report_action()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $product_id = request('product_id');
        $action = request('action');

        $items = StockOut::with(['product']);
        if ($start_date && $end_date) {
            $items->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        } elseif ($start_date && !$end_date) {
            $items->whereDate('created_at', $start_date);
        }

        if ($product_id) {
            $items->where('product_id', $product_id);
        }

        $data = $items->orderBy('id', 'DESC')->get();
        $product = Product::find($product_id);

        if ($action === 'export_pdf') {
            $pdf = Pdf::loadView('pages.stock-out.export-pdf', [
                'title' => 'Export PDF Stock Out',
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'product' => $product,
            ]);
            $fileName = "StockOut-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.pdf';
            return $pdf->download($fileName);
        } elseif ($action === 'export_excel') {
            $arr = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'product' => $product,
                'items' => $data
            ];
            $fileName = "StockOut-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.xlsx';
            return Excel::download(new StockOutExport($arr), $fileName);
        } else {
            return view('pages.stock-out.report', [
                'title' => 'Stock Out Report',
                'products' => product::orderBy('name', 'DESC')->get(),
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'product_id' => $product_id,
                'product' => $product,
            ]);
        }
    }
}
