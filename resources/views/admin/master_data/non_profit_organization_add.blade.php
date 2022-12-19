@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addOrganizationFrm" id="addOrganizationFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addOrganizationSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addOrganizationErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Name</label>
                            <input id="organizationName" type="text" class="form-control" name="organizationName" value="" >
                            <div class="invalid-feedback" id="error_validation_organizationName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Short Name</label>
                            <input id="organizationShortName" type="text" class="form-control" name="organizationShortName" value="" >
                            <div class="invalid-feedback" id="error_validation_organizationShortName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Type</label>
                            <select id="organizationType" class="form-control" name="organizationType" onchange="toggleOrganizationTypeData(this.value);">
                                <option value="">Organization Type</option>
                                <option value="trust">Trust</option>
                                <option value="society">Society</option>
                                <option value="section_8_company">Section-8 Company</option>
                                <option value="other">Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_organizationType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="otherTypeNameDiv" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>Other Name</label>
                            <input id="organizationOtherTypeName" type="text" class="form-control" name="organizationOtherTypeName" value="" >
                            <div class="invalid-feedback" id="error_validation_organizationOtherTypeName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Working Area</label>
                            <input id="organizationWorkingArea" type="text" class="form-control" name="organizationWorkingArea" value="" >
                            <div class="invalid-feedback" id="error_validation_organizationWorkingArea"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Organization Icon</label>
                            <input id="organizationIcon" type="file" class="form-control" name="organizationIcon" value="" >
                            <div class="invalid-feedback" id="error_validation_organizationIcon"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="organizationStatus" class="form-control" name="organizationStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_organizationStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="organization_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="organization_add_cancel" name="organization_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('non-profit-organization/list')}}'">Cancel</button>
                           <button type="button" id ="organization_add_submit" name="organization_add_submit" class="btn btn-dialog" onclick="submitAddOrganization();">Submit</button>
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
