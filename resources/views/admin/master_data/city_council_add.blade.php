@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addCityCouncilFrm" id="addCityCouncilFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addCityCouncilSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addCityCouncilErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>City Council Name</label>
                            <input id="ccName" type="text" class="form-control" name="ccName" value="" >
                            <div class="invalid-feedback" id="error_validation_ccName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="ccCountry" class="form-control" name="ccCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ccCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="ccState" class="form-control" name="ccState" onchange="getDistrictList(this.value,'ccDistrict','addCityCouncilErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ccState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="ccDistrict" class="form-control" name="ccDistrict" >
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ccDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="ccStatus" class="form-control" name="ccStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ccStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="cc_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="cc_add_cancel" name="cc_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('city-council/list')}}'">Cancel</button>
                           <button type="button" id ="cc_add_submit" name="cc_add_submit" class="btn btn-dialog" onclick="submitAddCityCouncil();">Submit</button>
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
