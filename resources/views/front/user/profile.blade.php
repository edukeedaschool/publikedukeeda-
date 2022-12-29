@extends('layouts.default')

@section('content')
<style type="text/css">.qual-div{display:none;}</style>
<div class="container">
    <br/>
    <div class="alert alert-success alert-dismissible elem-hidden"  id="profileUpdateSuccessMessage"></div>
    <div class="alert alert-danger alert-dismissible elem-hidden"  id="profileUpdateErrorMessage"></div>
    
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">{{ __('User Profile') }}
                    <div style="float:right">
                        <a href="javascript:;" class="user-list-edit image-edit"  onclick="editProfileImage('edit');"><i title="Edit " class="far fa-edit"></i></a>
                        <a href="javascript:;" class="user-list-edit image-save" style="padding-left: 10px;display:none;" onclick="updateUserProfileImage();"><i title="Save" class="far fa-save"></i></a>
                        <a href="javascript:;" class="user-list-edit image-cancel" style="padding-left: 10px;display:none;" onclick="editProfileImage('cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('user/profile') }}" name="profile_image_form" id="profile_image_form" enctype="multipart/form-data">
                        <div class="row mb-3" >
                            <div class="col-md-12">
                                <img src="{{$user_profile['image_url']}}" class="img-thumbnail">
                            </div>
                        </div>
                        
                        <div class="row mb-3" style="display:none;" id="edit_image_div">
                            <div class="col-md-12">
                                <input id="profile_image" type="file" class="form-control" name="profile_image" value=""  >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                {{$user_profile['name']}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                {{$user_profile['email']}}
                            </div>
                        </div>
                         <div class="row mb-3">
                            <div class="col-md-12">
                                {{$user_profile['mobile_no']}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <a href="{{url('user/change-password')}}" class="btn btn-dialog">Change Password</a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <a href="{{url('user/profile/view/'.$user_profile['id'])}}" class="btn btn-dialog">View Profile</a>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>    
        </div>
        <div class="col-md-8" >
            <div class="card" id="general_div">
                <div class="card-header">{{ __('General Information') }}
                    <div style="float:right">
                        <a href="javascript:;" class="user-list-edit general-edit"  onclick="editUserProfile('general','edit');"><i title="Edit " class="far fa-edit"></i></a>
                        <a href="javascript:;" class="user-list-edit general-save" style="padding-left: 10px;display:none;" onclick="editUserProfile('general','save');"><i title="Save" class="far fa-save"></i></a>
                        <a href="javascript:;" class="user-list-edit general-cancel" style="padding-left: 10px;display:none;" onclick="editUserProfile('general','cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('user/profile') }}" name="general_form" id="general_form">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-5 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-7">
                                <input id="name" type="text" class="form-control" name="name" value="{{$user_profile['name']}}"  >
                                <div class="invalid-feedback" id="error_validation_name"></div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="mobile_no" class="col-md-5 col-form-label text-md-end">{{ __('Mobile No') }}</label>
                            <div class="col-md-7">
                                <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="{{$user_profile['mobile_no']}}" maxlength="10" >
                                <div class="invalid-feedback" id="error_validation_mobile_no"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="gender" class="col-md-5 col-form-label text-md-end">{{ __('Gender') }}</label>
                            <div class="col-md-7">
                                <select id="gender" class="form-control" name="gender"  >
                                    <option value="">Gender</option>
                                    <option value="male" @if($user_profile['gender'] == 'male') selected @endif>Male</option>
                                    <option value="female" @if($user_profile['gender'] == 'female') selected @endif>Female</option>
                                    <option value="other" @if($user_profile['gender'] == 'other') selected @endif>Other</option>
                                </select>    
                                <div class="invalid-feedback" id="error_validation_gender"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="dob" class="col-md-5 col-form-label text-md-end">{{ __('DOB') }}</label>
                            <div class="col-md-7">
                                <input id="dob" type="date" class="form-control" name="dob" value="{{$user_profile['dob']}}"  >
                                <div class="invalid-feedback" id="error_validation_dob"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bio" class="col-md-5 col-form-label text-md-end">{{ __('Key identity / Bio') }}</label>
                            <div class="col-md-7">
                                <input id="bio" type="text" class="form-control" name="bio" value=""  >
                                <div class="invalid-feedback" id="error_validation_bio"></div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="profession" class="col-md-5 col-form-label text-md-end">{{ __('Profession') }}</label>
                            <div class="col-md-7">
                                <input id="profession" type="text" class="form-control" name="profession" value="{{$user_profile['profession']}}"  >
                                <div class="invalid-feedback" id="error_validation_profession"></div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="more_about_you" class="col-md-5 col-form-label text-md-end">{{ __('More about you') }}</label>
                            <div class="col-md-7">
                                <input id="more_about_you" type="text" class="form-control" name="more_about_you" value="{{$user_profile['more_about_you']}}"  >
                                <div class="invalid-feedback" id="error_validation_more_about_you"></div>
                            </div>
                        </div>
                        <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{$user_profile['id']}}"  />
                    </form>
                </div>
            </div>
            
            <div class="clear">&nbsp;</div>
            
            <div class="card" id="qualification_div">
                <div class="card-header">{{ __('Qualification') }}
                    <div style="float:right">
                        <a href="javascript:;" class="user-list-edit qualification-edit"  onclick="editUserProfile('qualification','edit');"><i title="Edit " class="far fa-edit"></i></a>
                        <a href="javascript:;" class="user-list-edit qualification-save" style="padding-left: 10px;display:none;" onclick="editUserProfile('qualification','save');"><i title="Save" class="far fa-save"></i></a>
                        <a href="javascript:;" class="user-list-edit qualification-cancel" style="padding-left: 10px;display:none;" onclick="editUserProfile('qualification','cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('user/profile') }}" name="qualification_form" id="qualification_form">
                        @csrf
                        <div class="row mb-3">
                            <label for="qualification" class="col-md-5 col-form-label text-md-end">{{ __('Qualification') }}</label>
                            <div class="col-md-7">
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

                        <div class="row mb-3 qual-div">
                            <label for="degree_year" class="col-md-5 col-form-label text-md-end" id="qual-label-1">{{ __('Expected year of degree completion') }}</label>
                            <div class="col-md-7">
                                <input id="degree_year" type="date" class="form-control" name="degree_year" value="{{$user_profile['degree_year']}}"   >
                                <div class="invalid-feedback" id="error_validation_degree_year"></div>
                            </div>
                        </div>

                        <div class="row mb-3 qual-div">
                            <label for="course_name" class="col-md-5 col-form-label text-md-end">{{ __('Course Name') }}</label>
                            <div class="col-md-7">
                                <input id="course_name" type="text" class="form-control" name="course_name" value="{{$user_profile['course_name']}}"   >
                                <div class="invalid-feedback" id="error_validation_course_name"></div>
                            </div>
                        </div>
                        <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{$user_profile['id']}}"  />
                    </form>
                </div>
            </div>    
            
            <div class="clear">&nbsp;</div>
            
            <div class="alert alert-success alert-dismissible elem-hidden"  id="addressSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="addressErrorMessage"></div>
                    
            <div class="card" id="address_div">
                <div class="card-header">{{ __('Address') }}
                    <div style="float:right">
                        <a href="javascript:;" class="user-list-edit address-edit"  onclick="editUserProfile('address','edit');"><i title="Edit " class="far fa-edit"></i></a>
                        <a href="javascript:;" class="user-list-edit address-save" style="padding-left: 10px;display:none;" onclick="editUserProfile('address','save');"><i title="Save" class="far fa-save"></i></a>
                        <a href="javascript:;" class="user-list-edit address-cancel" style="padding-left: 10px;display:none;" onclick="editUserProfile('address','cancel');"><i title="Cancel" class="fa fa-times"></i></a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('user/profile') }}" name="address_form" id="address_form">
                        @csrf
                        <div class="row mb-3">
                            <label for="address_line1" class="col-md-5 col-form-label text-md-end">{{ __('Address line 1') }}</label>
                            <div class="col-md-7">
                                <input id="address_line1" type="text" class="form-control" name="address_line1" value="{{$user_profile['address_line1']}}"  >
                                <div class="invalid-feedback" id="error_validation_address_line1"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="postal_code" class="col-md-5 col-form-label text-md-end">{{ __('Postal Code') }}</label>
                            <div class="col-md-7">
                                <input id="postal_code" type="text" class="form-control" name="postal_code" value="{{$user_profile['postal_code']}}"  >
                                <div class="invalid-feedback" id="error_validation_postal_code"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="country" class="col-md-5 col-form-label text-md-end">{{ __('Country') }}</label>
                            <div class="col-md-7">
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

                        <div class="row mb-3">
                            <label for="state" class="col-md-5 col-form-label text-md-end">{{ __('State') }}</label>
                            <div class="col-md-7">
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

                        <div class="row mb-3">
                            <label for="district" class="col-md-5 col-form-label text-md-end">{{ __('District') }}</label>
                            <div class="col-md-7">
                                <select id="district" class="form-control" name="district" onchange="getSubDistrictList(this.value,'sub_district','addressErrorMessage');"  >
                                </select>
                                <div class="invalid-feedback" id="error_validation_district"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="sub_district" class="col-md-5 col-form-label text-md-end">{{ __('Sub District') }}</label>
                            <div class="col-md-7">
                                <select id="sub_district" class="form-control" name="sub_district" onchange="getVillageList(this.value,'village','addressErrorMessage');"  >
                                </select>
                                <div class="invalid-feedback" id="error_validation_sub_district"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="village" class="col-md-5 col-form-label text-md-end">{{ __('City/Town/Village') }}</label>
                            <div class="col-md-7">
                                <select id="village" class="form-control" name="village"   >
                                </select>
                                <div class="invalid-feedback" id="error_validation_village"></div>
                            </div>
                        </div>
                        <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{$user_profile['id']}}"  />
                    </form>
                </div>
            </div>    
            
            <div class="clear">&nbsp;</div>
            
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
        $("#general_div .form-control,#qualification_div .form-control,#address_div .form-control").attr('readonly',true).css('background-color','#fff');
    })
</script>    
@endsection