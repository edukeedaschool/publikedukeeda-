@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top:20px; ">
                <div class="card-header">{{ __('Access Denied') }}</div>

                <div class="card-body">
                    <div class="alert alert-danger">Access Denied to the Page</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
