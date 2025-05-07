@extends('layouts.simple')
@push('after_body')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    @vite('resources/js/input-validators/login-validator.js')
@endpush
@section('content')
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ __('login') }}</h3>
                            <div class="block-options">
                                @if (Route::has('password.request'))
                                    <a class="btn-block-option fs-sm" href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                @endif
                                @if (Route::has('register'))
                                    <a class="btn-block-option" href="{{ route('register') }}" data-bs-toggle="tooltip"
                                        data-bs-placement="left" title="New Account">
                                        <i class="fa fa-user-plus"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="block-content">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                <p class="fw-medium text-muted">
                                    Bienvenido, por favor inicie sesión.
                                </p>

                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="py-3">
                                        <div class="mb-4">
                                            <input id="email" type="email"
                                                class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required
                                                placeholder="{{ __('Email') }}" autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-4 position-relative">
                                            <input id="password" type="password"
                                                class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror"
                                                name="password" required placeholder="{{ __('Password') }}"
                                                autocomplete="password">
                                            <span class="password-toggle-icon position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                                            </span>

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="remember">{{ __('Recordarme') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 col-xl-5">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary p-2 rounded-pill text-white"
                                                style="background-color: #FF6600; border: none; outline: none;">
                                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> {{ __('Log in') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                @include('partials.copyright')
            </div>
        </div>
    </div>
@endsection

@push('after_body')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        togglePassword.classList.remove('fa-eye-slash');
                        togglePassword.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        togglePassword.classList.remove('fa-eye');
                        togglePassword.classList.add('fa-eye-slash');
                    }
                });
            }
        });
    </script>
@endpush
