@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editSubscriberPackageFrm" id="editSubscriberPackageFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editSubscriberPackageSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editSubscriberPackageErrorMessage"></div>
                    <input type="hidden" name="subscriber_package_id" id="subscriber_package_id" value="{{$subscriber_package_data->id}}">

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Subscriber</label>
                            <select id="subscriber" class="form-control" name="subscriber"  >
                                <option value="">Subscriber</option>
                                @for($i=0;$i<count($subscriber_list);$i++)
                                    <?php if($subscriber_list[$i]['id'] == $subscriber_package_data->subscriber_id) $sel = 'selected';else $sel = ''; ?>
                                    <option {{$sel}} value="{{$subscriber_list[$i]['id']}}">{{$subscriber_list[$i]['subscriber_name']}}</option>
                                @endfor  
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subscriber"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Package</label>
                            <select id="package" class="form-control" name="package" onchange="getPackageData(this.value,'editSubscriberPackageErrorMessage','','','','');"  >
                            <option value="">Package</option>
                            @for($i=0;$i<count($package_list);$i++)
                                <?php if($package_list[$i]['id'] == $subscriber_package_data->package_id) $sel = 'selected';else $sel = ''; ?>
                                <option {{$sel}} value="{{$package_list[$i]['id']}}">{{$package_list[$i]['package_name']}}</option>
                            @endfor      
                            </select>    
                            <div class="invalid-feedback" id="error_validation_package"></div>
                        </div>
                    </div> 
                    
                    <div class="form-row" id="country_div" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="country" class="form-control" name="country" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php if($country_list[$i]['id'] == $subscriber_package_data->country) $sel = 'selected';else $sel = ''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="state_div" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <div id="state_elem_div"></div> 
                            <div class="invalid-feedback" id="error_validation_state"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row" id="district_div" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <div id="district_elem_div"></div>    
                            <div class="invalid-feedback" id="error_validation_district"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row" id="pc_div" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>Parliamentary Constituency</label>
                            <div id="pc_elem_div"></div>  
                            <div class="invalid-feedback" id="error_validation_pc"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row" id="ac_div" style="display:none;">
                        <div class="form-group col-md-6">
                            <label>Assembly Constituency</label>
                            <div id="ac_elem_div"></div>
                            <div class="invalid-feedback" id="error_validation_ac"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Total Price</label>
                            <input id="total_price" type="text" class="form-control" name="total_price" value="{{$subscriber_package_data->total_price}}" readonly="true" >
                            <div class="invalid-feedback" id="error_validation_total_price"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Discounted Price</label>
                            <input id="discounted_price" type="text" class="form-control" name="discounted_price" value="{{$subscriber_package_data->discounted_price}}" >
                            <div class="invalid-feedback" id="error_validation_discounted_price"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="status" class="form-control" name="status" >
                                <option value="">Status</option>
                                <option value="1" @if($subscriber_package_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($subscriber_package_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_status"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <button type="button" id="subscriber_package_edit_cancel" name="subscriber_package_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('subscriber-package/list')}}'">Cancel</button>
                           <button type="button" id ="subscriber_package_edit_submit" name="subscriber_package_edit_submit" class="btn btn-dialog" onclick="submitEditSubscriberPackage();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
<script src="{{ asset('js/package.js') }}" ></script>
<script type="text/javascript" >
    $(document).ready(function(){
        //getPackageData("{{$subscriber_package_data->package_id}}");
        getPackageData("{{$subscriber_package_data->package_id}}",'editSubscriberPackageErrorMessage','{{$subscriber_package_data->state}}','{{$subscriber_package_data->district}}','{{$subscriber_package_data->ac}}','{{$subscriber_package_data->pc}}');
        /*$("#country").val("{{$subscriber_package_data->country}}");
        $("#state").val("{{$subscriber_package_data->state}}");
        getDistrictList("{{$subscriber_package_data->state}}",'district','editSubscriberPackageErrorMessage',"{{$subscriber_package_data->district}}");
        getLACList("{{$subscriber_package_data->district}}",'ac','editSubscriberPackageErrorMessage',"{{$subscriber_package_data->ac}}");
        getPCList("{{$subscriber_package_data->district}}",'pc','editSubscriberPackageErrorMessage',"{{$subscriber_package_data->pc}}");*/
    });
</script>
@endsection
