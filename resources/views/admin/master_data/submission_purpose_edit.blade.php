@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="groupsContainer">
                <form class="" name="editSubmissionPurposeFrm" id="editSubmissionPurposeFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editSubmissionPurposeSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editSubmissionPurposeErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Submission Purpose</label>
                            <input id="submissionPurpose" type="text" class="form-control" name="submissionPurpose" value="{{$sub_purpose_data->submission_purpose}}" >
                            <div class="invalid-feedback" id="error_validation_submissionPurpose"></div>
                            <input id="sub_purpose_id" type="hidden" name="sub_purpose_id" value="{{$sub_purpose_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group</label>
                            <select id="groupId" class="form-control" name="groupId" >
                                <option value="">Group</option>
                                @for($i=0;$i<count($group_list);$i++)
                                    <?php $sel = ($group_list[$i]['id'] == $sub_purpose_data->group_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$group_list[$i]['id']}}">{{$group_list[$i]['group_name']}}</option>
                                @endfor  
                            </select>   
                            <div class="invalid-feedback" id="error_validation_groupId"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="submissionPurposeStatus" class="form-control" name="submissionPurposeStatus">
                                <option value="">Status</option>
                                <option value="1" @if($sub_purpose_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($sub_purpose_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_submissionPurposeStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <button type="button" id="sub_group_edit_cancel" name="sub_group_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('submission-purpose/list')}}'">Cancel</button>
                            <button type="button" id ="sub_group_edit_submit" name="sub_group_edit_submit" class="btn btn-dialog" onclick="submitEditSubmissionPurpose();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>

@endsection
