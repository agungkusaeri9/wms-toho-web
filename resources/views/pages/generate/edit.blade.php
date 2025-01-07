@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Generate</h4>
                    <form action="{{ route('qrcode-generator.product.update', $item->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='code' class='mb-2'>Code</label>
                            <input type='text' name='code' class='form-control @error('code') is-invalid @enderror'
                                value='{{ $item->code ?? old('code') }}' readonly>
                            @error('code')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='name' class='mb-2'>Part Name</label>
                            <input type='text' name='name' class='form-control @error('name') is-invalid @enderror'
                                value='{{ $item->product->name ?? old('name') }}' readonly>
                            @error('name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='name' class='mb-2'>Part No.</label>
                            <input type='text' name='name' class='form-control @error('name') is-invalid @enderror'
                                value='{{ $item->product->part_number->name ?? old('name') }}' readonly>
                            @error('name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='name' class='mb-2'>Lot No.</label>
                            <input type='text' name='name' class='form-control @error('name') is-invalid @enderror'
                                value='{{ $item->lot_number ?? old('name') }}' readonly>
                            @error('name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='qty' class='mb-2'>Qty</label>
                            <input type='text' name='qty' class='form-control @error('qty') is-invalid @enderror'
                                value='{{ $item->qty ?? old('qty') }}'>
                            @error('qty')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('qrcode-generator.product.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
