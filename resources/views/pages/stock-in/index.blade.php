@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Stock In</h4>
                    @can('Stock In Create')
                        <a href="{{ route('stock-ins.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Stock In</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Code</th>
                                    <th>Supplier Name</th>
                                    <th>Received Date</th>
                                    <th>Notes</th>
                                    <th>Created By</th>
                                    @canany(['Stock In Edit', 'Stock In Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->supplier->name }}</td>
                                        <td>{{ formatDate($item->received_date) }}</td>
                                        <td>{{ $item->notes }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        @canany(['Stock In Edit', 'Stock In Delete'])
                                            <td>
                                                @can('Stock In Detail')
                                                    <a href="{{ route('stock-ins.show', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-warning">Detail</a>
                                                @endcan
                                                @can('Stock In Edit')
                                                    <a href="{{ route('stock-ins.edit', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
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
