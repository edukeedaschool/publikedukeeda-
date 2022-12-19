@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="addPoliticalPartyFrm" id="addPoliticalPartyFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addPoliticalPartySuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addPoliticalPartyErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Political Party Name</label>
                            <input id="ppName" type="text" class="form-control" name="ppName" value="" >
                            <div class="invalid-feedback" id="error_validation_ppName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Party Short Name</label>
                            <input id="ppShortName" type="text" class="form-control" name="ppShortName" value="" >
                            <div class="invalid-feedback" id="error_validation_ppShortName"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Party Status</label>
                            <select id="ppStatus" class="form-control" name="ppStatus" >
                                <option value="">Party Status</option>
                                <option value="national">National</option>
                                <option value="state">State</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_ppStatus"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Party Symbol</label>
                            <input id="ppSymbol" type="file" class="form-control" name="ppSymbol" value="" >
                            <div class="invalid-feedback" id="error_validation_ppSymbol"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="status" class="form-control" name="status" >
                                <option value="">Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_status"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="pp_add_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="pp_add_cancel" name="pp_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('political-party/list')}}'">Cancel</button>
                           <button type="button" id ="pp_add_submit" name="pp_add_submit" class="btn btn-dialog" onclick="submitAddPoliticalParty();">Submit</button>
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
