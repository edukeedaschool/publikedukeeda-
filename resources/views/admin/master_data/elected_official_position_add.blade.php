@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addPositionFrm" id="addPositionFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addPositionSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addPositionErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Position Name</label>
                            <input id="positionName" type="text" class="form-control" name="positionName" value="" >
                            <div class="invalid-feedback" id="error_validation_positionName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Representation Area</label>
                            <select id="positionRepresentationArea" class="form-control" name="positionRepresentationArea" >
                                <option value="">Representation Area</option>
                                @foreach($rep_area as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach    
                            </select>  
                            <div class="invalid-feedback" id="error_validation_positionRepresentationArea"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="positionStatus" class="form-control" name="positionStatus" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_positionStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="position_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="position_add_cancel" name="position_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('elected-official-position/list')}}'">Cancel</button>
                           <button type="button" id ="position_add_submit" name="position_add_submit" class="btn btn-dialog" onclick="submitAddElectedOfficialPosition();">Submit</button>
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
