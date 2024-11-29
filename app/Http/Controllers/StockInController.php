<?php

namespace App\Http\Controllers;

use App\Exports\StockInExport;
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
        $this->middleware('can:Stock In Edit')->only(['edit', 'update']);
        $this->middleware('can:Stock In Delete')->only('destroy');
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
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['product_id', 'qty']);
            $data['received_date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth()->id();
            $data['code'] = StockIn::getNewCode();
            $stokIn = StockIn::create($data);
            $stokIn->product->increment('qty', request('qty'));

            DB::commit();
            return redirect()->route('stock-ins.index')->with('success', 'Stock In Berhasil dibuat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('stock-ins.index')->with('error', $th->getMessage());
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

    public function edit($id)
    {
        $item = StockIn::findOrFail($id);
        return view('pages.stock-in.edit', [
            'title' => 'Edit Stock In',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'received_date' => ['required', 'date']
        ]);

        DB::beginTransaction();
        try {
            $item = StockIn::findOrFail($id);
            $data = request()->only(['received_date', 'notes']);
            $item->update($data);
            DB::commit();
            return redirect()->route('stock-ins.index')->with('success', 'Stock In berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function report_index()
    {
        return view('pages.stock-in.report', [
            'title' => 'Stock In Report',
            'suppliers' => Supplier::orderBy('name', 'DESC')->get(),
            'items' => [],
            'start_date' => null,
            'end_date' => null,
            'supplier_id' => null
        ]);
    }

    public function report_action()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $supplier_id = request('supplier_id');
        $action = request('action');

        $items = StockIn::with(['product']);
        if ($start_date && $end_date) {
            $items->whereDate('received_date', '>=', $start_date)
                ->whereDate('received_date', '<=', $end_date);
        } elseif ($start_date && !$end_date) {
            $items->whereHas('stock_in', function ($q) use ($start_date) {
                $q->whereDate('received_date', $start_date);
            });
        }

        if ($supplier_id) {
            $items->whereHas('product', function ($q) use ($supplier_id) {
                $q->where('supplier_id', $supplier_id);
            });
        }

        $data = $items->orderBy('id', 'DESC')->get();
        $supplier = Supplier::find($supplier_id);

        if ($action === 'export_pdf') {
            $pdf = Pdf::loadView('pages.stock-in.export-pdf', [
                'title' => 'Export PDF Stock In',
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'supplier' => $supplier
            ]);
            $fileName = "StockIn-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.pdf';
            return $pdf->download($fileName);
        } elseif ($action === 'export_excel') {
            $arr = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'supplier' => $supplier,
                'items' => $data
            ];
            $fileName = "StockIn-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.xlsx';
            return Excel::download(new StockInExport($arr), $fileName);
        } else {
            return view('pages.stock-in.report', [
                'title' => 'Stock In Report',
                'suppliers' => Supplier::orderBy('name', 'DESC')->get(),
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'supplier_id' => $supplier_id,
                'supplier' => $supplier,
            ]);
        }
    }
}
