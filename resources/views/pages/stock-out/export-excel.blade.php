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
        <td>PT. Toho Technology Indonesia</td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Department</td>
        <td>:</td>
        <td>{{ $department ? $department->name : '-' }}</td>
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
        <th style="width:110px;font-weight:bold;text-align:center">Department</th>
        <th style="width:110px;font-weight:bold;text-align:center">Received Date</th>
        <th style="width:110px;font-weight:bold;text-align:center">Product Code</th>
        <th style="width:110px;font-weight:bold;text-align:center">Product Name</th>
        <th style="width:80px;font-weight:bold;text-align:center">Qty</th>
        <th style="width:110px;font-weight:bold;text-align:center">Area</th>
        <th style="width:110px;font-weight:bold;text-align:center">Rack</th>
    </tr>
    @forelse ($items as $item)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td>{{ $item->stock_out->department->name }}</td>
            <td>{{ formatDate($item->stock_out->received_date) }}</td>
            <td>{{ $item->product->code }}</td>
            <td>{{ $item->product->name }}</td>
            <td style="text-align: center">{{ $item->qty }}</td>
            <td>{{ $item->product->area->name ?? '-' }}</td>
            <td>{{ $item->product->rack->name ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" style="text-align:center;padding:5px 0;">Stock Out Not Found!</td>
        </tr>
    @endforelse
</table>
