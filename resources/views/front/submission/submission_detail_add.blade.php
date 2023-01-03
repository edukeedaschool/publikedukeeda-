@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="addsubmissionSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="addsubmissionErrorMessage"></div>
        
        <form class="form-horizontal" action="" method="post" name="saveSubmissionDetailForm" id="saveSubmissionDetailForm">
            <div class="form-group">
                <label class="control-label" for="name"><b>Purpose</b></label>
                <select class="form-control" id="purpose" name="purpose">
                    <option value="">Select Purpose</option>
                    @for($i=0;$i<count($submission_purpose_list);$i++)
                        <option value="{{$submission_purpose_list[$i]['id']}}">{{$submission_purpose_list[$i]['submission_purpose']}}</option>
                    @endfor        
                </select>
                <div class="invalid-feedback" id="error_validation_purpose"></div>
            </div>

            <div class="form-group">
                <label class="control-label mt-3" for="email"><b>Nature of submission</b></label>
                <select class="form-control" id="nature" name="nature">
                    <option value="">Select Nature</option>
                    <option value="open">Open</option>
                    <option value="not_open"> Not Open</option>
                </select>
                <div class="invalid-feedback" id="error_validation_nature"></div>
                <p class="alrtMsg">*Anyone can view your submission</p>
            </div>

            <div class="form-group">
                <label class="control-label mt-3" for="Subject"><b>Subject</b></label>
                <input id="subject" name="subject" type="text" placeholder="Subject" class="form-control" maxlength="250">
                <div class="invalid-feedback" id="error_validation_subject"></div>
            </div>

            <div class="form-group">
                <label class="control-label mt-3" for="message"><b>Summary</b></label>
                <textarea class="form-control" id="summary" name="summary" placeholder="Please enter your message here..." rows="5"></textarea>
                <div class="invalid-feedback" id="error_validation_summary"></div>
            </div>

            <div class="form-group">
                <label class="control-label mt-3" for="message"><b>Detail</b></label>
                <input id="detail_file" name="detail_file" type="file"  class="form-control">
                <div class="invalid-feedback" id="error_validation_detail_file"></div>
                <p class="alrtMsg">Upload image / Upload pdf / Related video</p>
            </div>

            <div class="form-group">
                <div class="text-right">
                    <button type="button" class="btn" name="submission_detail_btn" id="submission_detail_btn" onclick="submitAddSubmissionDetail();" >Next</button>
                </div>
            </div>
            <input type="hidden" name="subscriber_id" id="subscriber_id" value="{{$subscriber_data['id']}}" />
            <input type="hidden" name="submission_type_id" id="submission_type_id" value="{{$submission_type_id}}" />
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/submission.js') }}" ></script>
@endsection

