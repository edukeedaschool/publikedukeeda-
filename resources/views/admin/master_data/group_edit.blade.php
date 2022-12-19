@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="groupsContainer">
                <form class="" name="editGroupFrm" id="editGroupFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editGroupSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editGroupErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group Name</label>
                            <input id="groupName" type="text" class="form-control" name="groupName" value="{{$group_data->group_name}}" >
                            <div class="invalid-feedback" id="error_validation_groupName"></div>
                            <input id="group_id" type="hidden" name="group_id" value="{{$group_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group Type</label>
                            <select id="groupType" class="form-control" name="groupType" >
                                <option value="">Group Type</option>
                                <option value="political" @if($group_data->group_type == 'political') selected @endif>Political</option>
                                <option value="non_political" @if($group_data->group_type == 'non_political') selected @endif>Non political</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group Type</label>
                            <select id="groupSubType" class="form-control" name="groupSubType" >
                                <option value="">Group Sub Type</option>
                                <option value="person" @if($group_data->group_sub_type == 'person') selected @endif>Person</option>
                                <option value="government_department" @if($group_data->group_sub_type == 'government_department') selected @endif>Government Department</option>
                                <option value="nonprofit_organization" @if($group_data->group_sub_type == 'nonprofit_organization') selected @endif>Nonprofit Organization</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupSubType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="groupStatus" class="form-control" name="groupStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($group_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($group_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="group_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="group_edit_cancel" name="group_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('group/list')}}'">Cancel</button>
                           <button type="button" id ="group_edit_submit" name="group_edit_submit" class="btn btn-dialog" onclick="submitEditGroup();">Submit</button>
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
