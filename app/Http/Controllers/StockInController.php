<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $items = StockIn::orderBy('code', 'ASC')->get();
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
        $validator = Validator::make(request()->all(), [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'received_date' => ['required', 'date'],
            'data_json' => ['required']
        ], [
            'supplier_id.required' => 'Supplier is required and must exist in the database.',
            'received_date.required' => 'The received date is required.',
            'received_date.date' => 'Please enter a valid date format for the received date.',
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
            $data = request()->only(['supplier_id', 'received_date', 'notes']);
            $data_json = request('data_json');
            $data_array = json_decode($data_json, true);
            $data['user_id'] = auth()->id();
            $data['code'] = StockIn::getNewCode();
            $stockIn  = StockIn::create($data);
            foreach ($data_array as $item) {
                $stockIn->details()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty']
                ]);

                // update stok
                $product = Product::find($item['product_id']);
                $product->increment('qty', $item['qty']);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Stock In berhasil dibuat'
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = StockIn::findOrFail($id);
            $item->delete();
            DB::commit();
            return redirect()->route('stock-ins.index')->with('success', 'Stock In berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
