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
                    <button class="btn btn-info btn-sm my-2 mb-3 btn-sm py-2" data-toggle="modal"
                        data-target="#modalImport">Import Excel</button>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover nowrap">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Image</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    @canany(['Product Edit', 'Product Delete', 'Product Show'])
                                        <th>Action</th>
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
                                        <td>{{ $item->unit->name }}</td>
                                        @canany(['Product Edit', 'Product Delete', 'Product Show'])
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
    <!-- Modal -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong class="">Warning</strong>
                            <p class="mt-2">Please download the provided template <a
                                    href="{{ asset('format/import-product.xlsx') }}" target="_blank">Here</a>. After
                                filling in the required
                                data, upload it
                                here
                                in .xlsx format.</p>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='file' class='mb-2'>File Excel</label>
                            <input type='file' name='file' id='file'
                                class='form-control @error('file') is-invalid @enderror' value='{{ old('file') }}'>
                            @error('file')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
