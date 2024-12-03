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
                            <label for='image' class='mb-2'>Image</label>
                            <input type='file' name='image' id='image'
                                class='form-control @error('image') is-invalid @enderror'>
                            @error('image')
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
                            <label for='part_number_id'>Part No.</label>
                            <select name='part_number_id' id='part_number_id'
                                class='form-control @error('part_number_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Part No.</option>
                                @foreach ($part_numbers as $part_number)
                                    <option @selected($part_number->id == old('part_number_id')) value='{{ $part_number->id }}'>
                                        {{ $part_number->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('part_number_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='type_id'>Type</label>
                            <select name='type_id' id='type_id'
                                class='form-control @error('type_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Type</option>
                                @foreach ($types as $type)
                                    <option @selected($type->id == old('type_id')) value='{{ $type->id }}'>{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_id')
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
                        <div class='form-group'>
                            <label for='supplier_id'>Supplier</label>
                            <select name='supplier_id' id='supplier_id'
                                class='form-control @error('supplier_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option @selected($supplier->id == old('supplier_id')) value='{{ $supplier->id }}'>
                                        {{ $supplier->code . ' | ' . $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='area_id'>Area</label>
                            <select name='area_id' id='area_id'
                                class='form-control @error('area_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Area</option>
                                @foreach ($areas as $area)
                                    <option @selected($area->id == old('area_id')) value='{{ $area->id }}'>{{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('area_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='rack_id'>Rack</label>
                            <select name='rack_id' id='rack_id'
                                class='form-control @error('rack_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Rack</option>
                            </select>
                            @error('rack_id')
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
@push('scripts')
    <script>
        $(function() {
            $('#area_id').on('change', function() {
                let area_id = $(this).val();

                $.ajax({
                    url: '{{ route('racks.getByAreaId') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        area_id
                    },
                    success: function(data) {
                        $('#rack_id').empty();
                        $('#rack_id').append('<option selected disabled>Pilih Rack</option>');

                        if (data.length > 0) {
                            data.forEach(rack => {
                                $('#rack_id').append(
                                    `<option value="${rack.id}">${rack.name}</option>`
                                )
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            })
        })
    </script>
@endpush
