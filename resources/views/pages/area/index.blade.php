@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Area</h4>
                    @can('Area Create')
                        <a href="{{ route('areas.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Area</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable table-hover nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Area Name</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    @canany(['Area Edit', 'Area Delete', 'Rack Index'])
                                        <th>Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ $item->description }}</td>
                                        @canany(['Area Edit', 'Area Delete', 'Rack Index'])
                                            <td>
                                                @can('Rack Index')
                                                    <a href="{{ route('racks.index', [
                                                        'area_id' => $item->id,
                                                    ]) }}"
                                                        class="btn btn-sm py-2 btn-warning">Rack</a>
                                                @endcan
                                                @can('Area Edit')
                                                    <a href="{{ route('areas.edit', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Area Delete')
                                                    <form action="javascript:void(0)" method="post" class="d-inline"
                                                        id="formDelete">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                            data-action="{{ route('areas.destroy', $item->id) }}">Delete</button>
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
