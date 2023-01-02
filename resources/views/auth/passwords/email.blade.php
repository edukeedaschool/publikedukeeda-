@extends('layouts.front_login')

@section('content')

<div class="d-flex justify-content-center form_container">
    <form class="w-100" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="input-group mb-2">
            <label for="">Forgot Password</label>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input_pass" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="d-flex justify-content-center mt-3 login_container">
            <button type="submit" name="button" class="btn login_btn">Send Password</button>
        </div>
    </form>
</div>


<?php function z1(){ ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

@endsection
