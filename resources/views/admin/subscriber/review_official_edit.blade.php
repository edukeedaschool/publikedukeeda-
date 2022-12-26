@extends('layouts.default')
@section('content')

    <style type="text/css">.loc-div{display:none;}</style>
    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editReviewOfficialFrm" id="editReviewOfficialFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addReviewOfficialSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addReviewOfficialErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Email Address</label>
                            <input id="emailAddress" type="text" class="form-control" name="emailAddress" value="{{$user_data->email}}" onblur="getTeamMemberData(this.value);">
                            <input type="hidden" name="ro_id" id="ro_id" value="{{$ro_data->id}}">
                            <div class="invalid-feedback" id="error_validation_emailAddress"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Username</label>
                            <input id="user_Name" type="text" class="form-control" name="user_Name" value="{{$user_data->user_name}}" readonly="true" >
                            <div class="invalid-feedback" id="error_validation_user_Name"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Name</label>
                            <input id="userName" type="text" class="form-control" name="userName" value="{{$user_data->name}}" >
                            <div class="invalid-feedback" id="error_validation_userName"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Mobile Number</label>
                            <input id="mobileNumber" type="text" class="form-control" name="mobileNumber" value="{{$user_data->mobile_no}}" maxlength="10">
                            <div class="invalid-feedback" id="error_validation_mobileNumber"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Gender</label>
                            <select id="gender" class="form-control" name="gender" >
                                <option value="">Gender</option>
                                <option value="male" @if($user_data->gender == 'male') selected @endif>Male</option>
                                <option value="female" @if($user_data->gender == 'female') selected @endif>Female</option>
                                <option value="other" @if($user_data->gender == 'other') selected @endif>Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_gender"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>DOB</label>
                            <input id="DOB" type="date" class="form-control" name="DOB" value="{{$user_data->dob}}" >
                            <div class="invalid-feedback" id="error_validation_DOB"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Designation</label>
                            <select id="subscriber_review_id" class="form-control" name="subscriber_review_id" onchange="getReviewOfficialRangeData(this.value);">
                                <option value="">Designation</option>
                                @for($i=0;$i<count($subscriber_reviews);$i++)
                                    <?php $sel = ($subscriber_reviews[$i]['id'] == $ro_data->subscriber_review_id)?'selected':''; ?>    
                                    <option {{$sel}} value="{{$subscriber_reviews[$i]['id']}}">{{$subscriber_reviews[$i]['designation']}} ({{$subscriber_reviews[$i]['review_range']}}) </option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subscriber_review_id"></div>
                        </div>
                    </div>
                    
                    <div class="form-row loc-div" id="country_div">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="country_ro" class="form-control" name="country_ro" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $ro_data->country_ro)?'selected':''; ?>    
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor     
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country_ro"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row loc-div" id="state_div">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="state_ro" class="form-control" name="state_ro" onchange="getDistrictList(this.value,'district_ro','addTeamMemberErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $ro_data->state_ro)?'selected':''; ?>    
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor      
                            </select>    
                            <div class="invalid-feedback" id="error_validation_state_ro"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row loc-div" id="district_div">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="district_ro" class="form-control" name="district_ro" onchange="getDistrictFieldsData(this.value,'tm',''); ">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_district_ro"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row loc-div" id="subDistrict_div">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="subDistrict_ro" class="form-control" name="subDistrict_ro" onchange="getVillageList(this.value,'village_ro','addTeamMemberErrorMessage','');">
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subDistrict_ro"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row loc-div" id="village_div">
                        <div class="form-group col-md-6" >
                            <label>City/Village/Town</label>
                            <select id="village_ro" class="form-control" name="village_ro" >
                                <option value="">City/Village/Town</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_village_ro"></div>
                        </div>
                    </div>   
                    
                    <?php /* ?>
                    <div class="form-row  loc-div" id="MC1_div">
                        <div class="form-group col-md-6" >
                            <label>Municipal Corporation</label>
                            <select id="MC1_ro" class="form-control" name="MC1_ro" >
                                <option value="">Municipal Corporation</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC1_ro"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="MC2_div">
                        <div class="form-group col-md-6" >
                            <label>Municipality</label>
                            <select id="MC2_ro" class="form-control" name="MC2_ro" >
                                <option value="">Municipality</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC2_ro"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="CC_div" >
                        <div class="form-group col-md-6" >
                            <label>City Council</label>
                            <select id="CC_ro" class="form-control" name="CC_ro" onchange="getWardList(this.value,'ward_ro','addTeamMemberErrorMessage','');">
                                <option value="">City Council</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_CC_ro"></div>
                        </div>
                    </div>
                    
                    <div class="form-row  loc-div" id="LAC_div">
                        <div class="form-group col-md-6" >
                            <label>Legislative Assembly Constituency</label>
                            <select id="LAC_ro" class="form-control" name="LAC_ro" >
                                <option value="">Legislative Assembly Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_LAC_ro"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="PC_div">
                        <div class="form-group col-md-6" >
                            <label>Parliamentary Constituency</label>
                            <select id="PC_ro" class="form-control" name="PC_ro" >
                                <option value="">Parliamentary Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_PC_ro"></div>
                        </div>
                    </div>   <?php */ ?>
                    
                    <div class="form-row  loc-div" id="block_div">
                        <div class="form-group col-md-6" >
                            <label>Block</label>
                            <select id="block_ro" class="form-control" name="block_ro" >
                                <option value="">Block</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_block_ro"></div>
                        </div>
                    </div>   
                    
                    <?php /* ?>
                    <div class="form-row  loc-div" id="ward_div">
                        <div class="form-group col-md-6" >
                            <label>Ward</label>
                            <select id="ward_ro" class="form-control" name="ward_ro" >
                                <option value="">Ward</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ward_ro"></div>
                        </div>
                    </div>   <?php */ ?>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="reviewOfficialStatus" class="form-control" name="reviewOfficialStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($ro_data->status == '1') selected @endif>Enabled</option>
                                <option value="0" @if($ro_data->status == '0') selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_reviewOfficialStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <button type="button" id="member_edit_cancel" name="member_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('review-official/list')}}'">Cancel</button>
                            <button type="button" id ="member_edit_submit" name="member_edit_submit" class="btn btn-dialog" onclick="submitEditReviewOfficial();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
<script src="{{ asset('js/team.js') }}" ></script>
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script type="text/javascript" >
    $(document).ready(function(){
        getReviewOfficialRangeData({{$ro_data->subscriber_review_id}});
        
        @if(!empty($ro_data->state_ro)) 
            getDistrictList("{{$ro_data->state_ro}}",'district_ro','addReviewOfficialErrorMessage',"{{$ro_data->district_ro}}"); 
        @endif

        @if(!empty($ro_data->district_ro)) 
            getSubDistrictList("{{$ro_data->district_ro}}",'subDistrict_ro','addReviewOfficialErrorMessage',"{{$ro_data->sub_district_ro}}");
            getBlockList("{{$ro_data->district_ro}}",'block_ro','addReviewOfficialErrorMessage',"{{$ro_data->block_ro}}");
        @endif
    
        @if(!empty($ro_data->sub_district_ro)) getVillageList("{{$ro_data->sub_district_ro}}",'village_ro','addReviewOfficialErrorMessage',"{{$ro_data->village_ro}}");  @endif
        
    });
</script>    
@endsection
