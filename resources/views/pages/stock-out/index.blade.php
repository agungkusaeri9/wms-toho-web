@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Stock Out</h4>
                    @can('Stock Out Create')
                        <a href="{{ route('stock-outs.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Stock Out</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Code</th>
                                    <th>Department Name</th>
                                    <th>Date</th>
                                    <th>Notes</th>
                                    <th>Created By</th>
                                    @canany(['Stock Out Edit', 'Stock Out Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->department->name }}</td>
                                        <td>{{ formatDate($item->date) }}</td>
                                        <td>{{ $item->notes }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        @canany(['Stock Out Edit', 'Stock Out Delete'])
                                            <td>
                                                @can('Stock Out Detail')
                                                    <a href="{{ route('stock-outs.show', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-warning">Detail</a>
                                                @endcan
                                                @can('Stock Out Edit')
                                                    <a href="{{ route('stock-outs.edit', $item->id) }}"
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
