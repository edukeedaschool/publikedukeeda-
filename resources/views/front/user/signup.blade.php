@extends('layouts.front_login')
@section('content')
<div class="d-flex justify-content-center form_container">
    <form class="w-100" method="POST" action="{{ url('user/signup') }}">
        @csrf
        <div class="input-group mb-3">
            <label for="">Name</label>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror input_user"  placeholder="Name"  name="name" value="{{ old('name') }}" required autocomplete="name" >
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <label for="">Username</label>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror input_user"  placeholder="Username" name="user_name" value="{{ old('user_name') }}" required autocomplete="user_name" >
            @error('user_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <label for="">Email Address</label>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input_user" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <label for="">Password</label>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input_pass" name="password" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <label for="">Confirm Password</label>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
            </div>
            <input id="password-confirm" type="password" class="form-control input_pass" name="password_confirmation" required autocomplete="new-password">
        </div>
        <div class="d-flex justify-content-center mt-3 login_container">
            <button type="submit" name="button" class="btn login_btn">Create Account</button>
        </div>
    </form>
</div>

<div class="mt-2">
    <div class="d-flex justify-content-center links">
        Already have an account? <a href="{{url('user/login')}}" class="ml-2">Sign In</a>
    </div>
</div>


@endsection
