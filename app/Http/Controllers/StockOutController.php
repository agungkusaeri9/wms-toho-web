<?php

namespace App\Http\Controllers;

use App\Exports\StockOutExport;
use App\Models\Department;
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
        $this->middleware('can:Stock Out Edit')->only(['edit', 'update']);
        $this->middleware('can:Stock Out Delete')->only('destroy');
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
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric']
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail(request('product_id'));
            if ($product->qty < request('qty')) {
                return redirect()->back()->with('error', 'Qty tidak boleh melebihi stock');
            }
            $data = request()->only(['product_id', 'qty']);
            $data['department_id'] = $product->department_id;
            $data['date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = auth()->id();
            $data['code'] = StockOut::getNewCode();
            $stockOut = StockOut::create($data);
            $stockOut->product->decrement('qty', request('qty'));

            DB::commit();
            return redirect()->route('stock-outs.index')->with('success', 'Stock Out Berhasil dibuat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('stock-outs.index')->with('error', $th->getMessage());
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

    public function edit($id)
    {
        $item = StockOut::findOrFail($id);
        return view('pages.stock-out.edit', [
            'title' => 'Edit Stock Out',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'date' => ['required', 'date']
        ]);

        DB::beginTransaction();
        try {
            $item = StockOut::findOrFail($id);
            $data = request()->only(['date', 'notes']);
            $item->update($data);
            DB::commit();
            return redirect()->route('stock-outs.index')->with('success', 'Stock Out berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function report_index()
    {
        return view('pages.stock-out.report', [
            'title' => 'Stock Out Report',
            'items' => [],
            'departments' => Department::orderBy('name', 'DESC')->get(),
            'start_date' => null,
            'end_date' => null,
            'department_id' => null,
        ]);
    }

    public function report_action()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $department_id = request('department_id');
        $action = request('action');

        $items = StockOut::with(['product']);
        if ($start_date && $end_date) {
            $items->whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date);
        } elseif ($start_date && !$end_date) {
            $items->whereHas('stock_out', function ($q) use ($start_date, $end_date) {
                $q->whereDate('date', $start_date);
            });
        }

        if ($department_id) {
            $items->where('department_id', $department_id);
        }

        $data = $items->orderBy('id', 'DESC')->get();
        $department = Department::find($department_id);

        if ($action === 'export_pdf') {
            $pdf = Pdf::loadView('pages.stock-out.export-pdf', [
                'title' => 'Export PDF Stock Out',
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'department' => $department,
            ]);
            $fileName = "StockOut-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.pdf';
            return $pdf->download($fileName);
        } elseif ($action === 'export_excel') {
            $arr = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'department' => $department,
                'items' => $data
            ];
            $fileName = "StockOut-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.xlsx';
            return Excel::download(new StockOutExport($arr), $fileName);
        } else {
            return view('pages.stock-out.report', [
                'title' => 'Stock Out Report',
                'departments' => Department::orderBy('name', 'DESC')->get(),
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'department_id' => $department_id,
                'department' => $department,
            ]);
        }
    }
}
