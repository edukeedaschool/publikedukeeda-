@extends('layouts.front_main',['page_title'=>$title])

@section('content')
<div class="mainCenter">
    <div class="choose">
      <div class="address1">

        <ul class="">
            <li class="mb-5"><span>Submit to - </span><a href="javascript:;">{{$submission_data['subscriber_name']}} </a> &nbsp; ({{$submission_data['subscriber_bio']}})</li>
             <li><span>Submit Date -</span> {{date('d/m/Y',strtotime($submission_data['created_at']))}}</li>
            <li><span>Submit by -</span><a href="">{{$submission_data['submitted_by_name']}}</a> <div class="userImg2"><img src="{{$submission_data['submitted_by_profile_image_url']}}" class="img-fluid img-thumbnail"></div> </li>
            <li><span>Mail id -</span> {{$submission_data['submitted_by_email']}}</li>
            <li><span>Mobile number -</span> {{$submission_data['submitted_by_mobile_no']}}</li>
            <li><span>Age -</span> {{$submission_data['submitted_by_dob']}} years</li>
            <li><span>Qualification -</span> {{$submission_data['submitted_by_qualification']}}</li>
            <li><span>Profession -</span> {{$submission_data['submitted_by_profession']}}</li>
            <li><span>Address -</span>
                {{$submission_data['submitted_by_address_line1']}}, {{$submission_data['submitted_by_district_name']}} <br> {{$submission_data['submitted_by_district_name']}}
                {{$submission_data['submitted_by_state_name']}}, {{$submission_data['submitted_by_country_name']}}, {{$submission_data['submitted_by_postal_code']}}
            </li>
        </ul>
      </div>
      <div class="address1 brdNone">
        <ul class="">
            <li><span>Purpose - </span> {{$submission_data['submission_purpose']}}</li>
            <li><span>Type - </span> {{$submission_data['submission_type']}}</li>        
            <li><span>Nature of submission   -</span> {{$submission_data['nature']}} <br> <br> <i>*Anyone can view your submission</i></li>
            <li><span>Subject -</span> {{$submission_data['subject']}} </li>
            <li><span>Summary -</span>{{$submission_data['summary']}} </li>
            <li>
                <span>Detail -</span> 
                @if(stripos($submission_data['file'],'.pdf') === false)
                    <img src="{{$submission_data['file_url']}}" class="img-fluid img-thumbnail"> <br> <br> 
                @endif
                <p class="viewpdf"><a target="_blank" href="{{$submission_data['file_url']}}">View Image/PDF</a></p>
            </li>
            <li><span>Status -</span> Under review </li>
            <li><span>Reviewers Comments -</span>  </li>
        </ul>
      </div>
        
        @if(in_array($submission_data['submission_status'],['under_review','under_review_forwarded']) && $user->user_role == 5 )
            <div class="confirmBtn">
                <!--<a href="">Under Progress</a>-->
                <a href="javascript:;" onclick="$('#submission_action_dialog').modal('show');" class="active">Action</a>
            </div>
        @endif
        
        <div class="modal fade data-modal" id="submission_action_dialog" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Action</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('images/close.png')}}" alt="Close" title="Close" /></button>
                    </div>

                    <div class="alert alert-success alert-dismissible" style="display:none" id="submissionActionSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible" style="display:none" id="submissionActionErrorMessage"></div>

                    <form class="" name="submissionActionForm" id="submissionActionForm" type="POST" >
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <table><tr><td style='padding: 1px 5px;'><input id="submission_status" type="radio"  name="submission_status" value="under_review_forwarded" ></td><td style='padding: 1px 5px;'> Forward to Next Level</tr></table>
                                </div>
                            </div>    
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <table><tr><td style='padding: 1px 5px;'><input id="submission_status" type="radio"  name="submission_status" value="closed" ></td><td style='padding: 1px 5px;'> Close Submission</tr></table>
                                </div>
                            </div>  
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <table><tr><td style='padding: 1px 5px;'><input id="submission_status" type="radio"  name="submission_status" value="review_completed" ></td><td style='padding: 1px 5px;'> Review Completed</tr></table>
                                </div>
                            </div>  
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <label>Comments</label>
                                    <textarea id="action_comments" type="text" class="form-control" name="action_comments" value="" maxlength="1000"></textarea>
                                    <div class="invalid-feedback" id="error_validation_action_comments"></div>
                                </div>
                            </div>    
                        </div>
                        <div class="modal-footer center-footer">
                            <button type="button" id="submission_action_cancel" name="submission_action_cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" id ="submission_action_submit" name="submission_action_submit" class="btn btn-dialog" onclick="submitSubmissionAction();">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
       $("#submission_type_id").val('');
    });
</script>
<script src="{{ asset('js/submission.js') }}" ></script>
@endsection

