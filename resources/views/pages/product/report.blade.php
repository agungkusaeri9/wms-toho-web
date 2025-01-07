@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Products Report</h4>
                    <form action="{{ route('products.report.action') }}" method="post">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-md">
                                <div class='form-group mb-3'>
                                    <label for='start_date' class='mb-2'>Start Date</label>
                                    <input type='date' name='start_date' id='start_date'
                                        class='form-control @error('start_date') is-invalid @enderror'
                                        value='{{ formatDate($start_date, 'Y-m-d') ?? old('start_date') }}'>
                                    @error('start_date')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class='form-group mb-3'>
                                    <label for='end_date' class='mb-2'>End Date</label>
                                    <input type='date' name='end_date' id='end_date'
                                        class='form-control @error('end_date') is-invalid @enderror'
                                        value='{{ formatDate($end_date, 'Y-m-d') ?? old('end_date') }}'>
                                    @error('end_date')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-md">
                                <div class='form-group'>
                                    <label for='product_id'>Part Name</label>
                                    <select name='product_id' id='product_id'
                                        class='form-control py-2 @error('product_id') is-invalid @enderror'>
                                        <option value='' selected>Pilih Part Name</option>
                                        @foreach ($products as $product)
                                            <option @selected($product->id == $product_id ?? '') value='{{ $product->id }}'>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class='form-group'>
                                    <label for='generate_id'>Lot Number.</label>
                                    <select name='generate_id' id='generate_id'
                                        class='form-control @error('generate_id') is-invalid @enderror'>
                                        <option value='' selected>Pilih Lot Number</option>

                                    </select>
                                    @error('generate_id')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md align-self-center">
                                <button name="action" value="filter" class="btn btn-secondary">Filter</button>
                                <button name="action" value="export_pdf" class="btn btn-danger">Export PDF</button>
                                <button name="action" value="export_excel" class="btn btn-info">Export Excel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Product Report</h4>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    <th>Lot Number</th>
                                    <th>Stock In</th>
                                    <th>Stock Out</th>
                                    <th>Remains Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product->part_number->name ?? '-' }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->unit->name }}</td>
                                        <td>{{ $item->lot_number }}</td>
                                        <td>{{ $item->stock_in->sum('qty') }}</td>
                                        <td>{{ $item->stock_out->sum('qty') }}</td>
                                        <td>{{ $item->remains() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <style>
        /* Menyesuaikan tinggi input file Select2 agar sama dengan input Bootstrap */
        .select2-container .select2-selection--single {
            height: calc(1.5em + .75rem + 15px);
            /* Sesuaikan dengan height input Bootstrap */
            line-height: 1.5;
            padding: .375rem .75rem;
        }

        #notes {
            height: calc(1.5em + .75rem + 15px);
            /* Sesuaikan dengan height input Bootstrap */
            line-height: 1.5;
            padding: .375rem .75rem;
        }


        /* Menyesuaikan lebar Select2 dengan lebar input file */
        .select2-container {
            width: 100% !important;
        }

        /* Menambahkan padding dalam input file */
        select[type="file"] {
            height: calc(1.5em + .75rem + 2px);
            /* Sesuaikan dengan height input Bootstrap */
            padding: .375rem .75rem;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

    <script>
        $('#type_id').select2({
            theme: 'bootstrap'
        });
        $('#generate_id').select2({
            theme: 'bootstrap'
        });
        $('#product_id').select2({
            theme: 'bootstrap'
        });
        $('#product_id').on('change', function() {
            let product_id = $(this).val();
            if (product_id) {
                $.ajax({
                    url: '{{ route('qrcode-generator.getByProductId') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        product_id: product_id,
                    },
                    success: function(response) {
                        $('#generate_id').empty();
                        $('#generate_id').append('<option selected value="">Pilih Lot Number</option>');

                        if (response.data.length > 0) {
                            response.data.forEach(generate => {
                                $('#generate_id').append(
                                    `<option value="${generate.id}">${generate.lot_number}</option>`
                                )
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            } else {
                $.ajax({
                    url: '{{ route('qrcode-generator.getByProductId') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        product_id: null,
                    },
                    success: function(data) {
                        $('#generate_id').empty();
                        $('#generate_id').append('<option selected value="">Pilih Lot Number</option>');

                        if (data.length > 0) {
                            data.forEach(generate => {
                                $('#generate_id').append(
                                    `<option value="${generate.id}">${generate.lot_number}</option>`
                                )
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }
        })

        let generate_id2 = '{{ $generate_id ?? '' }}';
        let product_id2 = '{{ $product_id ?? '' }}';
        if (product_id2 || generate_id2) {
            $.ajax({
                url: '{{ route('qrcode-generator.getByProductId') }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    product_id: product_id2,
                },
                success: function(response) {
                    $('#generate_id').empty();
                    $('#generate_id').append('<option selected value="">Pilih Lot Number</option>');

                    if (response.data.length > 0) {
                        response.data.forEach(generate => {
                            if (generate.id == generate_id2) {
                                $('#generate_id').append(
                                    `<option selected value="${generate.id}">${generate.lot_number}</option>`
                                )
                            } else {
                                $('#generate_id').append(
                                    `<option value="${generate.id}">${generate.lot_number}</option>`
                                )
                            }
                        });
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    </script>
@endpush
