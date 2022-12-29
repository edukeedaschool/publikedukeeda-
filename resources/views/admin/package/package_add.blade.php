@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addPackageFrm" id="addPackageFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addPackageSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addPackageErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Package Name</label>
                            <input id="packageName" type="text" class="form-control" name="packageName" value="" >
                            <div class="invalid-feedback" id="error_validation_packageName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Message Call Options</label>
                            <select id="messageCallOption" class="form-control" name="messageCallOption" >
                                <option value="">Select One</option>
                                <option value="no">No</option>
                                <option value="one_to_one_message_call">One-to-one message-call</option>
                                <option value="group_message_call">Group message-call</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_messageCallOption"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Bulk Message Options</label>
                            <select id="bulkMessageOption" class="form-control" name="bulkMessageOption" >
                                <option value="">Select One</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_bulkMessageOption"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Permission to Receive Submissions</label>
                            <select id="receiveSubmission" class="form-control" name="receiveSubmission" onchange="if(this.value == 'yes') $('#submissionRangeDiv').show();else $('#submissionRangeDiv').hide(); ">
                                <option value="">Select One</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_receiveSubmission"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="submissionRangeDiv" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>Permitted Range Unit</label>
                            <select id="submissionRange" class="form-control" name="submissionRange" >
                                <option value="">Select One</option>
                                <option value="country">Country</option>
                                <option value="state">State</option>
                                <option value="district">District</option>
                                <option value="pc">Parliamentary Constituency</option>
                                <option value="ac">Assembly Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_submissionRange"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Package Validity</label>
                            <select id="packageValidity" class="form-control" name="packageValidity" >
                                <option value="">Select One</option>
                                <option value="3">3 Months</option>
                                <option value="6">6 Months</option>
                                <option value="12">1 Year</option>
                                <option value="24">2 Years</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_packageValidity"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="packageStatus" class="form-control" name="packageStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_packageStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="package_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="package_add_cancel" name="package_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('package/list')}}'">Cancel</button>
                           <button type="button" id ="package_add_submit" name="package_add_submit" class="btn btn-dialog" onclick="submitAddPackage();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/package.js') }}" ></script>

@endsection
