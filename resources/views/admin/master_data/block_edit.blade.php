@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editBlockFrm" id="editBlockFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editBlockSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editBlockErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Block Name</label>
                            <input id="blockName" type="text" class="form-control" name="blockName" value="{{$block_data->block_name}}" >
                            <div class="invalid-feedback" id="error_validation_blockName"></div>
                            <input id="block_id" type="hidden" name="block_id" value="{{$block_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="blockCountry" class="form-control" name="blockCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_blockCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="blockState" class="form-control" name="blockState" onchange="getDistrictList(this.value,'blockDistrict','editBlockErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $state_data->id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_blockState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District</label>
                            <select id="blockDistrict" class="form-control" name="blockDistrict" >
                                <option value="">District</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_blockDistrict"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="blockStatus" class="form-control" name="blockStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($block_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($block_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_blockStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="block_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="block_edit_cancel" name="block_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('block/list')}}'">Cancel</button>
                           <button type="button" id ="block_edit_submit" name="block_edit_submit" class="btn btn-dialog" onclick="submitEditBlock();">Submit</button>
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
    getDistrictList({{$state_data->id}},'blockDistrict','editBlockErrorMessage',{{$block_data->district_id}});
});
</script>
@endsection
