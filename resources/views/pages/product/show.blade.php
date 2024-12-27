@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Detail Product</h4>
                    <ul class="list-unstyled">
                        <li class="text-center mb-3">
                            <img src="{{ $item->image() }}" class="img-fluid" style="max-height:80px" alt="">
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Part No.
                            </div>
                            <div>{{ $item->part_number->name ?? '-' }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Part Name
                            </div>
                            <div>{{ $item->name }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Lot No.
                            </div>
                            <div>{{ $item->lot_number }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Type
                            </div>
                            <div>{{ $item->type->name ?? '-' }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Unit
                            </div>
                            <div>{{ $item->unit->name }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Description
                            </div>
                            <div>{{ $item->description }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Department
                            </div>
                            <div>{{ $item->department->name }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Area
                            </div>
                            <div>{{ $item->area->name ?? '-' }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Rack
                            </div>
                            <div>{{ $item->rack->name ?? '-' }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Stock In History</h4>
                        <div class="table-responsive">
                            <table class="table dtTable nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>Lot No.</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Receiving Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_ins as $stock_in)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stock_in->product->part_number->name ?? '-' }}</td>
                                            <td>{{ $stock_in->product->name }}</td>
                                            <td>{{ $stock_in->product->lot_number }}</td>
                                            <td>{{ $stock_in->qty }}</td>
                                            <td>{{ $stock_in->product->unit->name }}</td>
                                            <td>{{ formatDate($stock_in->received_date) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Stock Out History</h4>
                        <div class="table-responsive">
                            <table class="table dtTable nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>Lot No</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_outs as $stock_out)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stock_out->product->part_number->name }}</td>
                                            <td>{{ $stock_out->product->name }}</td>
                                            <td>{{ $stock_out->product->lot_number }}</td>
                                            <td>{{ $stock_out->qty }}</td>
                                            <td>{{ $stock_out->product->unit->name }}</td>
                                            <td>{{ formatDate($stock_out->date) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
