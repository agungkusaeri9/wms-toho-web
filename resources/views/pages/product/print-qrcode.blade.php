<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generate Qr Code Product</title>
    @vite(['resources/js'])
    <style>
        body {
            font-size: 8px;
            font-family: "Arial Narrow", sans-serif;
            /* letter-spacing: 0.5px; */
            /* line-height: 1.2; */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .kotak {
            /* border: 1px solid black; */
            width: 80px;
            /* height: 28px; */
            padding: 5px;
            margin-top: -8px !important;
            page-break-inside: avoid;
            page-break-after: auto;
            margin-left: 137px;
            justify-content: start;
            margin-bottom: -10px;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .bagian1 {
            width: 100%;
        }

        svg {
            /* max-height: 88px; */
            /* border: 0.001px solid black; */
            /* width: 100%; */
            /* padding: 2px; */
            padding: 5px 0;

        }


        .kotak-amount {
            border: 0.001px solid black;
            margin-right: 20px;

        }

        /* .kotak-gambar {
            border: 0.001px solid black;
            margin-right: 20px;
        } */

        .product_name {
            border: 0.001px solid black;
            text-align: center;
            margin-bottom: 15px;
            text-transform: uppercase
        }
    </style>

</head>

<body onload="print()">
    @for ($i = 1; $i < $amount + 1; $i++)
        @if ($i == 1)
            <div class="kotak" style="margin-top:10px;">
                <div class="bagian1">
                    <div style="font-weight-bold">
                        {{ Carbon\Carbon::now()->translatedFormat('d-m-Y') . ' - ' . $product->code }}</div>
                    <div class="text-align:center !important;">
                        {!! QrCode::size(32)->generate($product->dataQr($qty)) !!}
                    </div>
                </div>
            </div>
        @endif
    @endfor
</body>

</html>
