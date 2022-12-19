@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="subsubmissionPurposesContainer">
                <form class="" name="addSubmissionPurposeFrm" id="addSubmissionPurposeFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addSubmissionPurposeSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addSubmissionPurposeErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Submission Purpose</label>
                            <input id="submissionPurpose" type="text" class="form-control" name="submissionPurpose" value="" >
                            <div class="invalid-feedback" id="error_validation_submissionPurpose"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group</label>
                            <select id="groupId" class="form-control" name="groupId" >
                                <option value="">Group</option>
                                @for($i=0;$i<count($group_list);$i++)
                                    <option value="{{$group_list[$i]['id']}}">{{$group_list[$i]['group_name']}}</option>
                                @endfor  
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupId"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="submissionPurposeStatus" class="form-control" name="submissionPurposeStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_submissionPurposeStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                           <button type="button" id="subsubmissionPurpose_add_cancel" name="subsubmissionPurpose_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('submission-purpose/list')}}'">Cancel</button>
                           <button type="button" id ="subsubmissionPurpose_add_submit" name="subsubmissionPurpose_add_submit" class="btn btn-dialog" onclick="submitAddSubmissionPurpose();">Submit</button>
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
