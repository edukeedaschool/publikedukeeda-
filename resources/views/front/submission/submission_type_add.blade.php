@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="addsubmissionSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="addsubmissionErrorMessage"></div>
        
        <form  action="" method="post">
            <h2>What you want to share with {{$subscriber_data['subscriber_name']}}, {{$subscriber_data['bio']}}  ?</h2>
            <div class="form-row">
                @for($i=0;$i<count($submission_type_list);$i++)
                    <div class="col-md-4"><a href="javascript:;" class="btn  btn-sub-type" onclick="selectSubmissionType('{{$submission_type_list[$i]['id']}}');" id="link_{{$submission_type_list[$i]['id']}}" >{{$submission_type_list[$i]['submission_type']}}</a></div>
                    @if($i > 0 && ($i+1)%3 == 0) </div> <div style="clear:both ">&nbsp;</div> <div class="form-row"> @endif 
                @endfor    
            </div>

            <div style="clear:both ">&nbsp;</div>

            <div class="form-group">
                <div class="text-right">
                  <button type="button" class="btn" onclick="submitAddSubmissionType();">Next</button>
                </div>
            </div>
            
            <input type="hidden" name="subscriber_id" id="subscriber_id" value="{{$subscriber_data['id']}}" />
            <input type="hidden" name="submission_type_id" id="submission_type_id" value="" />
        </form>
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

