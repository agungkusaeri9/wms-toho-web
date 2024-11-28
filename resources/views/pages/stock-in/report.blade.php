@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Stock In Report</h4>
                    <form action="{{ route('stock-ins.report.action') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class='form-group mb-3'>
                                    <label for='start_date' class='mb-2'>Start Date</label>
                                    <input type='date' name='start_date' id='start_date'
                                        class='form-control @error('start_date') is-invalid @enderror'
                                        value='{{ old('start_date') }}'>
                                    @error('start_date')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class='form-group mb-3'>
                                    <label for='end_date' class='mb-2'>End Date</label>
                                    <input type='date' name='end_date' id='end_date'
                                        class='form-control @error('end_date') is-invalid @enderror'
                                        value='{{ old('end_date') }}'>
                                    @error('end_date')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class='form-group'>
                                    <label for='supplier_id'>Supplier</label>
                                    <select name='supplier_id' id='supplier_id'
                                        class='form-control py-2 @error('supplier_id') is-invalid @enderror'>
                                        <option value='' selected disabled>Pilih Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option @selected($supplier->id == old('supplier_id')) value='{{ $supplier->id }}'>
                                                {{ $supplier->code . ' | ' . $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md align-self-center">
                                <button name="action" value="export_pdf" class="btn btn-danger">Export PDF</button>
                                <button name="action" value="export_excel" class="btn btn-info">Export Excel</button>
                            </div>
                        </div>
                    </form>
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
        $('#supplier_id').select2({
            theme: 'bootstrap'
        });
    </script>
@endpush
