@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editPackagePriceFrm" id="editPackagePriceFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editPackagePriceSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editPackagePriceErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>One-to-one message/call option availability (Rs)</label>
                            <input id="one_to_one_message_call" type="text" class="form-control" name="one_to_one_message_call" value="@if(isset($package_price_data->one_to_one_message_call)) {{$package_price_data->one_to_one_message_call}} @endif" >
                            <input id="package_price_id" type="hidden"  name="package_price_id" value="@if(isset($package_price_data->id)) {{$package_price_data->id}} @endif" >
                            <div class="invalid-feedback" id="error_validation_one_to_one_message_call"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group message/call option availability (Rs)</label>
                            <input id="group_message_call" type="text" class="form-control" name="group_message_call" value="@if(isset($package_price_data->group_message_call)) {{$package_price_data->group_message_call}} @endif" >
                            <div class="invalid-feedback" id="error_validation_group_message_call"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Bulk message option availability (to all followers) (Rs)</label>
                            <input id="bulk_message" type="text" class="form-control" name="bulk_message" value="@if(isset($package_price_data->bulk_message)) {{$package_price_data->bulk_message}} @endif" >
                            <div class="invalid-feedback" id="error_validation_bulk_message"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>If permitted range Country (Rs)</label>
                            <input id="country_range" type="text" class="form-control" name="country_range" value="@if(isset($package_price_data->country_range)) {{$package_price_data->country_range}} @endif" >
                            <div class="invalid-feedback" id="error_validation_country_range"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>If permitted range State (Rs)</label>
                            <input id="state_range" type="text" class="form-control" name="state_range" value="@if(isset($package_price_data->state_range)) {{$package_price_data->state_range}} @endif" >
                            <div class="invalid-feedback" id="error_validation_state_range"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>If permitted range District (Rs)</label>
                            <input id="district_range" type="text" class="form-control" name="district_range" value="@if(isset($package_price_data->district_range)) {{$package_price_data->district_range}} @endif" >
                            <div class="invalid-feedback" id="error_validation_district_range"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>If permitted range Parliamentary Constituency (Rs)</label>
                            <input id="pc_range" type="text" class="form-control" name="pc_range" value="@if(isset($package_price_data->pc_range)) {{$package_price_data->pc_range}} @endif" >
                            <div class="invalid-feedback" id="error_validation_pc_range"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>If permitted range Assembly Constituency (Rs)</label>
                            <input id="ac_range" type="text" class="form-control" name="ac_range" value="@if(isset($package_price_data->ac_range)) {{$package_price_data->ac_range}} @endif" >
                            <div class="invalid-feedback" id="error_validation_ac_range"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Discount for 6 months package</label>
                            <input id="discount_6_month" type="text" class="form-control" name="discount_6_month" value="@if(isset($package_price_data->discount_6_month)) {{$package_price_data->discount_6_month}} @endif" >
                            <div class="invalid-feedback" id="error_validation_discount_6_month"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Discount for 1 year package</label>
                            <input id="discount_1_year" type="text" class="form-control" name="discount_1_year" value="@if(isset($package_price_data->discount_1_year)) {{$package_price_data->discount_1_year}} @endif" >
                            <div class="invalid-feedback" id="error_validation_discount_1_year"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Discount for 2 year package</label>
                            <input id="discount_2_year" type="text" class="form-control" name="discount_2_year" value="@if(isset($package_price_data->discount_2_year)) {{$package_price_data->discount_2_year}} @endif" >
                            <div class="invalid-feedback" id="error_validation_discount_2_year"></div>
                        </div>
                    </div>  
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="package_price_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="package_price_edit_cancel" name="package_price_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('package-price/list')}}'">Cancel</button>
                           <button type="button" id ="package_price_edit_submit" name="package_price_edit_submit" class="btn btn-dialog" onclick="submitEditPackagePrice();">Submit</button>
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
