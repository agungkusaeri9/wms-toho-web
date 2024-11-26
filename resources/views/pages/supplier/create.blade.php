@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Create Supplier</h4>
                    <form action="{{ route('suppliers.store') }}" method="post">
                        @csrf
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
                        <div class='form-group mb-3'>
                            <label for='email' class='mb-2'>Email</label>
                            <input type='email' name='email' id='email'
                                class='form-control @error('email') is-invalid @enderror' value='{{ old('email') }}'>
                            @error('email')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='phone' class='mb-2'>Phone</label>
                            <input type='text' name='phone' id='phone'
                                class='form-control @error('phone') is-invalid @enderror' value='{{ old('phone') }}'>
                            @error('phone')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='address' class='mb-2'>Address</label>
                            <textarea name='address' id='address' cols='30' rows='3'
                                class='form-control @error('address') is-invalid @enderror'>{{ old('address') }}</textarea>
                            @error('address')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='contact_person' class='mb-2'>Contact Person</label>
                            <input type='text' name='contact_person' id='contact_person'
                                class='form-control @error('contact_person') is-invalid @enderror'
                                value='{{ old('contact_person') }}'>
                            @error('contact_person')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Create Supplier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
