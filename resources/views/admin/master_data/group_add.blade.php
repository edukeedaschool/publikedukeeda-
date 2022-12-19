@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="groupsContainer">
                <form class="" name="addGroupFrm" id="addGroupFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addGroupSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addGroupErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group Name</label>
                            <input id="groupName" type="text" class="form-control" name="groupName" value="" >
                            <div class="invalid-feedback" id="error_validation_groupName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group Type</label>
                            <select id="groupType" class="form-control" name="groupType" >
                                <option value="">Group Type</option>
                                <option value="political">Political</option>
                                <option value="non_political ">Non political</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group Type</label>
                            <select id="groupSubType" class="form-control" name="groupSubType" >
                                <option value="">Group Sub Type</option>
                                <option value="person">Person</option>
                                <option value="government_department">Government Department</option>
                                <option value="nonprofit_organization">Nonprofit Organization</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupSubType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="groupStatus" class="form-control" name="groupStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_groupStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="group_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="group_add_cancel" name="group_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('group/list')}}'">Cancel</button>
                           <button type="button" id ="group_add_submit" name="group_add_submit" class="btn btn-dialog" onclick="submitAddGroup();">Submit</button>
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
