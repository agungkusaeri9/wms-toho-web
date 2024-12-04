@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center mb-5">Generate Qr Qr Code</h4>
                    <form action="{{ route('qrcode-generator.product.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class='form-group'>
                                    <label for='product_id'>Part Name</label>
                                    <select name='product_id' id='product_id' class='form-control '>
                                        <option value='' selected disabled>Pilih Part Name</option>
                                        @foreach ($items as $item)
                                            <option value='{{ $item->id }}'>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <label for='part_number' class='mb-2'>Part Number</label>
                                            <input type='text' name='part_number' id='part_number' class='form-control'
                                                value='{{ old('part_number') }}' readonly>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <label for='unit' class='mb-2'>Unit</label>
                                            <input type='text' name='unit' id='unit' class='form-control '
                                                value='{{ old('unit') }}' readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class='form-group mb-3'>
                                    <label for='lot_number' class='mb-2'>Lot Number</label>
                                    <input type='text' name='lot_number' id='lot_number' class='form-control '
                                        value='{{ old('lot_number') }}'>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class='form-group mb-3'>
                                    <label for='qty'>Qty</label>
                                    <input type='number' name='qty' id='qty' class='form-control'
                                        placeholder="qty" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary ">Generate</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Product</h4>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Lot No.</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generates as $generate)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $generate->product->part_number->name ?? '-' }}</td>
                                        <td>{{ $generate->product->name }}</td>
                                        <td>{{ $generate->product->lot_number }}</td>
                                        <td>{{ $generate->product->unit->name }}</td>
                                        <td>{{ $generate->qty }}</td>
                                        @canany(['Generate Qr Edit'])
                                            <td>
                                                @can('Generate Qr Edit')
                                                    <a href="{{ route('qrcode-generator.product.edit', $generate->id) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Generate Qr Print')
                                                    <a href="{{ route('qrcode-generator.product.print', $generate->code) }}"
                                                        target="_blank" class="btn btn-sm py-2 btn-secondary">Print</a>
                                                @endcan
                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-Datatable />
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
        $('#type_id').select2({
            theme: 'bootstrap'
        });
        $('#part_number_id').select2({
            theme: 'bootstrap'
        });
        $('#product_id').select2({
            theme: 'bootstrap'
        });
        $('#product_id').on('change', function() {
            let id = $(this).val();
            $.ajax({
                url: '{{ route('products.getById') }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    id,
                },
                success: function(data) {
                    $('#part_number').val(data.part_number.name);
                    $('#unit').val(data.unit.name);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })
    </script>
@endpush
