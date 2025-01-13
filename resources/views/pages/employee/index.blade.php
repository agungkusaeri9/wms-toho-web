@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Employee</h4>
                    @can('Employee Create')
                        <a href="{{ route('employees.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Create
                            Employee</a>
                    @endcan
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>NIK</th>
                                    <th>Gender</th>
                                    <th>Role</th>
                                    @canany(['Employee Edit', 'Employee Delete'])
                                        <th>Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->gender }}</td>
                                        <td>
                                            @forelse ($item->user->roles as $role)
                                                <span class="badge badge-warning">{{ $role->name }}</span>
                                            @empty
                                                <i>Tidak Punya Role!</i>
                                            @endforelse
                                        </td>
                                        @canany(['Employee Edit', 'Employee Delete'])
                                            <td>
                                                @if ($item->user_id != auth()->id())
                                                    @can('Employee Edit')
                                                        <a href="{{ route('employees.edit', $item->id) }}"
                                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                                    @endcan
                                                    @can('Employee Delete')
                                                        <form action="javascript:void(0)" method="post" class="d-inline"
                                                            id="formDelete">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                                data-action="{{ route('employees.destroy', $item->id) }}">Delete</button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    <div class="text-danger font-italic">
                                                        No Access!
                                                    </div>
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
