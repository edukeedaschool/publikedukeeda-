@extends('layouts.default')
@section('content')
    <style>.toggle-div {display:none;} .pp-rep-area-field,.eo-rep-area-field{display:none;}</style>
    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addSubscriberFrm" id="addSubscriberFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden" id="addSubscriberSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addSubscriberErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Subscriber Name</label>
                            <input id="subscriberName" type="text" class="form-control" name="subscriberName" value="" >
                            <div class="invalid-feedback" id="error_validation_subscriberName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Office Belongs To</label>
                            <select id="officeBelongsTo" class="form-control" name="officeBelongsTo" onchange="getOfficeBelongsToData(this.value);">
                                <option value="">Office Belongs To</option>
                                @for($i=0;$i<count($sub_group_list);$i++)
                                    <option value="{{$sub_group_list[$i]['id']}}">{{$sub_group_list[$i]['sub_group_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_officeBelongsTo"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="gender_div">
                        <div class="form-group col-md-6" >
                            <label>Gender</label>
                            <select id="subscriberGender" class="form-control" name="subscriberGender" >
                                <option value="">Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subscriberGender"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="DOB_div">
                        <div class="form-group col-md-6" >
                            <label>DOB</label>
                            <input id="subscriberDOB" type="date" class="form-control" name="subscriberDOB" value="" >
                            <div class="invalid-feedback" id="error_validation_subscriberDOB"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="politicalParty_div">
                        <div class="form-group col-md-6" >
                            <label>Political Party</label>
                            <select id="politicalParty" class="form-control" name="politicalParty" >
                                <option value="">Political Party</option>
                                @for($i=0;$i<count($pol_party_list);$i++)
                                    <option value="{{$pol_party_list[$i]['id']}}">{{$pol_party_list[$i]['party_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_politicalParty"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="politicalPartyOfficialPosition_div">
                        <div class="form-group col-md-6" >
                            <label>Official Position in Political Party</label>
                            <select id="politicalPartyOfficialPosition" class="form-control" name="politicalPartyOfficialPosition"  onchange="toggleOfficialPosData();">
                                <option value="">Official Position in Political Party</option>
                                <option value="0">No Official Position</option>
                                @for($i=0;$i<count($off_pos_pol_party);$i++)
                                    <option value="{{$off_pos_pol_party[$i]['id']}}">{{$off_pos_pol_party[$i]['position_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_politicalPartyOfficialPosition"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row toggle-div" id="repAreaOfficialPartyPosition_div">
                        <div class="form-group col-md-6" >
                            <label>Representation Area (of official party position)</label>
                            <select id="repAreaOfficialPartyPosition" class="form-control" name="repAreaOfficialPartyPosition" onchange="toggleRepAreaFields(this.value,'pp');">
                                <option value="">Representation Area</option>
                                @foreach($rep_area as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_repAreaOfficialPartyPosition"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="country_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="country_pp" class="form-control" name="country_pp" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country_pp"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row pp-rep-area-field" id="state_pp_div">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="state_pp" class="form-control" name="state_pp" onchange="updateSubscriberFields(this.value,'state','pp',''); ">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_state_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="district_pp_div" >
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="district_pp" class="form-control" name="district_pp" onchange="updateSubscriberFields(this.value,'district','pp',''); " >
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_district_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="subDistrict_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="subDistrict_pp" class="form-control" name="subDistrict_pp" onchange="updateSubscriberFields(this.value,'sub_district','pp',''); ">
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subDistrict_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="MC1_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Municipal Corporation</label>
                            <select id="MC1_pp" class="form-control" name="MC1_pp" >
                                <option value="">Municipal Corporation</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC1_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="MC2_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Municipality</label>
                            <select id="MC2_pp" class="form-control" name="MC2_pp" >
                                <option value="">Municipality</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC2_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="CC_pp_div" >
                        <div class="form-group col-md-6" >
                            <label>City Council</label>
                            <select id="CC_pp" class="form-control" name="CC_pp" onchange="updateSubscriberFields(this.value,'city_council','pp',''); ">
                                <option value="">City Council</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_CC_pp"></div>
                        </div>
                    </div>
                    
                    <div class="form-row pp-rep-area-field" id="LAC_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Legislative Assembly Constituency</label>
                            <select id="LAC_pp" class="form-control" name="LAC_pp" >
                                <option value="">Legislative Assembly Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_LAC_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="PC_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Parliamentary Constituency</label>
                            <select id="PC_pp" class="form-control" name="PC_pp" >
                                <option value="">Parliamentary Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_PC_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="block_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Block</label>
                            <select id="block_pp" class="form-control" name="block_pp" >
                                <option value="">Block</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_block_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="ward_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Ward</label>
                            <select id="ward_pp" class="form-control" name="ward_pp" >
                                <option value="">Ward</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ward_pp"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row pp-rep-area-field" id="village_pp_div">
                        <div class="form-group col-md-6" >
                            <label>Village</label>
                            <select id="village_pp" class="form-control" name="village_pp" >
                                <option value="">Village</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_village_pp"></div>
                        </div>
                    </div>
                    
                    <div class="form-row toggle-div" id="electedOfficialPositionName_div">
                        <div class="form-group col-md-6" >
                            <label>Elected Official Position Name</label>
                            <select id="electedOfficialPositionName" class="form-control" name="electedOfficialPositionName" onchange="toggleOfficialPosData();">
                                <option value="">Elected Official Position Name</option>
                                <option value="0">Not Applicable</option>
                                @for($i=0;$i<count($elec_off_position);$i++)
                                    <option value="{{$elec_off_position[$i]['id']}}">{{$elec_off_position[$i]['position_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_electedOfficialPositionName"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row toggle-div"  id="repAreaElectedOfficialPosition_div">
                        <div class="form-group col-md-6" >
                            <label>Representation Area (of Elected official position)</label>
                            <select id="repAreaElectedOfficialPosition" class="form-control" name="repAreaElectedOfficialPosition" onchange="toggleRepAreaFields(this.value,'eo');">
                                <option value="">Representation Area</option>
                                @foreach($rep_area as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_repAreaElectedOfficialPosition"></div>
                        </div>
                    </div>   
                    
                    
                    <div class="form-row eo-rep-area-field" id="country_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="country_eo" class="form-control" name="country_eo" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country_eo"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row eo-rep-area-field" id="state_eo_div">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="state_eo" class="form-control" name="state_eo" onchange="updateSubscriberFields(this.value,'state','eo',''); ">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_state_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="district_eo_div" >
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="district_eo" class="form-control" name="district_eo" onchange="updateSubscriberFields(this.value,'district','eo',''); " >
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_district_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="subDistrict_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="subDistrict_eo" class="form-control" name="subDistrict_eo" onchange="updateSubscriberFields(this.value,'sub_district','eo',''); ">
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subDistrict_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="MC1_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Municipal Corporation</label>
                            <select id="MC1_eo" class="form-control" name="MC1_eo" >
                                <option value="">Municipal Corporation</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC1_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="MC2_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Municipality</label>
                            <select id="MC2_eo" class="form-control" name="MC2_eo" >
                                <option value="">Municipality</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_MC2_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="CC_eo_div" >
                        <div class="form-group col-md-6" >
                            <label>City Council</label>
                            <select id="CC_eo" class="form-control" name="CC_eo" onchange="updateSubscriberFields(this.value,'city_council','eo',''); ">
                                <option value="">City Council</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_CC_eo"></div>
                        </div>
                    </div>
                    
                    <div class="form-row eo-rep-area-field" id="LAC_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Legislative Assembly Constituency</label>
                            <select id="LAC_eo" class="form-control" name="LAC_eo" >
                                <option value="">Legislative Assembly Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_LAC_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="PC_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Parliamentary Constituency</label>
                            <select id="PC_eo" class="form-control" name="PC_eo" >
                                <option value="">Parliamentary Constituency</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_PC_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="block_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Block</label>
                            <select id="block_eo" class="form-control" name="block_eo" >
                                <option value="">Block</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_block_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="ward_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Ward</label>
                            <select id="ward_eo" class="form-control" name="ward_eo" >
                                <option value="">Ward</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ward_eo"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row eo-rep-area-field" id="village_eo_div">
                        <div class="form-group col-md-6" >
                            <label>Village</label>
                            <select id="village_eo" class="form-control" name="village_eo" >
                                <option value="">Village</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_village_eo"></div>
                        </div>
                    </div>
                    
                    
                    <div class="form-row toggle-div" id="keyIdentity1_div">
                        <div class="form-group col-md-6" >
                            <label>Key Identity</label>
                            <input id="keyIdentity1" type="text" class="form-control" name="keyIdentity1" value="" >
                            <div class="invalid-feedback" id="error_validation_keyIdentity1"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="keyIdentity2_div">
                        <div class="form-group col-md-6" >
                            <label>Key Identity (Position)</label>
                            <input id="keyIdentity2" type="text" class="form-control" name="keyIdentity2" value="" >
                            <div class="invalid-feedback" id="error_validation_keyIdentity2"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="organizationName_div">
                        <div class="form-group col-md-6" >
                            <label>Organization Name</label>
                            <select id="organizationName" class="form-control" name="organizationName" >
                                <option value="">Organization Name</option>
                                <option value="government_department">Government Department</option>
                                <option value="non_profit_organization">Nonprofit Organization</option>
                                <option value="not_applicable">Not Applicable</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_organizationName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row toggle-div" id="authorizedPersonName_div">
                        <div class="form-group col-md-6" >
                            <label>Authorized Person Name</label>
                            <input id="authorizedPersonName" type="text" class="form-control" name="authorizedPersonName" value="" >
                            <div class="invalid-feedback" id="error_validation_authorizedPersonName"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row toggle-div" id="authorizedPersonDesignation_div">
                        <div class="form-group col-md-6" >
                            <label>Authorized Person Designation</label>
                            <input id="authorizedPersonDesignation" type="text" class="form-control" name="authorizedPersonDesignation" value="" >
                            <div class="invalid-feedback" id="error_validation_authorizedPersonDesignation"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Email Address</label>
                            <input id="emailAddress" type="text" class="form-control" name="emailAddress" value="" >
                            <div class="invalid-feedback" id="error_validation_emailAddress"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Mobile Number</label>
                            <input id="mobileNumber" type="text" class="form-control" name="mobileNumber" value="" maxlength="12">
                            <div class="invalid-feedback" id="error_validation_mobileNumber"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Image</label>
                            <input id="subscriberImage" type="file" class="form-control" name="subscriberImage" value="" >
                            <div class="invalid-feedback" id="error_validation_subscriberImage"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Address Line 1</label>
                            <input id="addressLine1" type="text" class="form-control" name="addressLine1" value="" >
                            <div class="invalid-feedback" id="error_validation_addressLine1"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Postal Code</label>
                            <input id="postalCode" type="text" class="form-control" name="postalCode" value="" onkeyup="updatePostalCodeData(this.value);" maxlength="6">
                            <div class="invalid-feedback" id="error_validation_postalCode"></div>
                            <div class="alert alert-danger alert-dismissible elem-hidden" id="postalCodeError" style="padding-top:2px;padding-bottom:2px; "></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="country" class="form-control" name="country" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="state" class="form-control" name="state" onchange="getDistrictList(this.value,'district','addSubscriberErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_state"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="district" class="form-control" name="district" onchange="getSubDistrictList(this.value,'subDistrict','addSubscriberErrorMessage');">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_district"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="subDistrict" class="form-control" name="subDistrict" onchange="getVillageList(this.value,'village','addSubscriberErrorMessage');">
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>City/Village/Town</label>
                            <select id="village" class="form-control" name="village" >
                                <option value="">City/Village/Town</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_village"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="subscriberStatus" class="form-control" name="subscriberStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subscriberStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="subscriber_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                            <button type="button" id="subscriber_add_cancel" name="subscriber_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('subscriber/list')}}'">Cancel</button>
                            <button type="button" id ="subscriber_add_submit" name="subscriber_add_submit" class="btn btn-dialog" onclick="submitAddSubscriber();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script src="{{ asset('js/master_data.js') }}" ></script>

@endsection
