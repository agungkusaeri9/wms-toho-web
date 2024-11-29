@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Stock Out</h4>
                    @can('Stock In Create')
                        <a href="{{ route('stock-outs.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Stock Out</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Part No.</th>
                                    <th>Part. Name</th>
                                    <th>Lot No.</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product->part_number->name ?? '-' }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->lot_number }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->product->unit->name }}</td>
                                        <td>{{ formatDate($item->date) }}</td>
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
