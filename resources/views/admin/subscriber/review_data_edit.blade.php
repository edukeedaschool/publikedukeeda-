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
                            <div class="col-md-3" ></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <form method="post" name="subscriberReviewDataFrm" id="subscriberReviewDataFrm">
                        
                        <div class="alert alert-success alert-dismissible elem-hidden" id="reviewDataSuccessMessage"></div>
                        <div class="alert alert-danger alert-dismissible elem-hidden"  id="reviewDataErrorMessage"></div>

                        <table class="table table-striped clearfix admin-table" cellspacing="0">
                            <thead>
                                <tr class="header-tr">
                                    <th>Review Level</th>
                                    <th>Designation Name</th>
                                    <th>Review Range</th>    
                                    @if(!empty($sub_rev_list))
                                        <th>Current Value</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody> 
                                @for($i=0;$i<count($review_levels);$i++)
                                    <tr>  
                                        <td>{{$review_levels[$i]['review_level']}}</td>
                                        <td>{{$review_levels[$i]['designation']}}</td>
                                        <td>
                                            <select name="review_range_{{$review_levels[$i]['id']}}" id="review_range_{{$review_levels[$i]['id']}}" class="form-control review_range_{{$review_levels[$i]['position']}}" @if($i+1 < count($review_levels)) onchange="getNextDropdownReviewRange(this.value,'{{$review_levels[$i]['position']}}');" @endif>
                                                <option value="">-- Review Range --</option>
                                                @if($i == 0)
                                                    @for($q=0;$q<count($review_ranges);$q++)
                                                        <option value="{{$review_ranges[$q]['id']}}">{{$review_ranges[$q]['review_range']}}</option>
                                                    @endfor
                                                @endif
                                            </select>
                                        </td>
                                        @if(!empty($sub_rev_list))
                                            <td>@if(isset($sub_rev_list[$review_levels[$i]['id']])) {{$sub_rev_list[$review_levels[$i]['id']]['review_range']}} @endif</td>
                                        @endif
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                    </form>
                </div>    
                                            
                <div class="form-row ">
                    <div class="separator-10">&nbsp;</div>
                    <div class="form-group col-md-12" style="text-align:center; ">
                        <button type="button" id="ReviewLevel_edit_cancel" name="ReviewLevel_edit_cancel" class="btn btn-secondary"  onclick="window.location.href='{{url('subscriber/review-data/view')}}'">Cancel</button>
                        <button type="button" id ="ReviewLevel_edit_submit" name="ReviewLevel_edit_submit" class="btn btn-dialog" onclick="submitEditSubscriberReviewData();">Submit</button>
                   </div>    
                </div>            
                                            
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
