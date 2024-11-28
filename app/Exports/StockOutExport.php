<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StockOutExport implements FromView
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $items = $this->data['items'];
        $start_date = $this->data['start_date'];
        $department = $this->data['department'];
        $end_date = $this->data['end_date'];
        return view('pages.stock-out.export-excel', [
            'items' => $items,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'department' => $department
        ]);
    }
}
