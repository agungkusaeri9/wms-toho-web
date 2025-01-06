@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @can('Qr Generator Create')
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center mb-5">Qr Generator</h4>
                            <form action="{{ route('qrcode-generator.product.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class='form-group'>

                                            <select name='product_id' id='product_id' class='form-control '>
                                                <option value='' selected disabled>Select Part Name</option>
                                                @foreach ($items as $item)
                                                    <option value='{{ $item->id }}'>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class='form-group mb-3'>
                                            <input type='text' name='part_number' id='part_number' class='form-control'
                                                value='{{ old('part_number') }}' readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class='form-group mb-3'>
                                            <input type='text' name='unit' id='unit' class='form-control '
                                                value='{{ old('unit') }}' readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <input type='text' name='lot_number' id='lot_number' class='form-control '
                                                placeholder="Lot Number" value='{{ old('lot_number') }}'>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <input type='number' name='qty' id='qty' class='form-control'
                                                placeholder="Qty" value="">
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <button type="submit" class="btn btn-primary ">Generate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <div class="row justify-content-center">
        @can('Qr Generator Index')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Qr Generator List</h4>
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
                                        @canany(['Qr Generator Edit', 'Qr Generator Print'])
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($generates as $generate)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $generate->product->part_number->name ?? '-' }}</td>
                                            <td>{{ $generate->product->name }}</td>
                                            <td>{{ $generate->lot_number }}</td>
                                            <td>{{ $generate->product->unit->name }}</td>
                                            <td>{{ $generate->qty }}</td>
                                            @canany(['Qr Generator Edit', 'Qr Generator Print'])
                                                <td>
                                                    @can('Qr Generator Edit')
                                                        <a href="{{ route('qrcode-generator.product.edit', $generate->id) }}"
                                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                                    @endcan
                                                    @can('Qr Generator Print')
                                                        {{-- <a href="{{ route('qrcode-generator.product.print', $generate->code) }}"
                                                            target="_blank" class="btn btn-sm py-2 btn-secondary">Print</a> --}}
                                                        <a href="javascript:void(0)" data-code="{{ $generate->code }}"
                                                            class="btn btn-sm py-2 btn-secondary btnPrint">Print</a>
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
        @endcan
    </div>
    <x-Datatable />

    <div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Print</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="formSubmit" method="get" target="_blank">
                    <div class="modal-body">
                        <input type="hidden" name="code" id="code">
                        <div class='form-group mb-3'>
                            <label for='amount' class='mb-2'>Amount <span class='text-danger small'>*</span></label>
                            <input type='text' name='amount' id='amount'
                                class='form-control @error('amount') is-invalid @enderror'
                                value='{{ 1 ?? old('amount') }}'>
                            @error('amount')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Print</button>
                    </div>
                </form>
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

        $('.btnPrint').on('click', function() {
            let code = $(this).data('code');
            $('#modalPrint #code').val(code);
            $('#modalPrint').modal('show');
            $('#formSubmit').attr('action', '{{ route('qrcode-generator.product.print') }}');
            $('#modalPrint').modal('show');
        })
    </script>
@endpush
