@extends('layouts.app')

@section('title') Log In @endsection

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#" class="text-decoration-none"><b>E-</b>Commerce</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Username -->
                <div class="input-group mb-3">
                    <input id="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email atau Nomor HP">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- End of Username -->
                <!-- Password -->
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" autocomplete="current-password" placeholder="Password"
                        onkeypress="return noSpaces()">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <a class="mr-2" id="seePassword" role="button">
                                <img src="{{ asset('asset/img/hide.png') }}" width="20" height="20"
                                    id="seePasswordIcon">
                            </a>
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- End of Password -->
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember Me</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            @if (Route::has('password.request'))
            <p class="mb-1">
                <a href="{{ route('password.request') }}" class="text-decoration-none">
                    {{ __('Forgot Your Password?') }}
                </a>
            </p>
            @endif
            <p class="mb-0">
                <a href="{{ route('register') }}" class="text-decoration-none">Register</a>
            </p>
            <div class="card mt-3" style="height: 50px">
                <div class="card-body d-flex align-items-center">
                    <div class="icon ml-5" style="width: 80px">
                        <img src="{{ asset('asset/img/google.png') }}" class="mx-auto" alt="" style="max-width: 35%; max-height: 10%">
                    </div>
                    <a href="" class="text-decoration-none text-center font-weight-bold text-dark">{{ __('Google') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function noSpaces() {
            if (event.keyCode == 32) {
                event.returnValue = false;
                return false;
            }
        }

        $('#seePassword').on('click', () => {
            if ($('#password').attr('type') == 'password') {
                $('#password').attr('type', 'text');
                $('#seePasswordIcon').prop('src', '{{ asset('asset/img/show.png') }}');
            } else {
                $('#password').attr('type', 'password');
                $('#seePasswordIcon').prop('src', '{{ asset('asset/img/hide.png') }}');
            }
        })
</script>
@endpush