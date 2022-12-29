@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editPackageFrm" id="editPackageFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editPackageSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editPackageErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Package Name</label>
                            <input id="packageName" type="text" class="form-control" name="packageName" value="{{$package_data->package_name}}" >
                            <input id="package_id" type="hidden"  name="package_id" value="{{$package_data->id}}" >
                            <div class="invalid-feedback" id="error_validation_packageName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Message Call Options</label>
                            <select id="messageCallOption" class="form-control" name="messageCallOption" >
                                <option value="">Select One</option>
                                <option value="no" @if($package_data->message_call == 'no') selected @endif>No</option>
                                <option value="one_to_one_message_call" @if($package_data->message_call == 'one_to_one_message_call') selected @endif>One-to-one message-call</option>
                                <option value="group_message_call" @if($package_data->message_call == 'group_message_call') selected @endif>Group message-call</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_messageCallOption"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Bulk Message Options</label>
                            <select id="bulkMessageOption" class="form-control" name="bulkMessageOption" >
                                <option value="">Select One</option>
                                <option value="yes" @if($package_data->bulk_message == 'yes') selected @endif>Yes</option>
                                <option value="no" @if($package_data->bulk_message == 'no') selected @endif>No</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_bulkMessageOption"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Permission to Receive Submissions</label>
                            <select id="receiveSubmission" class="form-control" name="receiveSubmission" onchange="if(this.value == 'yes') $('#submissionRangeDiv').show();else $('#submissionRangeDiv').hide(); " >
                                <option value="">Select One</option>
                                <option value="yes" @if($package_data->receive_submission == 'yes') selected @endif>Yes</option>
                                <option value="no" @if($package_data->receive_submission == 'no') selected @endif>No</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_receiveSubmission"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="submissionRangeDiv" @if($package_data->receive_submission == 'no') style="display:none;" @endif>
                        <div class="form-group col-md-6" >
                            <label>Permitted Range Unit</label>
                            <select id="submissionRange" class="form-control" name="submissionRange" >
                                <option value="">Select One</option>
                                <option value="country" @if($package_data->receive_submission_range == 'country') selected @endif>Country</option>
                                <option value="state" @if($package_data->receive_submission_range == 'state') selected @endif>State</option>
                                <option value="district" @if($package_data->receive_submission_range == 'district') selected @endif>District</option>
                                <option value="pc" @if($package_data->receive_submission_range == 'pc') selected @endif>Parliamentary Constituency</option>
                                <option value="ac" @if($package_data->receive_submission_range == 'ac') selected @endif>Assembly Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_submissionRange"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Package Validity</label>
                            <select id="packageValidity" class="form-control" name="packageValidity" >
                                <option value="">Select One</option>
                                <option value="3" @if($package_data->package_validity == '3') selected @endif>3 Months</option>
                                <option value="6" @if($package_data->package_validity == '6') selected @endif>6 Months</option>
                                <option value="12" @if($package_data->package_validity == '12') selected @endif>1 Year</option>
                                <option value="24" @if($package_data->package_validity == '24') selected @endif>2 Years</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_packageValidity"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="packageStatus" class="form-control" name="packageStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($package_data->status == '1') selected @endif>Enabled</option>
                                <option value="0" @if($package_data->status == '0') selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_packageStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="package_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="package_edit_cancel" name="package_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('package/list')}}'">Cancel</button>
                           <button type="button" id ="package_edit_submit" name="package_edit_submit" class="btn btn-dialog" onclick="submitEditPackage();">Submit</button>
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
