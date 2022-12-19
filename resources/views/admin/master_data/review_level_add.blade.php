@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ReviewLevelContainer">
                <form class="" name="addReviewLevelFrm" id="addReviewLevelFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="addReviewLevelSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="addReviewLevelErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Review Level</label>
                            <input id="reviewLevel" type="text" class="form-control" name="reviewLevel" value="" >
                            <div class="invalid-feedback" id="error_validation_reviewLevel"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Designation</label>
                            <input id="designation" type="text" class="form-control" name="designation" value="" >
                            <div class="invalid-feedback" id="error_validation_designation"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Position</label>
                            <select id="position" class="form-control" name="position" >
                                <option value="">Position</option>
                                @foreach($positions as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach  
                            </select>    
                            <div class="invalid-feedback" id="error_validation_position"></div>
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
                           <button type="button" id="ReviewLevel_add_cancel" name="ReviewLevel_add_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('review-level/list')}}'">Cancel</button>
                           <button type="button" id ="ReviewLevel_add_submit" name="ReviewLevel_add_submit" class="btn btn-dialog" onclick="submitAddReviewLevel();">Submit</button>
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
