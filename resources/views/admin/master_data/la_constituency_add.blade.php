@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addConstituencyFrm" id="addConstituencyFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addConstituencySuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addConstituencyErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Constituency Name</label>
                            <input id="constituencyName" type="text" class="form-control" name="constituencyName" value="" >
                            <div class="invalid-feedback" id="error_validation_constituencyName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="constituencyCountry" class="form-control" name="constituencyCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_constituencyCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="constituencyState" class="form-control" name="constituencyState" onchange="getDistrictListChk(this.value,'constituencyDistrictDiv','constituencyDistrictList','addConstituencyErrorMessage','');">
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
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
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_constituencyStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="constituency_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="constituency_add_cancel" name="constituency_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('la-constituency/list')}}'">Cancel</button>
                           <button type="button" id ="constituency_add_submit" name="constituency_add_submit" class="btn btn-dialog" onclick="submitAddLAConstituency();">Submit</button>
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
