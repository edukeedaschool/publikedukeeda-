@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addSubDistrictFrm" id="addSubDistrictFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addSubDistrictSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addSubDistrictErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub District Name</label>
                            <input id="sdName" type="text" class="form-control" name="sdName" value="" >
                            <div class="invalid-feedback" id="error_validation_sdName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="sdCountry" class="form-control" name="sdCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_sdCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="sdState" class="form-control" name="sdState" onchange="getDistrictList(this.value,'sdDistrict','addSubDistrictErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_sdState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="sdDistrict" class="form-control" name="sdDistrict" >
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_sdDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="sdStatus" class="form-control" name="sdStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_sdStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="sd_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="sd_add_cancel" name="sd_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('sub-district/list')}}'">Cancel</button>
                           <button type="button" id ="sd_add_submit" name="sd_add_submit" class="btn btn-dialog" onclick="submitAddSubDistrict();">Submit</button>
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
