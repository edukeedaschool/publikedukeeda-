@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addPostalCodeFrm" id="addPostalCodeFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addPostalCodeSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addPostalCodeErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Postal Code</label>
                            <input id="pcName" type="text" class="form-control" name="pcName" value="" >
                            <div class="invalid-feedback" id="error_validation_pcName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Post Office</label>
                            <input id="pcPostOffice" type="text" class="form-control" name="pcPostOffice" value="" >
                            <div class="invalid-feedback" id="error_validation_pcPostOffice"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="pcCountry" class="form-control" name="pcCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="pcState" class="form-control" name="pcState" onchange="getDistrictList(this.value,'pcDistrict','addPostalCodeErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="pcDistrict" class="form-control" name="pcDistrict" onchange="getSubDistrictList(this.value,'pcSubDistrict','addPostalCodeErrorMessage');">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="pcSubDistrict" class="form-control" name="pcSubDistrict" >
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcSubDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="pcStatus" class="form-control" name="pcStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="pc_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="pc_add_cancel" name="pc_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('postal-code/list')}}'">Cancel</button>
                           <button type="button" id ="pc_add_submit" name="pc_add_submit" class="btn btn-dialog" onclick="submitAddPostalCode();">Submit</button>
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
