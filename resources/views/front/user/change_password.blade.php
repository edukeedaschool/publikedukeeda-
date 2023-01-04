@extends('layouts.front_login',['page_title'=>$title])
@section('content')
<br/>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
            
<div class="d-flex justify-content-center form_container">
    <form class="w-100" method="post" action="{{ url('user/change-password') }}">
        @csrf
        <div class="input-group mb-3">
            <label for="">Enter present password</label>
            <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
            </div>

            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror input_pass" name="current_password" required >

            @error('current_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
         <div class="input-group mb-3">
                <label for="">New password</label>
                <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                
                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror input_pass" name="new_password" required >

                @error('new_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="input-group mb-3">
                <label for="">Confirm password</label>
                <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                
                <input id="new_password_confirmation" type="password" class="form-control input_pass" name="new_password_confirmation" required >
                @error('new_password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>

        <div class="d-flex justify-content-center mt-3 login_container">
                <button type="submit" name="button" class="btn login_btn">Change Password</button>
        </div>
    </form>
</div>

 
@endsection
