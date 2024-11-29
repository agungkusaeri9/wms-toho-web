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
        $type = $this->data['type'];
        $lot_number = $this->data['lot_number'];
        $part_number = $this->data['part_number'];
        return view('pages.product.export-excel', [
            'items' => $items,
            'type' => $type,
            'lot_number' => $lot_number,
            'part_number' => $part_number,
        ]);
    }
}
