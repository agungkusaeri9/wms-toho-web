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
                            <div class="col-md-2">
                                <div class='form-group'>
                                    <label for='type_id'>Type</label>
                                    <select name='type_id' id='type_id'
                                        class='form-control py-2 @error('type_id') is-invalid @enderror'>
                                        <option value='' selected>Pilih Type</option>
                                        @foreach ($types as $type)
                                            <option @selected($type->id == $type_id ?? '') value='{{ $type->id }}'>
                                                {{ $type->code . ' | ' . $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class='form-group'>
                                    <label for='part_number_id'>Part No.</label>
                                    <select name='part_number_id' id='part_number_id'
                                        class='form-control @error('part_number_id') is-invalid @enderror'>
                                        <option value='' selected>Pilih Part No.</option>
                                        @foreach ($part_numbers as $part_number)
                                            <option @selected($part_number->id == $part_number_id ?? old('part_number_id')) value='{{ $part_number->id }}'>
                                                {{ $part_number->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('part_number_id')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class='form-group'>
                                    <label for='product_id'>Product</label>
                                    <select name='product_id' id='product_id'
                                        class='form-control @error('product_id') is-invalid @enderror'>
                                        <option value='' selected>Pilih Product</option>
                                    </select>
                                </div>
                            </div>
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
                                    <th>Lot No.</th>
                                    <th>Unit</th>
                                    <th>Initial Qty</th>
                                    <th>Stock In</th>
                                    <th>Stock Out</th>
                                    <th>Remains Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->part_number->name ?? '-' }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->lot_number ?? '-' }}</td>
                                        <td>{{ $item->unit->name }}</td>
                                        <td>{{ $item->initial_qty }}</td>
                                        <td>{{ $item->stock_in->sum('qty') }}</td>
                                        <td>{{ $item->stock_out->sum('qty') }}</td>
                                        <td>{{ $item->qty }}</td>
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
        $('#part_number_id').select2({
            theme: 'bootstrap'
        });
        $('#product_id').select2({
            theme: 'bootstrap'
        });
        $('#type_id').on('change', function() {
            let type_id = $(this).val();
            let part_number_id = $('#part_number_id').val();
            if (type_id) {
                $.ajax({
                    url: '{{ route('products.getAllByTypePart') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        type_id: type_id,
                        part_number_id: null
                    },
                    success: function(data) {
                        $('#product_id').empty();
                        $('#product_id').append('<option selected value="">Pilih Product</option>');

                        if (data.length > 0) {
                            data.forEach(product => {
                                $('#product_id').append(
                                    `<option value="${product.id}">${product.lot_number} ${product.name}</option>`
                                )
                            });
                        }
                        $('#part_number').val(data.part_number.name);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            } else {
                $.ajax({
                    url: '{{ route('products.getAllByTypePart') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        type_id: type_id,
                        part_number_id: part_number_id,
                    },
                    success: function(data) {
                        $('#product_id').empty();
                        $('#product_id').append('<option selected value="">Pilih Product</option>');

                        if (data.length > 0) {
                            data.forEach(product => {
                                $('#product_id').append(
                                    `<option value="${product.id}">${product.lot_number} | ${product.name}</option>`
                                )
                            });
                        }
                        $('#part_number').val(data.part_number.name);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }
        })


        $('#part_number_id').on('change', function() {
            let part_number_id = $(this).val();
            let type_id = $('#type_id').val();
            if (part_number_id) {
                $.ajax({
                    url: '{{ route('products.getAllByTypePart') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        type_id: type_id,
                        part_number_id: part_number_id,
                    },
                    success: function(data) {
                        $('#product_id').empty();
                        $('#product_id').append('<option selected value="">Pilih Product</option>');

                        if (data.length > 0) {
                            data.forEach(product => {
                                $('#product_id').append(
                                    `<option value="${product.id}">${product.lot_number} | ${product.name}</option>`
                                )
                            });
                        }
                        $('#part_number').val(data.part_number.name);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            } else {
                $.ajax({
                    url: '{{ route('products.getAllByTypePart') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        type_id: type_id,
                        part_number_id: null,
                    },
                    success: function(data) {
                        $('#product_id').empty();
                        $('#product_id').append('<option selected value="">Pilih Product</option>');

                        if (data.length > 0) {
                            data.forEach(product => {
                                $('#product_id').append(
                                    `<option value="${product.id}">${product.lot_number} | ${product.name}</option>`
                                )
                            });
                        }
                        $('#part_number').val(data.part_number.name);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }
        })

        let type_id2 = '{{ $type_id }}';
        let part_number_id2 = '{{ $part_number_id }}';
        let product_id2 = '{{ $product_id }}';
        $.ajax({
            url: '{{ route('products.getAllByTypePart') }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                type_id: type_id2,
                part_number_id: part_number_id2
            },
            success: function(data) {
                $('#product_id').empty();
                $('#product_id').append('<option selected value="">Pilih Product</option>');

                if (data.length > 0) {
                    data.forEach(product => {
                        if (product.id == product_id2) {
                            $('#product_id').append(
                                `<option selected value="${product.id}">${product.lot_number} ${product.name}</option>`
                            )
                        } else {
                            $('#product_id').append(
                                `<option value="${product.id}">${product.lot_number} ${product.name}</option>`
                            )
                        }
                    });
                }
                $('#part_number').val(data.part_number.name);
            },
            error: function(err) {
                console.log(err);
            }
        })
    </script>
@endpush
