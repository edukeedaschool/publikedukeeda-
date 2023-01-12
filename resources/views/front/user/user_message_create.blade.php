@extends('layouts.front_main', ['page_title'=>$title])

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="saveMessageSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="saveMessageErrorMessage"></div>
        
        <form class="form-horizontal" action="" method="post" name="saveMessageForm" id="saveMessageForm">
            <h2 class="mt-3 justify-content-between d-flex">Create Message</h2>
            <div class="form-group">
                <label class="control-label mt-3" for="Subject"><b>To</b></label><br/>
                <img src="{{$to_user_data['image_url']}}" class="img-thumbnail" style="max-width:75px; ">
                <input id="to" name="to" type="text" placeholder="Subject" class="form-control" value="{{$to_user_data['name']}} ({{'@'}}{{$to_user_data['user_name']}})">
                <div class="invalid-feedback" id="error_validation_to_user_id"></div>
                <input type="hidden" name="to_id" id="to_id" value="{{$to_user_data['id']}}" />
            </div>

            <div class="form-group">
                <label class="control-label mt-3" for="message"><b>Message</b></label>
                <textarea class="form-control" id="message" name="message" placeholder="Please enter your message here..." rows="5"></textarea>
                <div class="invalid-feedback" id="error_validation_message"></div>
            </div>

            <div class="form-group">
                <div class="text-right">
                    <button type="button" class="btn" name="submission_detail_btn" id="submission_detail_btn" onclick="saveUserMessage();" >Submit</button>
                </div>
            </div>
            
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
    });
</script>
<script src="{{ asset('js/users.js') }}" ></script>
@endsection

