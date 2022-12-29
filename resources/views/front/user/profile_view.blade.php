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
                <div class="card-header">{{ __('User Profile') }}</div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <img src="{{$user_profile['image_url']}}" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            {{$user_profile['name']}}
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <div class="col-md-8" >
            <div class="card" id="general_div">
                <div class="card-header">{{ __('General Information') }}
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
                                <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="{{$user_profile['mobile_no']}}"  >
                                <div class="invalid-feedback" id="error_validation_mobile_no"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="gender" class="col-md-5 col-form-label text-md-end">{{ __('Gender') }}</label>
                            <div class="col-md-7">
                                <input id="gender" type="text" class="form-control" name="gender" value="{{ucfirst($user_profile['gender'])}}"  >
                                <div class="invalid-feedback" id="error_validation_gender"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="dob" class="col-md-5 col-form-label text-md-end">{{ __('DOB') }}</label>
                            <div class="col-md-7">
                                <input id="dob" type="text" class="form-control" name="dob" value="@if(!empty($user_profile['dob'])) {{date('d M Y',strtotime($user_profile['dob']))}} @endif"  >
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
                        
                        <div class="row mb-3">
                            <label for="more_about_you" class="col-md-5 col-form-label text-md-end">{{ __('Place') }}</label>
                            <div class="col-md-7">
                                <input id="place" type="text" class="form-control" name="place" value="{{$user_profile['district_name']}}, {{$user_profile['state_name']}}, {{$user_profile['country_name']}}"  >
                                <div class="invalid-feedback" id="error_validation_place"></div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="Qualification" class="col-md-5 col-form-label text-md-end">{{ __('Qualification') }}</label>
                            <div class="col-md-7">
                                <?php if($user_profile['qualification'] == 'pursing_graduate') { $qualification = 'Pursuing '.$user_profile['course_name'].' / Expected year '.$user_profile['degree_year']; }
                                elseif(in_array($user_profile['qualification'], ['pursing_graduate','graduate','post_graduate','doctorate'])) { $qualification = $user_profile['course_name'].' / '.date('Y',strtotime($user_profile['degree_year'])).' passout'; }
                                else{ $qualification = $user_profile['qualification']; }
                                ?>
                                <input id="qualification" type="text" class="form-control" name="qualification" value="{{$qualification}}"  >
                                <div class="invalid-feedback" id="error_validation_more_about_you"></div>
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
        $("#general_div .form-control").attr('readonly',true).css('background-color','#fff');
    })
</script>    
@endsection