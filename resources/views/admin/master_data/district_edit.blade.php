@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="districtsContainer">
                <form class="" name="editDistrictFrm" id="editDistrictFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editDistrictSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editDistrictErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>District Name</label>
                            <input id="districtName" type="text" class="form-control" name="districtName" value="{{$district_data->district_name}}" >
                            <div class="invalid-feedback" id="error_validation_districtName"></div>
                            <input id="district_id" type="hidden" name="district_id" value="{{$district_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="districtCountry" class="form-control" name="districtCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_districtCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="districtState" class="form-control" name="districtState" >
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $district_data->state_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_districtState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="districtStatus" class="form-control" name="districtStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($district_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($district_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_districtStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="district_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="district_edit_cancel" name="district_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('districts/list')}}'">Cancel</button>
                           <button type="button" id ="district_edit_submit" name="district_edit_submit" class="btn btn-dialog" onclick="submitEditDistrict();">Submit</button>
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
