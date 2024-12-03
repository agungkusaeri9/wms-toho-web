<table>
    <tr>
        <th rowspan="2" colspan="8" style="text-align: center;font-weight:bold;vertical-align:middle">Stock Out
            Report
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
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Company Name</td>
        <td>:</td>
        <td>{{ env('REPORT_COMPANY_NAME') }}</td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Part/Lot</td>
        <td>:</td>
        <td>{{ $product ? $product->name . '/' . $product->lot_number : '-' }}</td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Date Range</td>
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
    </tr>
    <tr>
        <th style="width:30px;text-align:center;font-weight:bold">No</th>
        <th style="width:110px;font-weight:bold;text-align:center">Code</th>
        <th style="width:110px;font-weight:bold;text-align:center">Part No.</th>
        <th style="width:110px;font-weight:bold;text-align:center">Part Name</th>
        <th style="width:110px;font-weight:bold;text-align:center">Lot No.</th>
        <th style="width:110px;font-weight:bold;text-align:center">Unit</th>
        <th style="width:80px;font-weight:bold;text-align:center">Qty</th>
        <th style="width:110px;font-weight:bold;text-align:center">Date</th>
    </tr>
    @forelse ($items as $item)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td>'{{ $item->code }}</td>
            <td>{{ $item->product->part_number->name ?? '-' }}</td>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->product->lot_number }}</td>
            <td>{{ $item->product->unit->name ?? '-' }}</td>
            <td style="text-align: center">{{ $item->qty }}</td>
            <td>{{ formatDate($item->created_at, 'd-m-Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" style="text-align:center;padding:5px 0;">Stock Out Not Found!</td>
        </tr>
    @endforelse
</table>
