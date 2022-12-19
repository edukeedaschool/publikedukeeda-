@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addWardFrm" id="addWardFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addWardSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addWardErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Ward Name</label>
                            <input id="wardName" type="text" class="form-control" name="wardName" value="" >
                            <div class="invalid-feedback" id="error_validation_wardName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="wardCountry" class="form-control" name="wardCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="wardState" class="form-control" name="wardState" onchange="getDistrictList(this.value,'wardDistrict','addWardErrorMessage');getMC1List(this.value,'wardMC1','addWardErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="wardDistrict" class="form-control" name="wardDistrict" onchange="getMC2List(this.value,'wardMC2','addWardErrorMessage');getCityCouncilList(this.value,'wardCC','addWardErrorMessage');">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Municipal Corporation</label>
                            <select id="wardMC1" class="form-control" name="wardMC1" >
                                <option value="">Municipal Corporation</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardMC1"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Municipality</label>
                            <select id="wardMC2" class="form-control" name="wardMC2" >
                                <option value="">Municipality</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardMC2"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>City Council</label>
                            <select id="wardCC" class="form-control" name="wardCC" >
                                <option value="">City Council</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardCC"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="wardStatus" class="form-control" name="wardStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="ward_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="ward_add_cancel" name="ward_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('ward/list')}}'">Cancel</button>
                           <button type="button" id ="ward_add_submit" name="ward_add_submit" class="btn btn-dialog" onclick="submitAddWard();">Submit</button>
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
