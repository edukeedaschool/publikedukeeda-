@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >
           <div class="separator-10">&nbsp;</div>
           
            <form method="get">
                <div class="row" >
                    <div class="col-6"></div>
                    <div class="col-6">
                        <div class="row justify-content-end">
                            <div class="col-md-3" >
                                <a href="{{url('subscriber/review-data/edit')}}" class="btn btn-dialog">{{!empty($subscriber_reviews)?'Edit':'Add'}} Review Range</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>

            <div id="requestsContainer">
                @if(!empty($subscriber_reviews))
                    <div class="table-responsive">
                        <form method="post" name="subscriberReviewDataFrm" id="subscriberReviewDataFrm">
                            <table class="table table-striped clearfix admin-table" cellspacing="0">
                                <thead>
                                    <tr class="header-tr">
                                        <th>Review Level</th>
                                        <th>Designation Name</th>
                                        <th>Review Range</th>    
                                    </tr>
                                </thead>
                                <tbody> 
                                    @for($i=0;$i<count($subscriber_reviews);$i++)
                                        <tr>  
                                            <td>{{$subscriber_reviews[$i]['review_level']}}</td>
                                            <td>{{$subscriber_reviews[$i]['designation']}}</td>
                                            <td>{{$subscriber_reviews[$i]['review_range']}}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </form>
                    </div>    
                @endif                         
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
