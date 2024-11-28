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
        <td></td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Company Name</td>
        <td>:</td>
        <td>PT. Toho Technology Indonesia</td>
    </tr>
    <tr>
        <td style="margin:0;padding:0" colspan="2">Category</td>
        <td>:</td>
        <td>{{ $category ? $category->name : '-' }}</td>
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
        <td></td>
    </tr>
    <tr>
        <th style="width:30px;text-align:center;font-weight:bold">No</th>
        <th style="width:110px;font-weight:bold;text-align:center">Code</th>
        <th style="width:180px;font-weight:bold;text-align:center">Name</th>
        <th style="width:110px;font-weight:bold;text-align:center">Category</th>
        <th style="width:80px;font-weight:bold;text-align:center">Unit</th>
        <th style="width:150px;font-weight:bold;text-align:center">Description</th>
        <th style="width:110px;font-weight:bold;text-align:center">Department</th>
        <th style="width:80px;font-weight:bold;text-align:center">Qty</th>
        <th style="width:110px;font-weight:bold;text-align:center">Area</th>
        <th style="width:110px;font-weight:bold;text-align:center">Rack</th>
    </tr>
    @forelse ($items as $item)
        <tr>
            <td style="width:30px !important;text-align:center">{{ $loop->iteration }}</td>
            <td style="text-align: center">{{ $item->code }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->name }}</td>
            <td style="text-align:center">{{ $item->unit->name }}</td>
            <td>{{ $item->description }}</td>
            <td style="text-align:center">{{ $item->department->name }}</td>
            <td style="text-align:center">{{ $item->qty }}</td>
            <td>{{ $item->area->name ?? '-' }}</td>
            <td>{{ $item->rack->name ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10" style="text-align:center;padding:5px 0;">Stock In Not Found!</td>
        </tr>
    @endforelse
</table>
