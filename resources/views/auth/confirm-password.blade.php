@extends('layouts.simple')
@section('content')
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ __('Password') }}</h3>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                <p>
                                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                                </p>
                                <form class="mt-4" action="{{ route('password.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <input type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Password') }}" required>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                {{ __('Confirm') }}
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
