<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\Area;
use App\Models\Department;
use App\Models\PartNumber;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInDetail;
use App\Models\StockOut;
use App\Models\StockOutDetail;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Product Index')->only('index');
        $this->middleware('can:Product Create')->only(['create', 'store']);
        $this->middleware('can:Product Edit')->only(['edit', 'update']);
        $this->middleware('can:Product Delete')->only('destroy');
    }

    public function index()
    {
        $items = Product::with(['department', 'unit', 'part_number'])->orderBy('name', 'ASC')->get();
        return view('pages.product.index', [
            'title' => 'Product',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.product.create', [
            'title' => 'Create Product',
            'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
            'units' => Unit::orderBy('name', 'ASC')->get(),
            'departments' => Department::orderBy('name', 'ASC')->get(),
            'areas' => Area::orderBy('name', 'ASC')->get(),
            'suppliers' => Supplier::orderBy('name', 'ASC')->get(),
            'types' => Type::orderBy('name', 'ASC')->get(),
        ]);
    }

    public function store()
    {
        request()->validate([
            'part_number_id' => ['required', 'exists:part_numbers,id'],
            'name' => ['required'],
            'unit_id' => ['required', 'exists:units,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'initial_qty' => ['required', 'numeric'],
            'image' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'area_id' => ['required', 'exists:areas,id'],
            'rack_id' => ['required', 'exists:racks,id'],
            'lot_number' => ['required', 'unique:products,lot_number'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'type_id' => ['required', 'exists:types,id'],
        ]);

        // cek lot dan part number
        $cekLotPart = Product::where('lot_number', request('lot_number'))->where('part_number_id', request('part_number_id'))->first();
        if ($cekLotPart) {
            return redirect()->back()->with('errors', 'Lot No. sudah terpakai di Part No.');
        }

        DB::beginTransaction();
        try {
            $data = request()->only(['code', 'name', 'part_number_id', 'unit_id', 'department_id', 'initial_qty', 'description', 'area_id', 'rack_id', 'lot_number', 'supplier_id', 'type_id']);
            if (request()->file('image')) {
                $data['image'] = request()->file('image')->store('prduct', 'public');
            }
            if (request('initial_qty')) {
                $data['qty'] = request('initial_qty');
            }
            Product::create($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Product::findOrFail($id);
        return view('pages.product.edit', [
            'title' => 'Edit Product',
            'item' => $item,
            'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
            'units' => Unit::orderBy('name', 'ASC')->get(),
            'departments' => Department::orderBy('name', 'ASC')->get(),
            'areas' => Area::orderBy('name', 'ASC')->get(),
            'suppliers' => Supplier::orderBy('name', 'ASC')->get(),
            'types' => Type::orderBy('name', 'ASC')->get(),
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'part_number_id' => ['required', 'exists:part_numbers,id'],
            'name' => ['required'],
            'unit_id' => ['required', 'exists:units,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'image' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'area_id' => ['required', 'exists:areas,id'],
            'rack_id' => ['required', 'exists:racks,id'],
            'lot_number' => ['required', 'unique:products,lot_number,' . $id . ''],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'type_id' => ['required', 'exists:types,id'],
        ]);

        // cek lot dan part number
        $cekLotPart = Product::where('lot_number', request('lot_number'))->where('part_number_id', request('part_number_id'))->whereNot('id', $id)->first();
        if ($cekLotPart) {
            return redirect()->back()->with('errors', 'Lot No. sudah sudah ada di product lain.');
        }


        DB::beginTransaction();
        try {
            $item = Product::findOrFail($id);
            $data = request()->only(['code', 'name', 'part_number_id', 'unit_id', 'department_id', 'initial_qty', 'description', 'area_id', 'rack_id', 'lot_number', 'supplier_id', 'type_id']);
            if (request()->file('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $data['image'] = request()->file('image')->store('prduct', 'public');
            }
            $item->update($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Product::findOrFail($id);
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getById()
    {
        if (request()->ajax()) {
            $item = Product::with(['part_number', 'unit'])->find(request('id'));
            return response()->json($item);
        }
    }

    public function show($id)
    {
        $item = Product::findOrFail($id);
        $stock_ins = StockIn::where('product_id', $id)->latest()->get();
        $stock_outs = StockOut::where('product_id', $id)->latest()->get();
        return view('pages.product.show', [
            'title' => 'Detail Product',
            'item' => $item,
            'stock_ins' => $stock_ins,
            'stock_outs' => $stock_outs,
        ]);
    }

    public function report_index()
    {
        return view('pages.product.report', [
            'title' => 'Product Report',
            'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
            'items' => Product::orderBy('name', 'ASC')->get(),
            'part_number_id' => null,
            'part_number' => []
        ]);
    }

    public function report_action()
    {
        $part_number_id = request('part_number_id');
        $action = request('action');
        $items = Product::with(['part_number', 'unit', 'department']);

        if ($part_number_id) {
            $items->where('part_number_id', $part_number_id);
        }

        $data = $items->orderBy('id', 'DESC')->get();
        $part_number = PartNumber::find($part_number_id);

        if ($action === 'export_pdf') {
            $pdf = Pdf::loadView('pages.product.export-pdf', [
                'title' => 'Export PDF Product',
                'items' => $data,
                'part_number' => $part_number
            ]);
            $fileName = "Product-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.pdf';
            return $pdf->download($fileName);
        } elseif ($action === 'export_excel') {
            $arr = [
                'part_number' => $part_number,
                'items' => $data
            ];
            $fileName = "Product-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.xlsx';
            return Excel::download(new ProductExport($arr), $fileName);
        } else {
            return view('pages.product.report', [
                'title' => 'Product Report',
                'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
                'items' => $data,
                'part_number_id' => $part_number_id,
                'part_number' => $part_number
            ]);
        }
    }
}
