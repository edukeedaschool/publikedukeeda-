@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="addsubmissionSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="addsubmissionErrorMessage"></div>
        
      
        <form class="form-horizontal" action="" method="post">
            <div id="add_submission_page_1">
                <h2>Want to connect someone with your submission?</h2>

                <div class="form-group">
                    <label class="control-label" for="name"><b>Choose one</b></label>
                    <select class="form-control" id="sub_group_id"  name="sub_group_id" onchange="getSubmissionSubscribersList(this.value,'{{$user->id}}','subscriber_id','addsubmissionErrorMessage','');">
                      <option value="">Subgroup 1</option>
                      @for($i=0;$i<count($sub_group_list);$i++)
                          <option value="{{$sub_group_list[$i]['id']}}">{{$sub_group_list[$i]['sub_group_name']}}</option>
                      @endfor    
                    </select>
                    <div class="invalid-feedback" id="error_validation_sub_group_id"></div>
                </div>

                <!-- Email input-->
                <!--<div class="form-group">
                  <label class="control-label mt-3" for="email"><b>Choose place</b></label>
                  <select class="form-control" id="country" required="true" name="country">
                    <option value="">From your district "district name"</option>
                    <option value="">Not from your district but From your state "State Name" </option>
                    <option value="">From other states of India </option>
                  </select>
                </div>-->


                <div class="form-group">
                    <label class="control-label mt-3" for="message"><b>Select name</b></label>
                    <select class="form-control" id="subscriber_id"  name="subscriber_id">
                        <option value="">Subscriber</option>
                    </select>
                    <div class="invalid-feedback" id="error_validation_subscriber_id"></div>
                </div>

                <!-- Form actions -->
                <div class="form-group">
                    <div class="text-right">
                        <button type="button" class="btn" onclick="submitAddSubmissionSubscriber();">Next</button>
                    </div>
                </div>
            </div>
            
            <div id="add_submission_page_2" style="display:none;">
                <h2>What you want to share with <span id="subscriber_name_span"></span>,  <span id="subscriber_bio_span"></span> ?</h2>
                <ul class="submissionBtn">
                    <li><a href="">Submission Type 1</a></li>
                    <li><a href="">Submission Type 1</a></li>
                    <li><a href="">Submission Type 1</a></li>
                </ul>
                
                <div class="form-group">
                    <div class="text-right">
                      <button type="button" class="btn" onclick="displayAddSubmissionPage(2,3,'addsubmissionErrorMessage');">Next</button>
                    </div>
                </div>
            </div>
            
            <div id="add_submission_page_3" style="display:none;">
                <div class="form-group">
                    <label class="control-label" for="name"><b>Purpose</b></label>
                    <select class="form-control" id="purpose" required="true" name="purpose">
                      <option value="YE">Submission Purpose 1</option>
                      <option value="ZM">Submission Purpose 1</option>
                    </select>
                </div>
          
                  <!-- Email input-->
                <div class="form-group">
                    <label class="control-label mt-3" for="email"><b>Nature of submission</b></label>
                    <select class="form-control" id="nature" required="true" name="nature">
                      <option value="">Open</option>
                      <option value=""> Not Open</option>
                    </select>
                    <p class="alrtMsg">*Anyone can view your submission</p>
                </div>
          
                <div class="form-group">
                    <label class="control-label mt-3" for="message"><b>Subject</b></label>
                    <input id="subject" name="subject" type="text" placeholder="Subject" class="form-control">
                </div>
                
                <div class="form-group">
                    <label class="control-label mt-3" for="message"><b>Summary</b></label>
                    <textarea class="form-control" id="summary" name="summary" placeholder="Please enter your message here..." rows="5"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="control-label mt-3" for="message"><b>Detail</b></label>
                    <input id="" name="" type="file" placeholder="Subject" class="form-control">
                    <p class="alrtMsg">Upload image / Upload pdf / Related video</p>
                </div>
          
                  <!-- Form actions -->
                <div class="form-group">
                    <div class="text-right">
                        <button type="button" class="btn" onclick="displayAddSubmissionPage(3,4,'addsubmissionErrorMessage');">Next</button>
                    </div>
                </div>
            </div>
            
            
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/submission.js?v=1.1') }}" ></script>
@endsection

