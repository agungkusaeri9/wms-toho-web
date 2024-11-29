@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Rack</h4>
                    @can('Rack Create')
                        <a href="{{ route('racks.create', [
                            'area_id' => request('area_id'),
                        ]) }}"
                            class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Rack</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Rack Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    @canany(['Rack Edit', 'Rack Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{!! $item->status() !!}</td>

                                        @canany(['Rack Edit', 'Rack Delete'])
                                            <td>
                                                @can('Rack Edit')
                                                    <a href="{{ route('racks.edit', $item->id) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Rack Delete')
                                                    <form action="javascript:void(0)" method="post" class="d-inline"
                                                        id="formDelete">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                            data-action="{{ route('racks.destroy', $item->id) }}">Delete</button>
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
