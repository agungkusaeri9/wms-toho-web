<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class QrCodeGeneratorController extends Controller
{
    public function index()
    {
        $type_id = request('type_id');
        $part_number_id = request('part_number_id');
        $product_id = request('product_id');
        $action = request('action');
        $data = Product::with(['part_number', 'unit', 'department']);

        if ($type_id) {
            $data->where('type_id', $type_id);
        }
        if ($part_number_id) {
            $data->where('part_number_id', $part_number_id);
        }
        if ($product_id) {
            $data->where('id', $product_id);
        }
        $items = $data->orderBy('name', 'ASC')->get();

        return view('pages.product.qrcode', [
            'title' => 'Generate Qr Code',
            'items' => $items,
            'part_numbers' => PartNumber::orderBy('name', 'ASC')->get(),
            'types' => Type::orderBy('name', 'ASC')->get(),
            'products' => Product::orderBy('name', 'ASC')->get(),
            'type_id' => request('type_id'),
            'part_number_id' => request('part_number_id'),
            'product_id' => request('product_id')
        ]);
    }

    public function print()
    {
        $products_ids = request('product_ids');
        if (count($products_ids) < 1) {
            return redirect()->back('error', 'Pilih Product terlebih dahulu.');
        }
        $products = Product::with(['unit', 'part_number'])->whereIn('id', $products_ids)->get();
        $amount = request('amount') ?? 1;
        return view('pages.product.print-qrcode', [
            'title' => 'Generate Qr Code',
            'amount' => $amount,
            'products' => $products
        ]);
    }
}
