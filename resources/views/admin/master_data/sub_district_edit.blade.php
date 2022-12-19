@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editSubDistrictFrm" id="editSubDistrictFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editSubDistrictSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editSubDistrictErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub District Name</label>
                            <input id="sdName" type="text" class="form-control" name="sdName" value="{{$sd_data->sub_district_name}}" >
                            <div class="invalid-feedback" id="error_validation_sdName"></div>
                            <input id="sd_id" type="hidden" name="sd_id" value="{{$sd_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="sdCountry" class="form-control" name="sdCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_sdCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="sdState" class="form-control" name="sdState" onchange="getDistrictList(this.value,'sdDistrict','editSubDistrictErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $state_data->id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
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
                                <option value="1" @if($sd_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($sd_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_sdStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="sd_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="sd_edit_cancel" name="sd_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('sub-district/list')}}'">Cancel</button>
                           <button type="button" id ="sd_edit_submit" name="sd_edit_submit" class="btn btn-dialog" onclick="submitEditSubDistrict();">Submit</button>
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
    getDistrictList({{$state_data->id}},'sdDistrict','editSubDistrictErrorMessage',{{$sd_data->district_id}});
});
</script>
@endsection
