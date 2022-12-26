@extends('layouts.default')
@section('content')
    
    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editUserFrm" id="editUserFrm" type="POST" >
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editUserSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editUserErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Name</label>
                            <input id="userName" type="text" class="form-control" name="userName" value="{{$user_data->name}}"  maxlength="250">
                            <input type="hidden" name="user_id" id="user_id" value="{{$user_data->id}}">
                            <div class="invalid-feedback" id="error_validation_userName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Email Address</label>
                            <input id="emailAddress" type="text" class="form-control" name="emailAddress"value="{{$user_data->email}}" >
                            <div class="invalid-feedback" id="error_validation_emailAddress"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Password</label>
                            <input id="password" type="password" class="form-control" name="password" value="" >
                            <div class="invalid-feedback" id="error_validation_password"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Confirm Password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" >
                            <div class="invalid-feedback" id="error_validation_password_confirmation"></div>
                        </div>
                        
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-4" >
                            <label></label>
                            <input id="update_password" type="checkbox"  name="update_password" value="1" > Update Password
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
                            <label>Qualification</label>
                            <select id="qualification" class="form-control" name="qualification" onchange="displayUserQualificationData(this.value);">
                                <option value="">Qualification</option>
                                <option value="under_5th_class" @if($user_data->qualification == 'under_5th_class') selected @endif>Under 5th class</option>
                                <option value="under_8th_class" @if($user_data->qualification == 'under_8th_class') selected @endif>Under 8th class</option>
                                <option value="secondary" @if($user_data->qualification == 'secondary') selected @endif>Secondary (10th Class)</option>
                                <option value="higher_secondary" @if($user_data->qualification == 'higher_secondary') selected @endif>Higher Secondary (10+2)</option>
                                <option value="pursing_graduate" @if($user_data->qualification == 'pursing_graduate') selected @endif>Pursing Graduate</option>
                                <option value="graduate" @if($user_data->qualification == 'graduate') selected @endif>Graduate</option>
                                <option value="post_graduate" @if($user_data->qualification == 'post_graduate') selected @endif>Post Graduate</option>
                                <option value="doctorate" @if($user_data->qualification == 'doctorate') selected @endif>Doctorate</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_qualification"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row qual-div" id="qual-div-1" style="display:none;">
                        <div class="form-group col-md-3" >
                            <label id="qual-label-1">Expected Year of Degree Completion</label>
                            <input id="passOutYear" type="date" class="form-control" name="passOutYear" value="{{$user_data->degree_year}}" >
                            <div class="invalid-feedback" id="error_validation_passOutYear"></div>
                        </div>
                        <div class="form-group col-md-3" >
                            <label>Course Name</label>
                            <input id="courseName" type="text" class="form-control" name="courseName" value="{{$user_data->course_name}}" >
                            <div class="invalid-feedback" id="error_validation_courseName"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Profession</label>
                            <input id="profession" type="text" class="form-control" name="profession" value="{{$user_data->profession}}" maxlength="250">
                            <div class="invalid-feedback" id="error_validation_profession"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Major Identity</label>
                            <input id="majorIdentity" type="text" class="form-control" name="majorIdentity" value="{{$user_data->major_identity}}" maxlength="250">
                            <div class="invalid-feedback" id="error_validation_majorIdentity"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>More About You</label>
                            <input id="moreAboutYou" type="text" class="form-control" name="moreAboutYou" value="{{$user_data->more_about_you}}" maxlength="250">
                            <div class="invalid-feedback" id="error_validation_moreAboutYou"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Image</label>
                            <img src="{{url('images/user_images/'.$user_data->image)}}" style="width:80px;" class="img-thumbnail">  
                            <input id="userImage" type="file" class="form-control" name="userImage" value="" >
                            <div class="invalid-feedback" id="error_validation_userImage"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Address Line 1</label>
                            <input id="addressLine1" type="text" class="form-control" name="addressLine1" value="{{$user_data->address_line1}}"  maxlength="250">
                            <div class="invalid-feedback" id="error_validation_addressLine1"></div>
                        </div>
                    </div>  
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Postal Code</label>
                            <input id="postalCode" type="text" class="form-control" name="postalCode" value="{{$user_data->postal_code}}" onkeyup="updatePostalCodeData(this.value);" maxlength="6">
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
                                    <?php $sel = ($country_list[$i]['id'] == $user_data->country)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_country"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="state" class="form-control" name="state" onchange="getDistrictList(this.value,'district','editUserErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $user_data->state)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_state"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="district" class="form-control" name="district" onchange="getSubDistrictList(this.value,'subDistrict','editUserErrorMessage');">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_district"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="subDistrict" class="form-control" name="subDistrict" onchange="getVillageList(this.value,'village','editUserErrorMessage');">
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
                            <select id="userStatus" class="form-control" name="userStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($user_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($user_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_userStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="user_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="user_edit_cancel" name="user_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('user/list')}}'">Cancel</button>
                           <button type="button" id ="user_edit_submit" name="user_edit_submit" class="btn btn-dialog" onclick="submitEditUser();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script src="{{ asset('js/users.js') }}" ></script>
<script type="text/javascript" >
    $(document).ready(function(){
       getDistrictList("{{$user_data->state}}",'district','editUserErrorMessage',"{{$user_data->district}}"); 
       getSubDistrictList("{{$user_data->district}}",'subDistrict','editUserErrorMessage',"{{$user_data->sub_district}}");
       getVillageList("{{$user_data->sub_district}}",'village','editUserErrorMessage',"{{$user_data->village}}"); 
       displayUserQualificationData("{{$user_data->qualification}}");
    });
</script>
@endsection
