@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editWardFrm" id="editWardFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editWardSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editWardErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Ward Name</label>
                            <input id="wardName" type="text" class="form-control" name="wardName" value="{{$ward_data->ward_name}}" >
                            <div class="invalid-feedback" id="error_validation_wardName"></div>
                            <input id="ward_id" type="hidden" name="ward_id" value="{{$ward_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="wardCountry" class="form-control" name="wardCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="wardState" class="form-control" name="wardState" onchange="getDistrictList(this.value,'wardDistrict','editWardErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $state_data->id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="wardDistrict" class="form-control" name="wardDistrict" >
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
                                <option value="1" @if($ward_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($ward_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_wardStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="ward_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="ward_edit_cancel" name="ward_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('ward/list')}}'">Cancel</button>
                           <button type="button" id ="ward_edit_submit" name="ward_edit_submit" class="btn btn-dialog" onclick="submitEditWard();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
<script type="text/javascript">
$(document).ready(function(){
    getDistrictList({{$state_data->id}},'wardDistrict','editWardErrorMessage',{{$ward_data->district_id}});
    getMC1List({{$state_data->id}},'wardMC1','editWardErrorMessage',{{$ward_data->mc1_id}});
    getMC2List({{$ward_data->district_id}},'wardMC2','editWardErrorMessage',{{$ward_data->mc2_id}});
    getCityCouncilList({{$ward_data->district_id}},'wardCC','editWardErrorMessage',{{$ward_data->city_council_id}});
});
</script>
@endsection
