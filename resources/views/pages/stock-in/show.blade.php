@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Detail Stock In</h4>
                            <ul class="p-0">
                                <li class="d-flex justify-content-between mb-2">
                                    <div class="font-weight-bold">Code</div>
                                    <div>{{ $item->code }}</div>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <div class="font-weight-bold">Supplier Name</div>
                                    <div>{{ $item->supplier->name }}</div>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <div class="font-weight-bold">Received Date</div>
                                    <div>{{ formatDate($item->received_date) }}</div>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <div class="font-weight-bold">Created By</div>
                                    <div>{{ $item->user->name }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">List Product</h4>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="product-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Product Category</th>
                                            <th>Product Unit</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->details as $detail)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $detail->product->code }}</td>
                                                <td>{{ $detail->product->name }}</td>
                                                <td>{{ $detail->product->category->name }}</td>
                                                <td>{{ $detail->product->unit->name }}</td>
                                                <td>{{ $detail->qty }}</td>
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
    </div>
@endsection
