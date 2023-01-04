@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="addsubmissionSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="addsubmissionErrorMessage"></div>
        
        <div class="address1">
            <ul class="">
                <li class="mb-5"><span>Submit to - </span><a href="javascript:;">{{$submission_data['subscriber_name']}} </a> &nbsp; ({{$submission_data['subscriber_bio']}})</li>
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
            </ul>
        </div>
        <div class="confirmBtn">
            <a href="javascript:;" class="active" onclick="submitAddSubmissionConfirm();">Confirm</a>
            <a href="">Cancel</a>
            <input type="hidden" name="submission_id" id="submission_id" value="{{$submission_data['id']}}">
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
       
    });
</script>
<script src="{{ asset('js/submission.js') }}" ></script>
@endsection

