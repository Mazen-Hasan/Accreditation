@extends('layouts.app')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic">
                    <img src="{{ asset('images/logo-login.png') }}" alt="Accreditation">
                </div>
                <form method="POST" class="login100-form validate-form" action="{{ route('login') }}">
                    @csrf
                    <span class="login100-form-title">
                            Login
                    </span>
                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                            <span class="heading2">
                                Email
                            </span>
                        <input id="email" type="email" class="input100 @error('email') is-invalid @enderror"
                               name="email" placeholder="Enter email" value="{{ old('email') }}" required
                               autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                           <span class="heading2">
							Password
                           </span>
                        <input id="password" type="password" class="input100 @error('password') is-invalid @enderror"
                               placeholder="Enter password" name="password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            sigin in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

