<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Models\Area;
use App\Models\Department;
use App\Models\Generate;
use App\Models\PartNumber;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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
            'part_number_id' => ['required'],
            'name' => ['required'],
            'unit_id' => ['required'],
            'department_id' => ['required', 'exists:departments,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'area_id' => ['required', 'exists:areas,id'],
            'rack_id' => ['required', 'exists:racks,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'type_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['code', 'name', 'part_number_id', 'unit_id', 'department_id', 'initial_qty', 'description', 'area_id', 'rack_id', 'supplier_id', 'type_id']);

            // cek partnumber apakah ada
            $cekPartNumber = PartNumber::where('id', request('part_number_id'))->first();
            if (!$cekPartNumber) {
                $partNumber = PartNumber::create([
                    'name' => request('part_number_id'),
                ]);
                $data['part_number_id'] = $partNumber->id;
            } else {
                $data['part_number_id'] = $cekPartNumber->id;
            }

            // cek type apakah ada
            $cekType = Type::where('id', request('type_id'))->first();
            if (!$cekType) {
                $type = Type::create([
                    'name' => request('type_id'),
                ]);
                $data['type_id'] = $type->id;
            } else {
                $data['type_id'] = $cekType->id;
            }

            // cek unit apakah ada
            $cekUnit = Unit::where('id', request('unit_id'))->first();
            if (!$cekUnit) {
                $unit = Unit::create([
                    'name' => request('unit_id'),
                ]);
                $data['unit_id'] = $unit->id;
            } else {
                $data['unit_id'] = $cekUnit->id;
            }

            if (request()->file('image')) {
                $data['image'] = request()->file('image')->store('prduct', 'public');
            }
            Product::create($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
            'part_number_id' => ['required'],
            'name' => ['required'],
            'unit_id' => ['required'],
            'department_id' => ['required', 'exists:departments,id'],
            'image' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'area_id' => ['required', 'exists:areas,id'],
            'rack_id' => ['required', 'exists:racks,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'type_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $item = Product::findOrFail($id);
            $data = request()->only(['code', 'name', 'part_number_id', 'unit_id', 'department_id', 'description', 'area_id', 'rack_id', 'supplier_id', 'type_id']);

            // cek partnumber apakah ada
            $cekPartNumber = PartNumber::where('id', request('part_number_id'))->first();
            if (!$cekPartNumber) {
                $partNumber = PartNumber::create([
                    'name' => request('part_number_id'),
                ]);
                $data['part_number_id'] = $partNumber->id;
            } else {
                $data['part_number_id'] = $cekPartNumber->id;
            }

            // cek type apakah ada
            $cekType = Type::where('id', request('type_id'))->first();
            if (!$cekType) {
                $type = Type::create([
                    'name' => request('type_id'),
                ]);
                $data['type_id'] = $type->id;
            } else {
                $data['type_id'] = $cekType->id;
            }

            // cek unit apakah ada
            $cekUnit = Unit::where('id', request('unit_id'))->first();
            if (!$cekUnit) {
                $unit = Unit::create([
                    'name' => request('unit_id'),
                ]);
                $data['unit_id'] = $unit->id;
            } else {
                $data['unit_id'] = $cekUnit->id;
            }

            if (request()->file('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $data['image'] = request()->file('image')->store('prduct', 'public');
            }
            $item->update($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product has been created successfully.');
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
            return redirect()->route('products.index')->with('success', 'Product has been deleted successfully.');
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
    public function getByCode()
    {
        if (request()->ajax()) {
            $item = Product::with(['part_number', 'unit', 'type', 'supplier', 'department', 'area', 'rack'])->where('code', request('code'))->first();
            if ($item) {
                return response()->json([
                    'status' => true,
                    'data' => $item
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => []
                ]);
            }
        }
    }

    public function getAllByTypePart()
    {
        if (request()->ajax()) {
            $items = Product::with(['part_number', 'unit']);
            $type_id  = request('type_id');
            $part_number_id  = request('part_number_id');

            if ($type_id) {
                $items->where('type_id', request('type_id'));
            }
            if ($part_number_id) {
                $items->where('part_number_id', request('part_number_id'));
            }

            $data = $items->orderBy('name', 'ASC')->get();
            return response()->json($data);
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
        $generates = Generate::with(['product'])->orderBy('created_at', 'DESC')->get();
        return view('pages.product.report', [
            'title' => 'Product Report',
            'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
            'items' => [],
            'part_number_id' => null,
            'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
            'types' => Type::orderBy('name', 'ASC')->get(),
            'products' => Product::orderBy('name', 'ASC')->get(),
            'type_id' => null,
            'product_id' => null,
            'start_date' => null,
            'end_date' => null,
            'generates' => $generates
        ]);
    }

    public function report_action()
    {
        $start_date = request('start_date');
        $end_date = request('end_date');
        $generate_id = request('generate_id');
        $product_id = request('product_id');
        $action = request('action');
        $items = Generate::with(['product', 'product.part_number', 'product.unit', 'product.department']);

        // if ($start_date && $end_date) {
        //     $items->whereDate('created_at', '>=', $start_date)
        //         ->whereDate('created_at', '<=', $end_date);
        // } elseif ($start_date && !$end_date) {
        //     $items->whereDate('created_at', $start_date);
        // }
        if ($generate_id) {
            $items->where('id', $generate_id);
            $generate = Generate::find($generate_id);
        }
        if ($product_id) {
            $items->where('product_id', $product_id);
            $product = Product::find($product_id);
        }

        $data = $items->latest()->get();

        if ($action === 'export_pdf') {
            $pdf = Pdf::loadView('pages.product.export-pdf', [
                'title' => 'Export PDF Product',
                'items' => $data,
                'generate_id' => $generate_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'generate' => isset($generate) ? $generate : null,
                'product' => isset($product) ? $product : null
            ]);
            $fileName = "Product-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.pdf';
            return $pdf->download($fileName);
        } elseif ($action === 'export_excel') {
            $arr = [
                'items' => $data,
                'generate_id' => $generate_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'generate' => isset($generate) ? $generate : null,
                'product' => isset($product) ? $product : null
            ];
            $fileName = "Product-Report-" . Carbon::now()->format('d-m-Y H:i:s') . '.xlsx';
            return Excel::download(new ProductExport($arr), $fileName);
        } else {
            return view('pages.product.report', [
                'title' => 'Product Report',
                'items' => $data,
                'types' => Type::orderBy('name', 'ASC')->get(),
                'products' => Product::orderBy('name', 'ASC')->get(),
                'generate_id' => $generate_id,
                'product_id' => $product_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        }
    }

    public function import()
    {

        Excel::import(new ProductImport, request()->file('file'));
        return redirect()->route('products.index')->with('success', 'Product has been imported successfully.');
    }
}
