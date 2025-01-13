@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Employee</h4>
                    <form action="{{ route('employees.update', $item->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3 row'>
                            <div class="col-md">
                                <label for='avatar' class='mb-2'>Avatar</label>
                                <br>
                                <img src="{{ $item->user->avatar() }}" class="img-fluid" alt="">
                            </div>
                            <div class="col-md-10 align-self-center">
                                <input type='file' name='avatar'
                                    class='form-control @error('avatar') is-invalid @enderror' value='{{ old('avatar') }}'>
                                @error('avatar')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='name' class='mb-2'>Name</label>
                            <input type='text' name='name' class='form-control @error('name') is-invalid @enderror'
                                value='{{ $item->name ?? old('name') }}'>
                            @error('name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nik' class='mb-2'>NIK</label>
                            <input type='text' name='nik' class='form-control @error('nik') is-invalid @enderror'
                                value='{{ $item->nik ?? old('nik') }}'>
                            @error('nik')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='gender' class='mb-2'>Gender</label>
                            <select name="gender" id="gender"
                                class="form-control @error('gender') is-invalid @enderror">
                                <option value="" selected disabled>Pilih Gender</option>
                                <option @selected($item->gender === 'L') value="L">Laki-laki</option>
                                <option @selected($item->gender === 'P') value="P">Perempuan</option>
                            </select>
                            @error('gender')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='email' class='mb-2'>Email</label>
                            <input type='text' name='email' class='form-control @error('email') is-invalid @enderror'
                                value='{{ $item->user->email ?? old('email') }}'>
                            @error('email')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='roles' class='mb-2'>Role</label>
                            <select name="roles" id="roles" class="form-control @error('roles') is-invalid @enderror">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ in_array($role->name, old('roles', $item->user->roles()->pluck('name')->toArray())) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='password' class='mb-2'>Password</label>
                            <input type='password' name='password'
                                class='form-control @error('password') is-invalid @enderror' value='{{ old('password') }}'>
                            @error('password')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='password_confirmation' class='mb-2'>Password Confirmation</label>
                            <input type='password' name='password_confirmation'
                                class='form-control @error('password_confirmation') is-invalid @enderror'
                                value='{{ old('password_confirmation') }}'>
                            @error('password_confirmation')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('employees.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script>
        $(function() {
            $('#roles').select2({
                theme: 'bootstrap',
                placeholder: 'Anda bisa memilih lebih dari 1 role'
            })
        })
    </script>
@endpush
