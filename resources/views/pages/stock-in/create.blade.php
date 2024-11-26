@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Create Stock In</h4>
                            <form action="{{ route('stock-ins.store') }}" method="post">
                                @csrf
                                <input type="text" id="json-input" name="data_json" hidden>
                                <div class="row">
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <label for='code' class='mb-2'>Code</label>
                                            <input type='text' name='code'
                                                class='form-control @error('code') is-invalid @enderror'
                                                value='{{ $latest_code ?? old('code') }}' disabled>
                                            @error('code')
                                                <div class='invalid-feedback'>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-md">
                                        <div class='form-group'>
                                            <label for='supplier_id'>Supplier Name</label>
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
                                    </div>
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <label for='notes' class='mb-2'>Notes</label>
                                            <textarea name='notes' id='notes' cols='30' rows='3'
                                                class='form-control @error('notes') is-invalid @enderror'>{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class='invalid-feedback'>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-md">
                                        <div class='form-group mb-3'>
                                            <label for='received_date' class='mb-2'>Received Date</label>
                                            <input type='date' name='received_date' id='received_date'
                                                class='form-control @error('received_date') is-invalid @enderror'
                                                value='{{ Carbon\Carbon::now()->format('Y-m-d') ?? old('received_date') }}'>
                                            @error('received_date')
                                                <div class='invalid-feedback'>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Select Product</h4>
                            <form action="javascript:void(0)" id="product-form">
                                <div class='form-group'>
                                    <label for='product_id'>Product</label>
                                    <select name='product_id' id='product_id'
                                        class='form-control @error('product_id') is-invalid @enderror'>
                                        <option value='' selected>Pilih Product</option>
                                        @foreach ($products as $product)
                                            <option @selected($product->id == old('product_id')) value='{{ $product->id }}'>
                                                {{ $product->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='form-group mb-3'>
                                    <label for='product_code' class='mb-2'>Product Code</label>
                                    <input type='text' name='product_code' id='product_code'
                                        class='form-control @error('product_code') is-invalid @enderror'
                                        value='{{ old('product_code') }}' disabled>
                                </div>
                                <div class='form-group mb-3'>
                                    <label for='product_name' class='mb-2'>Product Name</label>
                                    <input type='text' name='product_name' id='product_name'
                                        class='form-control @error('product_name') is-invalid @enderror'
                                        value='{{ old('product_name') }}' disabled>
                                </div>
                                <div class='form-group mb-3'>
                                    <label for='product_category' class='mb-2'>Product Category</label>
                                    <input type='text' name='product_category' id='product_category'
                                        class='form-control @error('product_category') is-invalid @enderror'
                                        value='{{ old('product_category') }}' disabled>
                                </div>
                                <div class='form-group mb-3'>
                                    <label for='product_unit' class='mb-2'>Unit</label>
                                    <input type='text' name='product_unit' id='product_unit'
                                        class='form-control @error('product_unit') is-invalid @enderror'
                                        value='{{ old('product_unit') }}' disabled>
                                </div>
                                <div class='form-group mb-3'>
                                    <label for='qty' class='mb-2'>Qty</label>
                                    <input type='number' name='qty' id='qty'
                                        class='form-control @error('qty') is-invalid @enderror'
                                        value='{{ old('qty') }}'>
                                </div>
                                <div class="form-group float-right">
                                    <button type="button" class="btn btn-success btn-sm btnSave">Save</button>
                                    <button type="button" class="btn btn-primary btn-sm btnAdd">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">List</h4>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="product-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Product Category</th>
                                            <th>Product Unit</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <style>
        /* Menyesuaikan tinggi input file Select2 agar sama dengan input Bootstrap */
        .select2-container .select2-selection--single {
            height: calc(1.5em + .75rem + 15px);
            /* Sesuaikan dengan height input Bootstrap */
            line-height: 1.5;
            padding: .375rem .75rem;
        }

        #notes {
            height: calc(1.5em + .75rem + 15px);
            /* Sesuaikan dengan height input Bootstrap */
            line-height: 1.5;
            padding: .375rem .75rem;
        }


        /* Menyesuaikan lebar Select2 dengan lebar input file */
        .select2-container {
            width: 100% !important;
        }

        /* Menambahkan padding dalam input file */
        select[type="file"] {
            height: calc(1.5em + .75rem + 2px);
            /* Sesuaikan dengan height input Bootstrap */
            padding: .375rem .75rem;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

    <script>
        $('#product_id').select2({
            theme: 'bootstrap'
        });
        $('#supplier_id').select2({
            theme: 'bootstrap'
        });

        $('#product_id').on('change', function() {
            let id = $(this).val();

            $.ajax({
                url: '{{ route('products.getById') }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    id
                },
                success: function(data) {
                    $('#product_code').val(data.code);
                    $('#product_name').val(data.name);
                    $('#product_category').val(data.category.name);
                    $('#product_unit').val(data.unit.name);

                },
                error: function(err) {
                    console.log(err);
                }
            })
        })

        function updateTable() {
            // Ambil data dari localStorage (jika ada)
            let products = JSON.parse(localStorage.getItem('products')) || [];

            // Kosongkan tabel sebelumnya
            $('#product-table tbody').empty();
            if (products.length > 0) {
                // Loop untuk menampilkan data di tabel
                products.forEach(function(product, index) {
                    let row = `<tr>
                <td>${index + 1}</td>
                <td>${product.product_code}</td>
                <td>${product.product_name}</td>
                <td>${product.product_category}</td>
                <td>${product.product_unit}</td>
                <td>${product.qty}</td>
                <td><button class="btn btn-sm btn-danger btn-delete" data-index="${index}">Delete</button></td>
            </tr>`;
                    $('#product-table tbody').append(row);
                    $('#json-input').val(JSON.stringify(products));
                });
            } else {
                let row = `<tr>
                <td colspan="7" class="text-center">Product Not Found.</td>
            </tr>`;
                $('#product-table tbody').append(row);

            }
        }

        // Menambahkan produk ke localStorage ketika tombol Add diklik
        $('.btnAdd').on('click', function() {
            // Ambil nilai dari input form
            let product_id = $('#product_id').val();
            let product_code = $('#product_code').val();
            let product_name = $('#product_name').val();
            let product_category = $('#product_category').val();
            let product_unit = $('#product_unit').val();
            let qty = parseInt($('#qty').val());

            // Validasi input
            if (!product_id || !qty) {
                return;
            }

            // Ambil data yang sudah ada di localStorage
            let products = JSON.parse(localStorage.getItem('products')) || [];

            // Cek apakah produk sudah ada di dalam array
            let existingProductIndex = products.findIndex(product => product.product_id === product_id);

            console.log({
                existingProductIndex,
                qty
            })
            if (existingProductIndex > -1) {
                // Jika produk sudah ada, tambahkan qty-nya
                products[existingProductIndex].qty += qty;
            } else {
                // Jika produk belum ada, tambahkan produk baru
                products.push({
                    product_id,
                    product_code,
                    product_name,
                    product_category,
                    product_unit,
                    qty
                });
            }

            // Simpan array yang telah diperbarui ke localStorage
            localStorage.setItem('products', JSON.stringify(products));

            // Update tabel setelah menambahkan produk baru
            updateTable();

            // Clear input form setelah data ditambahkan
            $('#product-form')[0].reset();
            $('#product_id').val(null).trigger('change');
        })

        $(document).on('click', '.btn-delete', function() {
            let index = $(this).data('index');
            let products = JSON.parse(localStorage.getItem('products')) || [];
            products.splice(index, 1);
            localStorage.setItem('products', JSON.stringify(products));
            updateTable();
        });
        // update
        updateTable();

        $('.btnSave').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Once saved, you won't be able to edit this data. Do you want to proceed?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let data_json = $('#json-input').val();
                    let notes = $('#notes').val();
                    let received_date = $('#received_date').val();
                    let supplier_id = $('#supplier_id').val();
                    $.ajax({
                        url: '{{ route('stock-ins.store') }}',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            supplier_id,
                            notes,
                            received_date,
                            '_token': '{{ csrf_token() }}',
                            data_json
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire('Success', response.message, 'success');
                                localStorage.removeItem('products');
                                setTimeout(function() {
                                    let back = '{{ route('stock-ins.index') }}';
                                    window.location.href = back;
                                }, 1000);
                            } else if (response.status == false && response.code === 422) {
                                let errorMessages = '';

                                // Get the first field with an error
                                let firstErrorField = Object.keys(response.errors)[0];
                                let firstErrorMessage = response.errors[firstErrorField][0];

                                // Set the error message
                                errorMessages = firstErrorMessage;

                                // Show SweetAlert with the first validation error
                                Swal.fire('Error ', errorMessages, 'error');
                            }
                        },
                        error: function(err) {
                            Swal.fire('Error ', 'Something went wrong while saving the data !',
                                'error');

                        }
                    })
                }
            })
        })
    </script>
@endpush
