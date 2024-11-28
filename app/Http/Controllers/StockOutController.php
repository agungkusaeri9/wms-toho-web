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
        $items = StockOut::orderBy('code', 'ASC')->get();
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
        $validator = Validator::make(request()->all(), [
            'department_id' => ['required', 'exists:departments,id'],
            'date' => ['required', 'date'],
            'data_json' => ['required']
        ], [
            'department_id.required' => 'Department is required and must exist in the database.',
            'date.required' => 'The received date is required.',
            'date.date' => 'Please enter a valid date format for the received date.',
            'data_json.required' => 'Product Not Found.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'errors' =>
                $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            $data = request()->only(['department_id', 'date', 'notes']);
            $data_json = request('data_json');
            $data_array = json_decode($data_json, true);
            $data['user_id'] = auth()->id();
            $data['code'] = StockOut::getNewCode();
            $stockOut  = StockOut::create($data);
            foreach ($data_array as $item) {
                $stockOut->details()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty']
                ]);

                // update stok
                $product = Product::find($item['product_id']);
                $product->decrement('qty', $item['qty']);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Stock Out berhasil dibuat'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
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
        $items = StockOut::orderBy('id', 'DESC')->get();
        return view('pages.stock-out.report', [
            'title' => 'Stock Out Report',
            'items' => $items,
            'departments' => Department::orderBy('name', 'DESC')->get()
        ]);
    }

    public function report_action()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $department_id = request('department_id');
        $action = request('action');

        $items = StockOutDetail::with(['stock_out']);
        if ($start_date && $end_date) {
            $items->whereHas('stock_out', function ($q) use ($start_date, $end_date) {
                $q->whereDate('date', '>=', $start_date)
                    ->whereDate('date', '<=', $end_date);
            });
        } elseif ($start_date && !$end_date) {
            $items->whereHas('stock_out', function ($q) use ($start_date, $end_date) {
                $q->whereDate('date', $start_date);
            });
        }

        if ($department_id) {
            $items->whereHas('stock_out', function ($q) use ($department_id) {
                $q->where('department_id', $department_id);
            });
        }

        $data = $items->orderBy('id', 'DESC')->get();
        $department = Department::find($department_id);

        if ($action === 'export_pdf') {
            $pdf = Pdf::loadView('pages.stock-out.export-pdf', [
                'title' => 'Export PDF Stock Out',
                'items' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'department' => $department
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
        }
    }
}
