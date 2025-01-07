<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $items = $this->data['items'];
        $product = $this->data['product'];
        $generate = $this->data['generate'];
        return view('pages.product.export-excel', [
            'items' => $items,
            'product' => $product,
            'generate' => $generate,
        ]);
    }
}
