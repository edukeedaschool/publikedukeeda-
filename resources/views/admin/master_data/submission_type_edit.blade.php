@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="groupsContainer">
                <form class="" name="editSubmissionTypeFrm" id="editSubmissionTypeFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editSubmissionTypeSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editSubmissionTypeErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Submission Type</label>
                            <input id="submissionType" type="text" class="form-control" name="submissionType" value="{{$sub_type_data->submission_type}}" >
                            <div class="invalid-feedback" id="error_validation_submissionType"></div>
                            <input id="sub_type_id" type="hidden" name="sub_type_id" value="{{$sub_type_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group</label>
                            <select id="groupId" class="form-control" name="groupId" >
                                <option value="">Group</option>
                                @for($i=0;$i<count($group_list);$i++)
                                    <?php $sel = ($group_list[$i]['id'] == $sub_type_data->group_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$group_list[$i]['id']}}">{{$group_list[$i]['group_name']}}</option>
                                @endfor  
                            </select>   
                            <div class="invalid-feedback" id="error_validation_groupId"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="submissionTypeStatus" class="form-control" name="submissionTypeStatus">
                                <option value="">Status</option>
                                <option value="1" @if($sub_type_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($sub_type_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_submissionTypeStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <button type="button" id="sub_group_edit_cancel" name="sub_group_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('submission-type/list')}}'">Cancel</button>
                            <button type="button" id ="sub_group_edit_submit" name="sub_group_edit_submit" class="btn btn-dialog" onclick="submitEditSubmissionType();">Submit</button>
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
