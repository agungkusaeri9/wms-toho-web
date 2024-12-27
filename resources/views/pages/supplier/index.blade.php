@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Supplier</h4>
                    @can('Supplier Create')
                        <a href="{{ route('suppliers.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Supplier</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table nowrap dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Supplier Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Contact Person</th>
                                    @canany(['Supplier Edit', 'Supplier Delete'])
                                        <th>Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->contact_person }}</td>
                                        @canany(['Supplier Edit', 'Supplier Delete'])
                                            <td>
                                                @can('Supplier Edit')
                                                    <a href="{{ route('suppliers.edit', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Supplier Delete')
                                                    <form action="javascript:void(0)" method="post" class="d-inline"
                                                        id="formDelete">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                            data-action="{{ route('suppliers.destroy', $item->id) }}">Delete</button>
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
