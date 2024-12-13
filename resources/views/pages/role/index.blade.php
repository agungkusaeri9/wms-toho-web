@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Role</h4>
                    @can('Role Create')
                        <a href="{{ route('roles.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Role</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    @canany(['Role Edit', 'Role Delete'])
                                        <th>Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        @canany(['Role Edit', 'Role Delete'])
                                            <td>
                                                @if ($item->name !== 'superadmin')
                                                    @can('Role Edit')
                                                        @if ($item->guard_name === 'web')
                                                            <a href="{{ route('roles.edit', $item->id) }}"
                                                                class="btn btn-sm py-2 btn-info">Edit</a>
                                                        @else
                                                            <a disabled title="This role cannot be edited"
                                                                class="btn btn-sm py-2 text-white btn-info">Edit</a>
                                                        @endif
                                                    @endcan
                                                    @can('Role Delete')
                                                        <form action="javascript:void(0)" method="post" class="d-inline"
                                                            id="formDelete">
                                                            @csrf
                                                            @method('delete')

                                                            @if ($item->guard_name === 'web')
                                                                <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                                    data-action="{{ route('roles.destroy', $item->id) }}">Delete</button>
                                                            @else
                                                                <button title="This role cannot be deleted"
                                                                    class="btn btn-sm py-2 btn-danger" disabled>Delete</button>
                                                            @endif
                                                        </form>
                                                    @endcan
                                                @else
                                                    <i>No Access!</i>
                                                @endif
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
