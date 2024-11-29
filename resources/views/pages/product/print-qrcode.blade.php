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
            font-size: 17px;
        }

        .kotak {
            /* border: 1px solid black; */
            width: 340px;
            /* height: 219px; */
            display: flex;
            padding: 20px;
            gap: 35px;
            justify-content: space-between;
            margin-top: 30px;
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .bagian1 {
            width: 70%;
        }

        .bagian2 {
            width: 30%;
            /* border: 1px solid black; */
        }

        svg {
            max-height: 88px;
            border: 0.001px solid black;
            width: 100%;
            /* padding: 2px; */
            padding: 5px 0;
            ;
        }

        .amount {
            text-align: center;
            /* margin-right: 5px; */
            width: 100%;
            padding: 0;
            margin: 0;
            border: 0.001px solid black;
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
    @foreach ($products as $key => $product)
        @if ($key == 0)
            @for ($i = 1; $i < $amount + 1; $i++)
                @if ($i == 1)
                    <div class="kotak" style="margin-top:10px;">
                        <div class="bagian1">
                            <div class="product_name">
                                {{ $product->name }}
                            </div>
                            <table>
                                <tr>
                                    <td style="width:100px">Material Code</td>
                                    <td>:</td>
                                    <td>{{ $product->part_number->name }}</td>
                                </tr>
                                <tr>
                                    <td>Qty</td>
                                    <td>:</td>
                                    <td>{{ $product->qty }}</td>
                                </tr>
                                <tr>
                                    <td>Unit</td>
                                    <td>:</td>
                                    <td>{{ $product->unit->name }}</td>
                                </tr>
                                <tr>
                                    <td>Lot No</td>
                                    <td>:</td>
                                    <td>{{ $product->lot_number }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="bagian2">
                            {{-- <img src="{{ asset('assets/images/contoh-qrcode.png') }}" alt="" class="gambar-qr"> --}}
                            <div class="gambar-qr">
                                {!! QrCode::size(80)->generate('https://google.com') !!}
                            </div>

                            <h1 class="amount">1/{{ $i }}</h1>
                        </div>
                    </div>
                @endif
            @endfor
        @else
            @for ($i = 1; $i < $amount + 1; $i++)
                <div class="kotak">
                    <div class="bagian1">
                        <div class="product_name">
                            {{ $product->name }}
                        </div>
                        <table>
                            <tr>
                                <td style="width:100px">Material Code</td>
                                <td>:</td>
                                <td>{{ $product->part_number->name }}</td>
                            </tr>
                            <tr>
                                <td>Qty</td>
                                <td>:</td>
                                <td>{{ $product->qty }}</td>
                            </tr>
                            <tr>
                                <td>Unit</td>
                                <td>:</td>
                                <td>{{ $product->unit->name }}</td>
                            </tr>
                            <tr>
                                <td>Lot No</td>
                                <td>:</td>
                                <td>{{ $product->lot_number }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="bagian2">
                        {{-- <img src="{{ asset('assets/images/contoh-qrcode.png') }}" alt="" class="gambar-qr"> --}}
                        <div class="gambar-qr">
                            {!! QrCode::size(80)->generate('https://google.com') !!}
                        </div>

                        <h1 class="amount">1/{{ $i }}</h1>
                    </div>
                </div>
            @endfor
        @endif
    @endforeach
</body>

</html>
