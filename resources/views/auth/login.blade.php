@extends('auth.app')
@section('title')
    Login
@endsection
@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/images/logo-toho2.png') }}" class="img-fluid"
                                    style="max-height:40px" alt="">
                            </div>
                            <h5 class="text-center mb-4">WAREHOUSE MANAGEMENT SYSTEM</h5>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" method="post" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email"
                                        class="form-control @error('email')is-invalid @enderror form-control-lg"
                                        placeholder="Email" name="email" id="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password"
                                        class="form-control  @error('password')is-invalid @enderror form-control-lg"
                                        id="password" placeholder="Password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" name="rememberme" class="form-check-input">
                                            Keep me signed in
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                        IN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
@endsection
