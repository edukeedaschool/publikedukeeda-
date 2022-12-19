@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editOrganizationFrm" id="editOrganizationFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editOrganizationSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editOrganizationErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Name</label>
                            <input id="organizationName" type="text" class="form-control" name="organizationName" value="{{$organization_data->organization_name}}" >
                            <div class="invalid-feedback" id="error_validation_organizationName"></div>
                            <input id="organization_id" type="hidden" name="organization_id" value="{{$organization_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Short Name</label>
                            <input id="organizationShortName" type="text" class="form-control" name="organizationShortName" value="{{$organization_data->organization_short_name}}" >
                            <div class="invalid-feedback" id="error_validation_organizationShortName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Type</label>
                            <select id="organizationType" class="form-control" name="organizationType" onchange="toggleOrganizationTypeData(this.value);">
                                <option value="">Organization Type</option>
                               <option value="trust" @if($organization_data->organization_type == 'trust') selected @endif>Trust</option>
                                <option value="society" @if($organization_data->organization_type == 'society') selected @endif>Society</option>
                                <option value="section_8_company" @if($organization_data->organization_type == 'section_8_company') selected @endif>Section-8 Company</option>
                                <option value="other" @if($organization_data->organization_type == 'other') selected @endif>Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_organizationType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="otherTypeNameDiv" @if($organization_data->organization_type != 'other') style="display:none;" @endif>
                        <div class="form-group col-md-6" >
                            <label>Other Name</label>
                            <input id="organizationOtherTypeName" type="text" class="form-control" name="organizationOtherTypeName" value="{{$organization_data->other_type_name}}" >
                            <div class="invalid-feedback" id="error_validation_organizationOtherTypeName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Working Area</label>
                            <input id="organizationWorkingArea" type="text" class="form-control" name="organizationWorkingArea" value="{{$organization_data->working_area}}" >
                            <div class="invalid-feedback" id="error_validation_organizationWorkingArea"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Icon</label>
                            <img src="{{url('images/govt_dept_icon/'.$organization_data->organization_icon)}}" style="width:80px;" class="img-thumbnail">  
                            <input id="organizationIcon" type="file" class="form-control" name="organizationIcon" value="" >
                            <div class="invalid-feedback" id="error_validation_organizationIcon"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="organizationStatus" class="form-control" name="organizationStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($organization_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($organization_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_organizationStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="organization_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="organization_edit_cancel" name="organization_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('non-profit-organization/list')}}'">Cancel</button>
                           <button type="button" id ="organization_edit_submit" name="organization_edit_submit" class="btn btn-dialog" onclick="submitEditOrganization();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
<script type="text/javascript">
$(document).ready(function(){
});
</script>
@endsection
