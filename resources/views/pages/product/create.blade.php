@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Create Product</h4>
                    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='code' class='mb-2'>Code</label>
                            <input type='text' name='code' id='code'
                                class='form-control @error('code') is-invalid @enderror' value='{{ old('code') }}'>
                            @error('code')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='name' class='mb-2'>Name</label>
                            <input type='text' name='name' class='form-control @error('name') is-invalid @enderror'
                                value='{{ old('name') }}'>
                            @error('name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='category_id'>Category</label>
                            <select name='category_id' id='category_id'
                                class='form-control @error('category_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Category</option>
                                @foreach ($categories as $category)
                                    <option @selected($category->id == old('category_id')) value='{{ $category->id }}'>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='unit_id'>Unit</label>
                            <select name='unit_id' id='unit_id'
                                class='form-control @error('unit_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Unit</option>
                                @foreach ($units as $unit)
                                    <option @selected($unit->id == old('unit_id')) value='{{ $unit->id }}'>{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='description' class='mb-2'>Description</label>
                            <textarea name='description' id='description' cols='30' rows='3'
                                class='form-control @error('description') is-invalid @enderror'>{{ old('description') }}</textarea>
                            @error('description')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='department_id'>Department</label>
                            <select name='department_id' id='department_id'
                                class='form-control @error('department_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Department</option>
                                @foreach ($departments as $department)
                                    <option @selected($department->id == old('department_id')) value='{{ $department->id }}'>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='initial_qty' class='mb-2'>Initial Qty</label>
                            <input type='number' name='initial_qty' id='initial_qty'
                                class='form-control @error('initial_qty') is-invalid @enderror'
                                value='{{ old('initial_qty') }}'>
                            @error('initial_qty')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='image' class='mb-2'>Image</label>
                            <input type='file' name='image' id='image'
                                class='form-control @error('image') is-invalid @enderror' value='{{ old('image') }}'>
                            @error('image')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('products.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
