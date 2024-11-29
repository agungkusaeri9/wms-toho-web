@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Product</h4>
                    @can('Product Create')
                        <a href="{{ route('products.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Product</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable table-hover nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Image</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Lot No.</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    @canany(['Product Edit', 'Product Delete', 'Rack Index'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $item->image() }}" class="img-fluid" style="max-height: 80px"
                                                alt="">
                                        </td>
                                        <td>{{ $item->part_number->name ?? '-' }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->lot_number ?? '-' }}</td>
                                        <td>{{ $item->unit->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        @canany(['Product Edit', 'Product Delete'])
                                            <td>
                                                @can('Product Show')
                                                    <a href="{{ route('products.show', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-warning">Detail</a>
                                                @endcan
                                                @can('Product Edit')
                                                    <a href="{{ route('products.edit', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Product Delete')
                                                    <form action="javascript:void(0)" method="post" class="d-inline"
                                                        id="formDelete">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                            data-action="{{ route('products.destroy', $item->id) }}">Delete</button>
                                                    </form>
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
@endsection
<x-Datatable />
