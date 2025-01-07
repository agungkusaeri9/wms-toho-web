<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            /* background-color: #f9f9f9; */
            font-size: 10px
        }

        h2 {
            margin-bottom: 20px;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .stock-table2 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .stock-table thead th {
            /* background-color: #626465; */
            /* color: white; */
            text-align: center;
            /* padding: 10px; */
            border: 1px solid #ccc;
        }

        .stock-table tbody td {
            /* padding: 8px; */
            text-align: center;
            border: 1px solid #ccc;
        }

        .stock-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .stock-table tbody tr:hover {
            background-color: #e0f7fa;
        }

        .stock-table th,
        .stock-table td {
            min-width: 20px;
        }


        .table-header tr td {
            text-align: left
        }
    </style>
</head>

<body onload="print()">

    <h3 style="margin-bottom:20px !important;font-size:15px !important">Stock Out Report</h3>

    <table width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="vertical-align: top; text-align: left;">
                <table class="table-header">
                    <tr>
                        <td style="margin:0;padding:0">Company Name</td>
                        <td>:</td>
                        <td>{{ env('REPORT_COMPANY_NAME') }}</td>
                    </tr>
                    <tr>
                        <td style="margin:0;padding:0" colspan="2">Part Name</td>
                        <td>:</td>
                        <td>{{ $product ? $product->name : '-' }}</td>
                    </tr>
                    <tr>
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
                    </tr>
                    <tr>
                        <td style="margin:0;padding:0">Date</td>
                        <td>:</td>
                        <td>{{ Carbon\Carbon::now()->translatedFormat('d-m-Y H:i:s') }}</td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <img src="{{ public_path(env('REPORT_IMAGE_PATH')) }}" style="max-height: 50px;" alt="">
            </td>
        </tr>
    </table>


    <table class="stock-table">
        <thead>
            <tr>
                <th style="width:5px !important">No</th>
                <th>Code</th>
                <th>Part No.</th>
                <th>Part Name</th>
                <th>Lot No.</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td style="width:5px !important">{{ $loop->iteration }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->product->part_number->name ?? '-' }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->generate->lot_number }}</td>
                    <td>{{ $item->product->unit->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ formatDate($item->date, 'd-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:5px 0;">Stock Out Not Found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
