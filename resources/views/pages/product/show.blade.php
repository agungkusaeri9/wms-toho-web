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
                                Code
                            </div>
                            <div>{{ $item->code }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Name
                            </div>
                            <div>{{ $item->name }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Category
                            </div>
                            <div>{{ $item->category->name }}</div>
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
                                Initial Qty
                            </div>
                            <div>{{ $item->initial_qty }}</div>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <div class="font-weight-bold">
                                Qty
                            </div>
                            <div>{{ $item->qty }}</div>
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
                                        <th>Code</th>
                                        <th>Product Name</th>
                                        <th>Supplier Name</th>
                                        <th>Qty</th>
                                        <th>Received Date</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_ins as $stockDetail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stockDetail->stock_in->code }}</td>
                                            <td>{{ $stockDetail->product->name }}</td>
                                            <td>{{ $stockDetail->stock_in->supplier->name }}</td>
                                            <td>{{ $stockDetail->qty . ' ' . $stockDetail->product->unit->name }}</td>
                                            <td>{{ formatDate($stockDetail->stock_in->received_date) }}</td>
                                            <td>{{ $stockDetail->stock_in->user->name }}</td>

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
                                        <th>Code</th>
                                        <th>Product Name</th>
                                        <th>Department</th>
                                        <th>Qty</th>
                                        <th>Date</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_outs as $stockOutDetail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stockOutDetail->stock_out->code }}</td>
                                            <td>{{ $stockOutDetail->product->name }}</td>
                                            <td>{{ $stockOutDetail->stock_out->department->name }}</td>
                                            <td>{{ $stockOutDetail->qty . ' ' . $stockOutDetail->product->unit->name }}
                                            </td>
                                            <td>{{ formatDate($stockOutDetail->stock_out->date) }}</td>
                                            <td>{{ $stockOutDetail->stock_out->user->name }}</td>

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
