@extends('layouts.default')
@section('content')

    <style type="text/css">.loc-div{display:none;}</style>
    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editTeamMemberFrm" id="editTeamMemberFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addTeamMemberSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addTeamMemberErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Email Address</label>
                            <input id="emailAddress" type="text" class="form-control" name="emailAddress" value="{{$user_data->email}}" onblur="getTeamMemberData(this.value);">
                            <input type="hidden" name="tm_id" id="tm_id" value="{{$tm_data->id}}">
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
                            <select id="designation" class="form-control" name="designation" onchange="displayTeamMemberAssignedArea(this.value);">
                                <option value="">Designation</option>
                                @for($i=0;$i<count($designation_list);$i++)
                                    <?php $sel = ($designation_list[$i]['id'] == $tm_data->designation_id)?'selected':''; ?>    
                                    <option {{$sel}} value="{{$designation_list[$i]['id']}}">{{$designation_list[$i]['designation_name']}} ({{$designation_list[$i]['representation_area']}})</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_designation"></div>
                        </div>
                    </div>
                    
                    <div class="form-row loc-div" id="country_div">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="country_tm" class="form-control" name="country_tm" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $tm_data->country_tm)?'selected':''; ?>    
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor     
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country_tm"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row loc-div" id="state_div">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="state_tm" class="form-control" name="state_tm" onchange="getDistrictList(this.value,'district_tm','addTeamMemberErrorMessage','');getMC1List(this.value,'MC1_tm','addTeamMemberErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $tm_data->state_tm)?'selected':''; ?>    
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor      
                            </select>    
                            <div class="invalid-feedback" id="error_validation_state_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row loc-div" id="district_div">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="district_tm" class="form-control" name="district_tm" onchange="getDistrictFieldsData(this.value,'tm',''); ">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_district_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row loc-div" id="subDistrict_div">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="subDistrict_tm" class="form-control" name="subDistrict_tm" onchange="getVillageList(this.value,'village_tm','addTeamMemberErrorMessage','');">
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subDistrict_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row loc-div" id="village_div">
                        <div class="form-group col-md-6" >
                            <label>City/Village/Town</label>
                            <select id="village_tm" class="form-control" name="village_tm" >
                                <option value="">City/Village/Town</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_village_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="MC1_div">
                        <div class="form-group col-md-6" >
                            <label>Municipal Corporation</label>
                            <select id="MC1_tm" class="form-control" name="MC1_tm" >
                                <option value="">Municipal Corporation</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC1_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="MC2_div">
                        <div class="form-group col-md-6" >
                            <label>Municipality</label>
                            <select id="MC2_tm" class="form-control" name="MC2_tm" >
                                <option value="">Municipality</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC2_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="CC_div" >
                        <div class="form-group col-md-6" >
                            <label>City Council</label>
                            <select id="CC_tm" class="form-control" name="CC_tm" onchange="getWardList(this.value,'ward_tm','addTeamMemberErrorMessage','');">
                                <option value="">City Council</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_CC_tm"></div>
                        </div>
                    </div>
                    
                    <div class="form-row  loc-div" id="LAC_div">
                        <div class="form-group col-md-6" >
                            <label>Legislative Assembly Constituency</label>
                            <select id="LAC_tm" class="form-control" name="LAC_tm" >
                                <option value="">Legislative Assembly Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_LAC_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="PC_div">
                        <div class="form-group col-md-6" >
                            <label>Parliamentary Constituency</label>
                            <select id="PC_tm" class="form-control" name="PC_tm" >
                                <option value="">Parliamentary Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_PC_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="block_div">
                        <div class="form-group col-md-6" >
                            <label>Block</label>
                            <select id="block_tm" class="form-control" name="block_tm" >
                                <option value="">Block</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_block_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row  loc-div" id="ward_div">
                        <div class="form-group col-md-6" >
                            <label>Ward</label>
                            <select id="ward_tm" class="form-control" name="ward_tm" >
                                <option value="">Ward</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ward_tm"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="memberStatus" class="form-control" name="memberStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($tm_data->status == '1') selected @endif>Enabled</option>
                                <option value="0" @if($tm_data->status == '0') selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_memberStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <button type="button" id="member_edit_cancel" name="member_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('team-member/list')}}'">Cancel</button>
                            <button type="button" id ="member_edit_submit" name="member_edit_submit" class="btn btn-dialog" onclick="submitEditTeamMember();">Submit</button>
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
        displayTeamMemberAssignedArea({{$tm_data->designation_id}});
        
        @if(!empty($tm_data->state_tm)) 
            getDistrictList("{{$tm_data->state_tm}}",'district_tm','addSubscriberErrorMessage',"{{$tm_data->district_tm}}"); 
            getMC1List("{{$tm_data->state_tm}}",'MC1_tm','addSubscriberErrorMessage',"{{$tm_data->mc1_tm}}"); 
        @endif

        @if(!empty($tm_data->district_tm)) 
            getSubDistrictList("{{$tm_data->district_tm}}",'subDistrict_tm','addTeamMemberErrorMessage',"{{$tm_data->sub_district_tm}}");
            getMC2List("{{$tm_data->district_tm}}",'MC2_tm','addTeamMemberErrorMessage',"{{$tm_data->mc2_tm}}");
            getCityCouncilList("{{$tm_data->district_tm}}",'CC_tm','addTeamMemberErrorMessage',"{{$tm_data->cc_tm}}");
            getBlockList("{{$tm_data->district_tm}}",'block_tm','addTeamMemberErrorMessage',"{{$tm_data->block_tm}}");
            getLACList("{{$tm_data->district_tm}}",'LAC_tm','addTeamMemberErrorMessage',"{{$tm_data->lac_tm}}");
            getPCList("{{$tm_data->district_tm}}",'PC_tm','addTeamMemberErrorMessage',"{{$tm_data->pc_tm}}");
        @endif
    
        @if(!empty($tm_data->sub_district_tm)) getVillageList("{{$tm_data->sub_district_tm}}",'village_tm','addSubscriberErrorMessage',"{{$tm_data->village_tm}}");  @endif
        @if(!empty($tm_data->cc_tm)) getWardList("{{$tm_data->cc_tm}}",'ward_tm','addSubscriberErrorMessage',"{{$tm_data->ward_tm}}");  @endif
    });
</script>    
@endsection
