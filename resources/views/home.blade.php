@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top:20px; ">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($user->user_role == 2)
                        <a href="{{url('subscriber/review-data/view')}}">Review Range Data</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
