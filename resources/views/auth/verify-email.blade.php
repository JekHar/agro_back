@extends('layouts.simple')

@section('content')
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Reminder Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ __('Verify Your Email Address') }}</h3>
                        </div>
                        <div class="block-content">
                            @if (session('status') == 'verification-link-sent')
                                <div class="alert alert-success" role="alert">
                                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                </div>
                            @endif
                                <div class="mb-3">
                                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                                </div>
                                <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                <form class=" mt-4" action="{{ route('verification.send') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn w-100 btn-alt-primary">
                                        <i class="fa fa-fw fa-envelope me-1 opacity-50"></i> {{ __('Resend Verification Email') }}
                                    </button>
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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>

                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
