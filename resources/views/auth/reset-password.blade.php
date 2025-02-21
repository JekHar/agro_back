@extends('layouts.simple')
@push('after_body')
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    @vite('resources/js/input-validators/reset-password-validator.js')
@endpush
@section('content')
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Reminder Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ __('Reset Password') }}</h3>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <form class="js-validation-reset mt-4" action="{{ route('password.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ request()->token }}">

                                    <div class="mb-4">
                                        <input type="text" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <input type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <input class="form-control form-control-lg form-control-alt" id="password-confirm" type="password" name="password_confirmation" placeholder="{{ __('Password Confirmation') }}" required>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                <i class="fa fa-fw fa-envelope me-1 opacity-50"></i> {{ __('Reset password') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Reminder Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Reminder Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                @include('partials.copyright')
            </div>
        </div>
    </div>
@endsection
