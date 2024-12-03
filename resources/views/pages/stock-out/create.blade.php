@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Scan Qr Code Stock Out</h4>
                    <form action="{{ route('stock-outs.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='product_code' class='mb-2'>Scan</label>
                            <input type='text' name='product_code' id='product_code'
                                class='form-control @error('product_code') is-invalid @enderror'
                                value='{{ old('product_code') }}' placeholder="Scan Qr Code" autofocus="true">
                            @error('product_code')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3 d-none'>
                            <label for='qty' class='mb-2'>Qty</label>
                            <input type='number' name='qty' id='qty'
                                class='form-control @error('qty') is-invalid @enderror' value='{{ 1 ?? old('qty') }}'>
                            @error('qty')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Part No.
                                </div>
                                <span id="part_number">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Part Name
                                </div>
                                <span id="part_name">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Lot No.
                                </div>
                                <span id="lot_number">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Type
                                </div>
                                <span id="type">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Unit
                                </div>
                                <span id="unit">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Description
                                </div>
                                <span id="description">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Department
                                </div>
                                <span id="department">-</span>
                            </li>
                            {{-- <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Qty
                                </div>
                                <span id="qty">-</span>
                            </li> --}}
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Area
                                </div>
                                <span id="area">-</span>
                            </li>
                            <li class="d-flex justify-content-between mb-3">
                                <div class="font-weight-normal">
                                    Rack
                                </div>
                                <span id="rack">-</span>
                            </li>
                        </ul>

                        <div class="form-group">
                            <button class="btn btn-primary float-right">Submit</button>
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
        let typingTimer;
        const doneTypingInterval = 200;

        $('#product_code').on('input', function() {
            clearTimeout(typingTimer);
            let data = $(this).val();
            let arr = data.split('-');
            let code = arr[0];
            let qty = arr[arr.length - 1];
            $('#qty').val(qty);
            typingTimer = setTimeout(function() {
                if (code.length > 0) {
                    $.ajax({
                        url: '{{ route('products.getByCode') }}',
                        type: 'GET',
                        dataType: 'JSON',
                        data: {
                            code
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status) {
                                let data = response.data;
                                $('#part_number').html(data.part_number.name);
                                $('#part_name').html(data.name);
                                $('#lot_number').html(data.lot_number);
                                $('#type').html(data.type.name);
                                $('#unit').html(data.unit.name);
                                $('#description').html(data.description);
                                $('#department').html(data.department.name);
                                // $('#qty').html(data.qty);
                                $('#area').html(data.area.name);
                                $('#rack').html(data.rack.name);
                            } else {
                                $('#part_number').html('Not Found');
                                $('#part_name').html('Not Found');
                                $('#lot_number').html('Not Found');
                                $('#type').html('Not Found');
                                $('#unit').html('Not Found');
                                $('#description').html('Not Found');
                                $('#department').html('Not Found');
                                // $('#qty').html('Not Found');
                                $('#area').html('Not Found');
                                $('#rack').html('Not Found');
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                }
            }, doneTypingInterval);
        })
    </script>
@endpush
