@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addDesignationFrm" id="addDesignationFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addDesignationSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addDesignationErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Designation Name</label>
                            <input id="designationName" type="text" class="form-control" name="designationName" value="" >
                            <div class="invalid-feedback" id="error_validation_designationName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Representation Area</label>
                            <select id="representationArea" class="form-control" name="representationArea" >
                                <option value="">Representation Area</option>
                                @for($i=0;$i<count($rep_area_list);$i++)
                                    <option value="{{$rep_area_list[$i]['id']}}">{{$rep_area_list[$i]['representation_area']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_representationArea"></div>
                        </div>
                    </div>    
                    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="designationStatus" class="form-control" name="designationStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_designationStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="designation_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="designation_add_cancel" name="designation_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('team-designation/list')}}'">Cancel</button>
                           <button type="button" id ="designation_add_submit" name="designation_add_submit" class="btn btn-dialog" onclick="submitAddDesignation();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/team.js') }}" ></script>

@endsection
