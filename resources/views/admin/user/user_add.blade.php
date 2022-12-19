@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addUserFrm" id="addUserFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addUserSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addUserErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Name</label>
                            <input id="userName" type="text" class="form-control" name="userName" value="" >
                            <div class="invalid-feedback" id="error_validation_userName"></div>
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
                            <input id="mobileNumber" type="text" class="form-control" name="mobileNumber" value="" >
                            <div class="invalid-feedback" id="error_validation_mobileNumber"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Gender</label>
                            <select id="gender" class="form-control" name="gender" >
                                <option value="">Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_gender"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>DOB</label>
                            <input id="DOB" type="date" class="form-control" name="DOB" value="" >
                            <div class="invalid-feedback" id="error_validation_DOB"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="userCountry" class="form-control" name="userCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_userCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="userState" class="form-control" name="userState" onchange="getDistrictList(this.value,'userDistrict','addUserErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_userState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="userDistrict" class="form-control" name="userDistrict" >
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_userDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="userStatus" class="form-control" name="userStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_userStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="user_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="user_add_cancel" name="user_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('user/list')}}'">Cancel</button>
                           <button type="button" id ="user_add_submit" name="user_add_submit" class="btn btn-dialog" onclick="submitAddUser();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>

@endsection
