<table>
    <tr>
        <th rowspan="2" colspan="10" style="text-align: center;font-weight:bold;vertical-align:middle">Product Report
        </th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Company Name</td>
        <td>:</td>
        <td>{{ env('REPORT_COMPANY_NAME') }}</td>
    </tr>
    {{-- <tr>
        <td style="margin:0;padding:0">Date Range</td>
        <td>:</td>
        <td>
            @if ($start_date && $end_date)
                {{ formatDate($start_date) . ' to ' . formatDate($end_date) }}
            @elseif($start_date && !$end_date)
                {{ formatDate($start_date) }}
            @else
                -
            @endif
        </td>
    </tr> --}}
    <tr>
        <td style="margin:0;padding:0">Part Name</td>
        <td>:</td>
        <td>
            @if ($product)
                {{ $product->name }}
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <td style="margin:0;padding:0">Lot Number</td>
        <td>:</td>
        <td>
            @if ($generate)
                {{ $generate->lot_number }}
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Date</td>
        <td>:</td>
        <td>{{ Carbon\Carbon::now()->translatedFormat('d-m-Y H:i:s') }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th style="width:30px;text-align:center;font-weight:bold">No</th>
        <th style="width:110px;font-weight:bold;text-align:center">Part No.</th>
        <th style="width:180px;font-weight:bold;text-align:center">Part Name</th>
        <th style="width:80px;font-weight:bold;text-align:center">Unit</th>
        <th style="width:80px;font-weight:bold;text-align:center">Lot Number</th>
        <th style="width:150px;font-weight:bold;text-align:center">In</th>
        <th style="width:150px;font-weight:bold;text-align:center">Out</th>
        <th style="width:150px;font-weight:bold;text-align:center">Remains Qty</th>
        <th style="width:110px;font-weight:bold;text-align:center">Area</th>
        <th style="width:110px;font-weight:bold;text-align:center">Rack</th>
    </tr>
    @forelse ($items as $item)
        <tr>
            <td style="width:30px !important;text-align:center">{{ $loop->iteration }}</td>
            <td style="text-align: center">{{ $item->product->part_number->name ?? '-' }}</td>
            <td>{{ $item->product->name }}</td>
            <td style="text-align:center">{{ $item->product->unit->name }}</td>
            <td style="text-align:center">{{ $item->lot_number }}</td>
            <td>{{ $item->stock_in->sum('qty') }}</td>
            <td>{{ $item->stock_out->sum('qty') }}</td>
            <td>{{ $item->remains() }}</td>
            <td>{{ $item->product->area->name ?? '-' }}</td>
            <td>{{ $item->product->rack->name ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10" style="text-align:center;padding:5px 0;">Product Not Found!</td>
        </tr>
    @endforelse
</table>
