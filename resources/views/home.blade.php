@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top:20px; ">
                <div class="card-header">{{ __('Home Page') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if(isset($user->id) && !empty($user->id) )
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> {{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else 
                        <a href="{{url('user/login')}}">Login</a> | <a href="{{url('user/signup')}}">Signup</a>
                    @endif

                    <?php /* ?>
                    @if($user->user_role == 2)
                        <a href="{{url('subscriber/review-data/view')}}">Review Range Data</a>
                    @endif <?php */ ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
