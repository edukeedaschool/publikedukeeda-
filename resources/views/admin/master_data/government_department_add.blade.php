@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addDepartmentFrm" id="addDepartmentFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addDepartmentSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addDepartmentErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Department Name</label>
                            <input id="departmentName" type="text" class="form-control" name="departmentName" value="" >
                            <div class="invalid-feedback" id="error_validation_departmentName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Department Short Name</label>
                            <input id="departmentShortName" type="text" class="form-control" name="departmentShortName" value="" >
                            <div class="invalid-feedback" id="error_validation_departmentShortName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Department Type</label>
                            <select id="departmentType" class="form-control" name="departmentType" onchange="toggleDepartmentTypeData(this.value);">
                                <option value="">Department Type</option>
                                <option value="national">National</option>
                                <option value="state">State</option>
                                <option value="other">Other</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_departmentType"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="countryDiv" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="departmentCountry" class="form-control" name="departmentCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <option value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_departmentCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row" id="stateDiv" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="departmentState" class="form-control" name="departmentState" >
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <option value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_departmentState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row" id="otherTypeNameDiv" style="display:none;">
                        <div class="form-group col-md-6" >
                            <label>Other Name</label>
                            <input id="departmentOtherTypeName" type="text" class="form-control" name="departmentOtherTypeName" value="" >
                            <div class="invalid-feedback" id="error_validation_departmentOtherTypeName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Department Icon</label>
                            <input id="departmentIcon" type="file" class="form-control" name="departmentIcon" value="" >
                            <div class="invalid-feedback" id="error_validation_departmentIcon"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="departmentStatus" class="form-control" name="departmentStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_departmentStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="department_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="department_add_cancel" name="department_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('govt-department/list')}}'">Cancel</button>
                           <button type="button" id ="department_add_submit" name="department_add_submit" class="btn btn-dialog" onclick="submitAddDepartment();">Submit</button>
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
