<?php

namespace App\Http\Controllers;

use App\Exports\StockInExport;
use App\Models\Generate;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInDetail;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StockInController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Stock In Index')->only('index');
        $this->middleware('can:Stock In Create')->only(['create', 'store']);
        $this->middleware('can:Report Stock In')->only(['report_index', 'report_action']);
    }

    public function index()
    {
        $items = StockIn::orderBy('id', 'DESC')->get();
        return view('pages.stock-in.index', [
            'title' => 'Stock In',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.stock-in.create', [
            'title' => 'Create Stock In',
            'latest_code' => StockIn::getNewCode(),
            'products' => Product::orderBy('name', 'ASC')->get(),
            'suppliers' => Supplier::orderBy('name', 'ASC')->get()
        ]);
    }

    public function store()
    {
        request()->validate([
            'generate_code' => ['required'],
            'qty' => ['required', 'numeric']
        ]);

        DB::beginTransaction();
        try {
            $generate = Generate::where('code', request('generate_code'))->first();

            if ($generate->status == 1) {
                return redirect()->back()->with('error', 'The QR code has already been used properly.');
            }
            $generate->update(['status' => 1]);
            $data = request()->only(['qty']);
            $data['product_id'] = $generate->product_id;
            $data['received_date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth()->id();
            $data['code'] = StockIn::getNewCode();
            $data['generate_id'] = $generate->id;
            $stokIn = StockIn::create($data);
            $generate->product->increment('stock', $stokIn->qty);
            DB::commit();
            return redirect()->back()->with('success', 'Stock In has been created successfully.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $item = StockIn::with('details.product')->findOrFail($id);
        return view('pages.stock-in.show', [
            'title' => 'Detail Stock In',
            'item' => $item
        ]);
    }
    public function report_index()
    {
        return view('pages.stock-in.report', [
            'title' => 'Stock In Report',
            'products' => Product::orderBy('name', 'ASC')->get(),
            'items' => [],
            'start_date' => null,
            'end_date' => null,
            'product_id' => null
        ]);
    }

    public function report_action()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $product_id = request('product_id');
        $action = request('action');

        $items = StockIn::with(['product']);
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
            $pdf = Pdf::loadView('pages.stock-in.export-pdf', [
                'title' => 'Export PDF Stock In',
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'product' => $product
            ]);
            $fileName = "StockIn-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.pdf';
            return $pdf->download($fileName);
        } elseif ($action === 'export_excel') {
            $arr = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'product' => $product,
                'items' => $data
            ];
            $fileName = "StockIn-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.xlsx';
            return Excel::download(new StockInExport($arr), $fileName);
        } else {
            return view('pages.stock-in.report', [
                'title' => 'Stock In Report',
                'products' => Product::orderBy('name', 'DESC')->get(),
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'product_id' => $product_id,
                'product' => $product,
            ]);
        }
    }
}
