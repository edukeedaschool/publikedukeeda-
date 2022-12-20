@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editDesignationFrm" id="editDesignationFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editDesignationSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editDesignationErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Designation Name</label>
                            <input id="designationName" type="text" class="form-control" name="designationName" value="{{$designation_data->designation_name}}" >
                            <div class="invalid-feedback" id="error_validation_designationName"></div>
                            <input type="hidden" name="designation_id" id="designation_id" value="{{$designation_data->id}}">
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Representation Area</label>
                            <select id="representationArea" class="form-control" name="representationArea" >
                                <option value="">Representation Area</option>
                                @for($i=0;$i<count($rep_area_list);$i++)
                                    <?php $sel = ($rep_area_list[$i]['id'] == $designation_data->rep_area_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$rep_area_list[$i]['id']}}">{{$rep_area_list[$i]['representation_area']}}</option>
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
                                <option value="1" @if($designation_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($designation_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_designationStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="designation_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="designation_edit_cancel" name="designation_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('team-designation/list')}}'">Cancel</button>
                           <button type="button" id ="designation_edit_submit" name="designation_edit_submit" class="btn btn-dialog" onclick="submitEditDesignation();">Submit</button>
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
