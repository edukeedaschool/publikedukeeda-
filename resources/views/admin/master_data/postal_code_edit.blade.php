@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editPostalCodeFrm" id="editPostalCodeFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editPostalCodeSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editPostalCodeErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Postal Code</label>
                            <input id="pcName" type="text" class="form-control" name="pcName" value="{{$pc_data->postal_code}}" >
                            <div class="invalid-feedback" id="error_validation_pcName"></div>
                            <input id="pc_id" type="hidden" name="pc_id" value="{{$pc_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Post Office</label>
                            <input id="pcPostOffice" type="text" class="form-control" name="pcPostOffice" value="{{$pc_data->post_office}}" >
                            <div class="invalid-feedback" id="error_validation_pcPostOffice"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="pcCountry" class="form-control" name="pcCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="pcState" class="form-control" name="pcState" onchange="getDistrictList(this.value,'pcDistrict','editPostalCodeErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $state_data->id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="pcDistrict" class="form-control" name="pcDistrict" onchange="getSubDistrictList(this.value,'pcSubDistrict','editPostalCodeErrorMessage');">
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
                                <option value="1" @if($pc_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($pc_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_pcStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="pc_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="pc_edit_cancel" name="pc_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('postal-code/list')}}'">Cancel</button>
                           <button type="button" id ="pc_edit_submit" name="pc_edit_submit" class="btn btn-dialog" onclick="submitEditPostalCode();">Submit</button>
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
    getDistrictList({{$state_data->id}},'pcDistrict','editPostalCodeErrorMessage',{{$district_data->id}});
    getSubDistrictList({{$district_data->id}},'pcSubDistrict','editPostalCodeErrorMessage',{{$pc_data->sub_district_id}});
});
</script>
@endsection
