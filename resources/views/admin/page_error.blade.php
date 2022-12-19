@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >
           <div class="separator-10">&nbsp;</div>
           
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <div class="alert alert-danger"><p>Error in Page </p> <p>{{$message}}</p></div>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
