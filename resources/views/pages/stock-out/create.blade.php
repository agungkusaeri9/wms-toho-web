@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Create Stock Out</h4>
                    <form action="{{ route('stock-outs.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='received_date' class='mb-2'>Date</label>
                            <input type='date' name='received_date' id='received_date'
                                class='form-control @error('received_date') is-invalid @enderror'
                                value='{{ Carbon\Carbon::now()->format('Y-m-d') ?? old('received_date') }}' readonly>
                            @error('received_date')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='product_id'>Product</label>
                            <select name='product_id' id='product_id'
                                class='form-control @error('product_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Product</option>
                                @foreach ($products as $product)
                                    <option @selected($product->id == old('product_id')) value='{{ $product->id }}'>
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
                        <div class='form-group mb-3'>
                            <label for='product_code' class='mb-2'>Part No.</label>
                            <input type='text' name='product_code' id='product_code'
                                class='form-control @error('product_code') is-invalid @enderror'
                                value='{{ old('product_code') }}' readonly>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='product_lot_number' class='mb-2'>Lot No.</label>
                            <input type='text' name='product_lot_number' id='product_lot_number'
                                class='form-control @error('product_lot_number') is-invalid @enderror'
                                value='{{ old('product_lot_number') }}' readonly>
                        </div>

                        <div class='form-group mb-3'>
                            <label for='product_name' class='mb-2'>Part Name</label>
                            <input type='text' name='product_name' id='product_name'
                                class='form-control @error('product_name') is-invalid @enderror'
                                value='{{ old('product_name') }}' readonly>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='qty' class='mb-2'>Qty</label>
                            <input type='number' name='qty' id='qty'
                                class='form-control @error('qty') is-invalid @enderror' value='{{ old('qty') }}'>
                            @error('qty')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary float-right">Create Stock Out</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
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
        $('#product_id').select2({
            theme: 'bootstrap'
        });
        $('#department_id').select2({
            theme: 'bootstrap'
        });

        $('#product_id').on('change', function() {
            let id = $(this).val();

            $.ajax({
                url: '{{ route('products.getById') }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    id
                },
                success: function(data) {
                    $('#product_lot_number').val(data.lot_number);
                    $('#product_name').val(data.name);
                    $('#product_code').val(data.part_number.name);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })
    </script>
@endpush
