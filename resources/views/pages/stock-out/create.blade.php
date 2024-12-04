@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Scan Qr Code Stock Out</h4>
                    <form action="{{ route('stock-outs.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='generate_code' class='mb-2'>Scan</label>
                            <input type='text' name='generate_code' id='generate_code'
                                class='form-control @error('generate_code') is-invalid @enderror'
                                value='{{ old('generate_code') }}' placeholder="Scan Qr Code" autofocus="true">
                            @error('generate_code')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='stock' class='mb-2'>Stock</label>
                            <input type='number' name='stock' id='stock'
                                class='form-control @error('stock') is-invalid @enderror' readonly
                                value='{{ old('stock') }}'>
                            @error('stock')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
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
                        <div class="row">
                            <div class="col-md">
                                <table class="table table-sm table-borderless">
                                    <tr class="mb-5">
                                        <th style="width: 150px">Part No</th>
                                        <td width="10">:</td>
                                        <td style="width:400px" id="part_number">
                                            <span id="part_number"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Part Name</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="part_name"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Lot No.</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="lot_number"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Type</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="type"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Unit</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="unit"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Description</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="description"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Department</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="department"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Area</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="area"></span>
                                        </td>
                                    </tr>
                                    <tr class="mb-5">
                                        <th style="width: 150px">Rack</th>
                                        <td width="10">:</td>
                                        <td style="width:400px">
                                            <span id="rack"></span>
                                        </td>
                                    </tr>
                                </table>

                                </ul>
                            </div>
                        </div>

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
        const doneTypingInterval = 500;

        $('#generate_code').on('input', function() {
            clearTimeout(typingTimer);
            let code = $(this).val();
            $('#generate_code').attr('readonly', true);
            typingTimer = setTimeout(function() {
                if (code.length > 0) {
                    $.ajax({
                        url: '{{ route('qrcode-generator.product.getByCode') }}',
                        type: 'GET',
                        dataType: 'JSON',
                        data: {
                            code
                        },
                        success: function(response) {

                            if (response.status) {
                                let data = response.data.product;
                                $('#stock').val(response.data.qty);
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
                } else {
                    Swal.fire('Error', 'Qr Code Not Found.', 'error')
                }
            }, doneTypingInterval);
        })
    </script>
@endpush
