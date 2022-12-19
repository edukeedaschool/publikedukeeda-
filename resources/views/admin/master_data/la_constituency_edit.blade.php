@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editConstituencyFrm" id="editConstituencyFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editConstituencySuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editConstituencyErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Constituency Name</label>
                            <input id="constituencyName" type="text" class="form-control" name="constituencyName" value="{{$constituency_data->constituency_name}}" >
                            <div class="invalid-feedback" id="error_validation_constituencyName"></div>
                            <input id="constituency_id" type="hidden" name="constituency_id" value="{{$constituency_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="constituencyCountry" class="form-control" name="constituencyCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_constituencyCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="constituencyState" class="form-control" name="constituencyState" onchange="getDistrictListChk(this.value,'constituencyDistrictDiv','constituencyDistrictList','editConstituencyErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $state_data->id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_constituencyState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <div  id="constituencyDistrictDiv"></div>
                            <div class="invalid-feedback" id="error_validation_constituencyDistrictList"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="constituencyStatus" class="form-control" name="constituencyStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($constituency_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($constituency_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_constituencyStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="constituency_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="constituency_edit_cancel" name="constituency_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('la-constituency/list')}}'">Cancel</button>
                           <button type="button" id ="constituency_edit_submit" name="constituency_edit_submit" class="btn btn-dialog" onclick="submitEditLAConstituency();">Submit</button>
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
    getDistrictListChk({{$state_data->id}},'constituencyDistrictDiv','constituencyDistrictList','editConstituencyErrorMessage',"{{$constituency_data->district_list}}");
});
</script>
@endsection
