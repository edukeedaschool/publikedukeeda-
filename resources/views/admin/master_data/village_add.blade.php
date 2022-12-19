@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addVillageFrm" id="addVillageFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addVillageSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addVillageErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Village Name</label>
                            <input id="villageName" type="text" class="form-control" name="villageName" value="" >
                            <div class="invalid-feedback" id="error_validation_villageName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="villageCountry" class="form-control" name="villageCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_villageCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="villageState" class="form-control" name="villageState" onchange="getDistrictList(this.value,'villageDistrict','addVillageErrorMessage');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_villageState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="villageDistrict" class="form-control" name="villageDistrict" onchange="getSubDistrictList(this.value,'villageSubDistrict','addVillageErrorMessage');">
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_villageDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub District</label>
                            <select id="villageSubDistrict" class="form-control" name="villageSubDistrict" >
                                <option value="">Sub District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_villageSubDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="villageStatus" class="form-control" name="villageStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_villageStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="village_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="village_add_cancel" name="village_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('village/list')}}'">Cancel</button>
                           <button type="button" id ="village_add_submit" name="village_add_submit" class="btn btn-dialog" onclick="submitAddVillage();">Submit</button>
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
