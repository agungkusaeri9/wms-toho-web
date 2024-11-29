@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Product Report</h4>
                    <form action="{{ route('products.report.action') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md align-self-center">
                                <button name="action" value="export_pdf" class="btn btn-danger">Export PDF</button>
                                <button name="action" value="export_excel" class="btn btn-info">Export Excel</button>
                            </div>
                        </div>
                    </form>
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
        $('#category_id').select2({
            theme: 'bootstrap'
        });
    </script>
@endpush
