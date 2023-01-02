@extends('layouts.front_login')
@section('content')
<div class="d-flex justify-content-center form_container">
   
    <form class="w-100" method="POST" action="{{ url('user/login') }}">
        @csrf
        
        @if(session()->has('message'))
            <div class="input-group mb-3">
                <div class="alert alert-success">{{ session()->get('message') }}</div>
            </div>
        @endif
        
        <div class="input-group mb-3">
            <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input_user" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-2">
            <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input_pass" name="password" required autocomplete="current-password" placeholder="password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="customControlInline">Remember me</label>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3 login_container">
            <button type="submit" name="button" class="btn login_btn">Login</button>
        </div>
    </form>
</div>
		
<div class="mt-4">
    <div class="d-flex justify-content-center links">
        Don't have an account? <a href="{{url('user/signup')}}" class="ml-2">Sign Up</a>
    </div>
    <div class="d-flex justify-content-center links">
        <a href="{{ route('password.request')}}">Forgot your password?</a>
    </div>
</div>
@endsection
