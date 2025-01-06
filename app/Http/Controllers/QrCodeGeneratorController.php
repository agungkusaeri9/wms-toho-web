<?php

namespace App\Http\Controllers;

use App\Models\Generate;
use App\Models\PartNumber;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrCodeGeneratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Qr Generator Index')->only('index');
        $this->middleware('can:Qr Generator Create')->only(['create', 'store']);
        $this->middleware('can:Qr Generator Edit')->only(['edit', 'update']);
        $this->middleware('can:Qr Generator Print')->only('print');
    }
    public function index()
    {
        $type_id = request('type_id');
        $product_id = request('product_id');
        $data = Product::with(['part_number', 'unit', 'department']);

        if ($type_id) {
            $data->where('type_id', $type_id);
        }
        if ($product_id) {
            $data->where('id', $product_id);
        }
        $items = $data->orderBy('name', 'ASC')->get();
        $generates = Generate::with('product')->whereHas('product', function ($q) {
            $q->orderBy('name', 'ASC');
        })->get();
        return view('pages.generate.qrcode', [
            'title' => 'Generate Qr Code',
            'items' => $items,
            'generates' => $generates,
            'product_id' => request('product_id')
        ]);
    }

    public function store()
    {
        request()->validate([
            'product_id' => ['required', 'numeric'],
            'lot_number' => ['required'],
            'qty' => ['required', 'numeric']
        ]);

        // cek lot number
        $cekLot = Generate::where('lot_number', request('lot_number'))->first();
        if ($cekLot) {
            return redirect()->back()->with('error', 'Lot No. sudah terpakai.');
        }
        $product = Product::with(['unit', 'part_number'])->where('id', request('product_id'))->first();

        // cek product in generate
        // $cekGenerate = Generate::where('product_id', request('product_id'))->first();
        // if ($cekGenerate) {
        //     return redirect()->back()->with('error', 'Product sudah di generate.');
        // }

        DB::beginTransaction();
        try {
            // generate
            $generate =  Generate::create([
                'product_id' => $product->id,
                'qty' => request('qty'),
                'lot_number' => request('lot_number')
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Qr berhasil di generate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function edit($id)
    {
        $item = Generate::with('product')->where('id', $id)->first();
        return view('pages.generate.edit', [
            'title' => 'Edit Qr Code',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'qty' => ['required', 'numeric']
        ]);
        try {
            $item = Generate::findOrFail($id);
            $data = request()->only(['qty']);
            $item->update($data);
            return redirect()->route('qrcode-generator.product.index')->with('success', 'Qr has been updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function print()
    {
        $code  = request()->code;
        $amount = request()->amount;
        $item = Generate::with('product')->where('code', $code)->first();
        return view('pages.generate.print-qrcode', [
            'title' => 'Generate Qr Code',
            'item' => $item,
            'amount' => $amount
        ]);
    }

    public function getByCode()
    {
        if (request()->ajax()) {
            $item = Generate::with(['product.part_number', 'product.type', 'product.unit', 'product.department', 'product.rack', 'product.area'])->where('code', request('code'))->first();
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
}
