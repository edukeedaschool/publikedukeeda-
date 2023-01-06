@extends('layouts.front_profile')

@section('content')
<style type="text/css">.qual-div{display:none;}</style>
<div class="container">
    
    <div class="row justify-content-center" id="content_div">
        <div class="col-md-3">
            <div class="bg-white h-100 p-4">
                <form method="POST" action="{{ url('user/profile') }}" name="profile_image_form" id="profile_image_form" enctype="multipart/form-data">
                    <div style="float:right">
                        <a href="javascript:;" class="user-list-edit image-edit"  onclick="editProfileImage('edit');"><i title="Edit " class="far fa-edit"></i></a>
                        <a href="javascript:;" class="user-list-edit image-save" style="padding-left: 10px;display:none;" onclick="updateUserProfileImage();"><i title="Save" class="far fa-save"></i></a>
                        <a href="javascript:;" class="user-list-edit image-cancel" style="padding-left: 10px;display:none;" onclick="editProfileImage('cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                    </div>
                    <div class="userPropic">
                        <figure>
                            @if($user_profile['user_role'] == 2)
                                <a href="{{url('subscriber/profile/view/'.$user_profile['subscriber_id'])}}">
                            @else
                                <a href="{{url('user/profile/view/'.$user_profile['id'])}}">
                            @endif
                                <img src="{{$user_profile['image_url']}}" class="img-thumbnail">
                            </a>
                            <figcaption>{{$user_profile['name']}}</figcaption>
                        </figure>
                        <ul class="mt-4 userEmail text-left">
                          <li>Email Id:- {{$user_profile['email']}}</li>
                          <li>Phone:- {{$user_profile['mobile_no']}}</li>
                          <li><a href="{{url('user/change-password')}}" class="btn btn-primary">Change Password</a></li>
                        </ul>
                        <ul class="mt-4 userEmail text-left" style="display:none;" id="edit_image_div">
                            <input id="profile_image" type="file" class="form-control" name="profile_image" value=""  >
                            <div class="invalid-feedback" id="error_validation_profile_image"></div>
                        </ul>

                        <ul class="mt-4 following text-left">
                          <li><a href="#">Following 15</a></li>
                          <li><a href="#">Followers 250</a></li>
                        </ul>

                    </div>
                </form>     
            </div>
        </div>
        
        <div class="col-lg-6">
          <div class="mainCenter">
            <div class="choose">
                <div class="alert alert-success alert-dismissible elem-hidden"  id="profileUpdateSuccessMessage"></div>
                <div class="alert alert-danger alert-dismissible elem-hidden"  id="profileUpdateErrorMessage"></div>
                <div id="general_div">
                    <form method="POST" action="{{ url('user/profile') }}" name="general_form" id="general_form">
                        <h2 class="mt-3 justify-content-between d-flex">General Information 
                        
                    
                    <div style="float:right">
                        <a href="javascript:;" style="font-size:14px;" class="user-list-edit general-edit"  onclick="editUserProfile('general','edit');"><i title="Edit " class="far fa-edit"></i></a>
                        <a href="javascript:;" class="user-list-edit general-save" style="padding-left: 10px;display:none;font-size:14px" onclick="editUserProfile('general','save');"><i title="Save" class="far fa-save"></i></a>
                        <a href="javascript:;" class="user-list-edit general-cancel" style="padding-left: 10px;display:none;font-size:14px" onclick="editUserProfile('general','cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                    </div></h2>
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="name">Official Name</label>
                        <div class="col-md-8">
                            <input id="official_name" name="official_name" type="text" placeholder="Official Name" class="form-control" value="{{$user_profile['official_name']}}">
                            <div class="invalid-feedback" id="error_validation_official_name"></div>
                        </div>
                    </div>
              
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="name">Name</label>
                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control" name="name" value="{{$user_profile['name']}}" placeholder="Name" >
                            <div class="invalid-feedback" id="error_validation_name"></div>
                        </div>
                    </div>
              
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="name">Mobile No</label>
                        <div class="col-md-8">
                            <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="{{$user_profile['mobile_no']}}" maxlength="10" >
                            <div class="invalid-feedback" id="error_validation_mobile_no"></div>
                        </div>
                    </div>
              
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="email">Gender</label>
                        <div class="col-md-8">
                            <select id="gender" class="form-control" name="gender"  >
                                <option value="">Gender</option>
                                <option value="male" @if($user_profile['gender'] == 'male') selected @endif>Male</option>
                                <option value="female" @if($user_profile['gender'] == 'female') selected @endif>Female</option>
                                <option value="other" @if($user_profile['gender'] == 'other') selected @endif>Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_gender"></div>
                        </div>
                    </div>
              
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="email">Date of birth</label>
                        <div class="col-md-8">
                            <input id="dob" type="date" class="form-control" name="dob" value="{{$user_profile['dob']}}"  >
                            <div class="invalid-feedback" id="error_validation_dob"></div>
                        </div>
                    </div>
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="email">Key identity / Bio</label>
                        <div class="col-md-8">
                            <input id="bio" type="text" class="form-control" name="bio" value=""  >
                            <div class="invalid-feedback" id="error_validation_bio"></div>
                        </div>
                    </div>
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="email">Profession</label>
                        <div class="col-md-8">
                            <input id="profession" type="text" class="form-control" name="profession" value="{{$user_profile['profession']}}"  >
                            <div class="invalid-feedback" id="error_validation_profession"></div>
                        </div>
                    </div>
                    <div class="form-group row no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="email">More about you</label>
                        <div class="col-md-8">
                            <input id="more_about_you" type="text" class="form-control" name="more_about_you" value="{{$user_profile['more_about_you']}}"  >
                            <div class="invalid-feedback" id="error_validation_more_about_you"></div>
                        </div>
                    </div>
                    <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{$user_profile['id']}}"  />    
                    </form>    
                </div>    

                <hr>
                <div id="qualification_div">    
                    <form method="POST" action="{{ url('user/profile') }}" name="qualification_form" id="qualification_form">
                        <h2 class="mt-3 justify-content-between d-flex">Qualification 
                            <div style="float:right">
                                <a href="javascript:;" class="user-list-edit qualification-edit" style="font-size:14px;"  onclick="editUserProfile('qualification','edit');"><i title="Edit " class="far fa-edit"></i></a>
                                <a href="javascript:;" class="user-list-edit qualification-save" style="padding-left: 10px;display:none;font-size:14px;" onclick="editUserProfile('qualification','save');"><i title="Save" class="far fa-save"></i></a>
                                <a href="javascript:;" class="user-list-edit qualification-cancel" style="padding-left: 10px;display:none;font-size:14px;" onclick="editUserProfile('qualification','cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                            </div>
                        </h2>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">Qualification</label>
                            <div class="col-md-8">
                              <select id="qualification" class="form-control" name="qualification"  onchange="displayQualificationData(this.value);" >
                                  <option value="">Qualification</option>
                                  @foreach($qualification_list as $key=>$val)
                                      <?php $sel = ($user_profile['qualification'] == $key)?'selected':''; ?>
                                      <option {{$sel}} value="{{$key}}">{{$val}}</option>
                                  @endforeach
                              </select>    
                              <div class="invalid-feedback" id="error_validation_qualification"></div>
                            </div>
                        </div>

                        <div class="form-group row no-gutters align-items-center qual-div">
                            <label class="col-md-4 control-label" for="email" id="qual-label-1">Expected completion year</label>
                            <div class="col-md-8">
                                <input id="degree_year" type="date" class="form-control" name="degree_year" value="{{$user_profile['degree_year']}}"   >
                                <div class="invalid-feedback" id="error_validation_degree_year"></div>
                            </div>
                        </div>
                        <div class="form-group row no-gutters align-items-center qual-div">
                            <label class="col-md-4 control-label" for="email">Course Name</label>
                            <div class="col-md-8">
                                <input id="course_name" type="text" class="form-control" name="course_name" value="{{$user_profile['course_name']}}"   >
                                <div class="invalid-feedback" id="error_validation_course_name"></div>
                            </div>
                        </div>
                        <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{$user_profile['id']}}"  />
                    </form>
                </div>    
                
                <div id="address_div">
                    <form method="POST" action="{{ url('user/profile') }}" name="address_form" id="address_form">
                        <div class="clear">&nbsp;</div>

                        <div class="alert alert-success alert-dismissible elem-hidden"  id="addressSuccessMessage"></div>
                        <div class="alert alert-danger alert-dismissible elem-hidden"  id="addressErrorMessage"></div>

                        <hr>
                        <h2 class="mt-3 justify-content-between d-flex">Address 
                            <div style="float:right">
                                <a href="javascript:;" class="user-list-edit address-edit" style="font-size:14px;"  onclick="editUserProfile('address','edit');"><i title="Edit " class="far fa-edit"></i></a>
                                <a href="javascript:;" class="user-list-edit address-save" style="padding-left: 10px;display:none;font-size:14px;" onclick="editUserProfile('address','save');"><i title="Save" class="far fa-save"></i></a>
                                <a href="javascript:;" class="user-list-edit address-cancel" style="padding-left: 10px;display:none;font-size:14px;" onclick="editUserProfile('address','cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                            </div>
                        </h2>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">Address line 1</label>
                            <div class="col-md-8">
                                <input id="address_line1" type="text" class="form-control" name="address_line1" value="{{$user_profile['address_line1']}}"  >
                                <div class="invalid-feedback" id="error_validation_address_line1"></div>
                            </div>
                        </div>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">Pin Code</label>
                            <div class="col-md-8">
                                <input id="postal_code" type="text" class="form-control" name="postal_code" value="{{$user_profile['postal_code']}}"  >
                                <div class="invalid-feedback" id="error_validation_postal_code"></div>
                            </div>
                        </div>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">Country</label>
                            <div class="col-md-8">
                                <select id="country" class="form-control" name="country"   >
                                    <option value="">Country</option>
                                    @for($i=0;$i<count($country_list);$i++)
                                        <?php $sel = ($user_profile['country'] == $country_list[$i]['id'])?'selected':''; ?>
                                        <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                    @endfor  
                                </select>
                                <div class="invalid-feedback" id="error_validation_country"></div>
                            </div>
                        </div>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">State</label>
                            <div class="col-md-8">
                                <select id="state" class="form-control" name="state"  onchange="getDistrictList(this.value,'district','addressErrorMessage');" >
                                    <option value="">State</option>
                                    @for($i=0;$i<count($states_list);$i++)
                                        <?php $sel = ($user_profile['state'] == $states_list[$i]['id'])?'selected':''; ?>
                                        <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                    @endfor    
                                </select>
                                <div class="invalid-feedback" id="error_validation_state"></div>
                            </div>
                        </div>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">District</label>
                            <div class="col-md-8">
                                <select id="district" class="form-control" name="district" onchange="getSubDistrictList(this.value,'sub_district','addressErrorMessage');"  ></select>
                                <div class="invalid-feedback" id="error_validation_district"></div>
                            </div>
                        </div>
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">Sub-district</label>
                            <div class="col-md-8">
                                <select id="sub_district" class="form-control" name="sub_district" onchange="getVillageList(this.value,'village','addressErrorMessage');"  ></select>
                                <div class="invalid-feedback" id="error_validation_sub_district"></div>
                            </div>
                        </div> 
                        <div class="form-group row no-gutters align-items-center">
                            <label class="col-md-4 control-label" for="email">City/Town/Village</label>
                            <div class="col-md-8">
                                <select id="village" class="form-control" name="village"   ></select>
                                <div class="invalid-feedback" id="error_validation_village"></div>
                            </div>
                        </div>   
                        <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{$user_profile['id']}}"  />
                    </form>
                </div>
                </div>
          </div>

        </div>
        <div class="col-md-3 d-none d-lg-block">
            @include('front.right_banner') 
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/front_data.js') }}" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        getDistrictList("{{$user_profile['state']}}",'district','editUserErrorMessage',"{{$user_profile['district']}}"); 
        getSubDistrictList("{{$user_profile['district']}}",'sub_district','editUserErrorMessage',"{{$user_profile['sub_district']}}");
        getVillageList("{{$user_profile['sub_district']}}",'village','editUserErrorMessage',"{{$user_profile['village']}}");
        displayQualificationData("{{$user_profile['qualification']}}");
        $("#general_div .form-control,#qualification_div .form-control,#address_div .form-control").attr('readonly',true).css('background-color','#e9ecef');
        
    });
</script>    
@endsection